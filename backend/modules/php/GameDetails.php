<?php

namespace Bga\Games\AgileAndCo;

class GameDetails
{
    private mixed $cards;
    private mixed $game;
    private GlobalVariable $ongoingActivity;
    private GlobalVariable $currentEarnings;

    public function __construct($cards, $game)
    {
        $this->game = $game;
        $this->cards = $cards;
        $this->cards->init("card");
        $this->cards->autoreshuffle = true;
        $this->ongoingActivity = new GlobalVariable($game, 'ONGOING_ACTIVITY');
        $this->currentEarnings = new GlobalVariable($game, 'CURRENT_EARNINGS');
    }

    public function initGame($players): void
    {
        $this->cards->createCards(CardsData::$instances, 'deck');
        $this->deckify('ACTIVITY', 'activities');
        $this->deckify('EARNINGS', 'earnings');
        $startupTeams = array_values($this->cards->getCardsOfType('PRODUCT_TEAM', 0));
        $i = 0;
        foreach ($players as $player_id => $player) {
            $this->cards->moveCard($startupTeams[$i++]['id'], 'teams', $player_id * 100);
        }
        $this->cards->shuffle('deck');
        foreach ($players as $player_id => $player) {
            $this->cards->pickCards(4, 'deck', $player_id);
        }
        $this->ongoingActivity->write(['', 0]);
        $this->currentEarnings->write(0);
    }

    private function deckify($type, $deckName)
    {
        $i = 0;
        foreach ($this->cards->getCardsOfType($type) as $card) {
            $this->cards->moveCard($card['id'], $deckName, $i++);
        }
    }

    public function getData($location): mixed
    {
        return $this->cards->getCardsInLocation($location);
    }

    public function getAll($type): mixed
    {
        return $this->cards->getCardsOfType($type);
    }

    private function pairsToDictionary(array $pairs)
    {
        return array_reduce($pairs, fn($carry, $item) => [$item[0] => $item[1]] + $carry, []);
    }

    private function orderedArrayValues(array $array)
    {
        $keys = array_keys($array);
        sort($keys);
        return array_map(function ($key) use ($array) {
            return $array[$key];
        }, $keys);
    }

    private function getPlayerTeams($playerId)
    {
        $teams = [];
        $index = 0;
        while (true) {
            $teamCards = array_values($this->listCards('teams', $playerId * 100 + $index));
            if (count($teamCards) == 0)
                return $teams;
            $teamCardName = $teamCards[0];
            $productCards = array_values($this->listCards('products', $playerId * 100 + $index));
            $productCardName = count($productCards) == 0 ? false : $productCards[0];
            $teams[] = [$teamCardName, $productCardName];
            $index++;
        }
    }

    public function getGameState(): array
    {
        list($ongoingActivity, $activityInitiator) = $this->ongoingActivity->read();

        $players = $this->game->loadPlayersBasicInfos();
        $activePlayers = $this->game->gamestate->getActivePlayerList();
        $playerGames = array_map(function ($player) use ($ongoingActivity, $activityInitiator, $activePlayers) {
            $player_id = $player['player_id'];
            $currentActivity = in_array($player_id, $activePlayers) ? $ongoingActivity : '';
            if($currentActivity == 'ACTIVITY_RETROSPECTIVE')
                $currentActivity = 'ACTIVITY_RETROSPECTIVE_CHOOSE';
            $choice = false;
            $potential = array_values($this->listCards('hand', $player['player_id']));
            $selectedInRetrospective = array_values($this->listCards('retrospective', $player['player_id']));
            if(count($selectedInRetrospective) > 0) {
                $currentActivity = 'ACTIVITY_RETROSPECTIVE_PAYMENT';
                $choice = 300+count($potential);
                $potential[] = $selectedInRetrospective[0];
            }
            return [
                'name' => $player['player_name'],
                'activity' => $currentActivity,
                'choice' => $choice,
                'initiate' => $activityInitiator == $player_id,
                'teams' => $this->getPlayerTeams($player_id),
                'company' => array_values($this->listCards('company', $player['player_id'])),
                'potential' => $potential,
                'drawn' => array_values($this->listCards('drawn', $player['player_id'])),
            ];
        }, $players);
        $publicPlayerGames = array_map(function ($player) {
            $playerCopy = array_map(function ($item) {
                return $item;
            }, $player);
            unset($playerCopy['potential']);
            unset($playerCopy['drawn']);
            return $playerCopy;
        }, $playerGames);

        $activities = $this->pairsToDictionary(array_map(function ($activityCard) use ($ongoingActivity) {
            $locArg = $activityCard['location_arg'];
            $index = $locArg % 100;
            $activityName = CardsData::getActivities()[$index];
            $selected = $ongoingActivity == $activityName;
            $hidden = !$selected && intdiv($locArg, 100) == 1;
            return [$index, [$activityName, $hidden, $selected]];
        }, $this->cards->getCardsInLocation('activities')));

        $earnings = CardsData::getAll('EARNINGS')[$this->currentEarnings->read()];
        return [
            'public' => [
                'active_player' => $ongoingActivity == '' ? $this->game->getActivePlayerId() : 0,
                'central' => [
                    'selection' => false,
                    'activities' => $this->orderedArrayValues($activities),
                    'earnings' => [$earnings, $ongoingActivity != 'ACTIVITY_DEPLOYMENT'],
                ],
                'players' => $publicPlayerGames,
                'debug' => $this->getDebugInfos($players, $playerGames)
            ],
            '_private' => $playerGames,
        ];
    }

    private function getDebugInfos($players, array $playerGames): array
    {
        return [
            'players' => $players,
            'act_type' => $this->getAll('ACTIVITY'),
            'activities' => $this->getData('activities'),
            'earnings' => $this->getData('earnings'),
            'teams' => $this->getData('teams'),
            'products' => $this->getData('products'),
            'hand' => $this->getData('hand'),
            'discard' => $this->getData('discard'),
            'drawn' => $this->getData('drawn'),
            'deck' => $this->getData('deck'),
            'yolo' => $playerGames,
        ];
    }

    public function chooseActivity(string $activity): string
    {
        $activityLocId = CardsData::getActivityIndex($activity);
        $selection = $this->cards->getCardsInLocation('activities', $activityLocId);
        if (count($selection) === 0)
            throw new \BgaUserException('Invalid activity choice');
        $activityCard = array_values($selection)[0];

        $transition = match ($activity) {
            'ACTIVITY_CONFERENCE' => 'activityConference',
            'ACTIVITY_DEVELOPMENT' => 'activityDevelopment',
            'ACTIVITY_DEPLOYMENT' => 'activityDeployment',
            'ACTIVITY_RETROSPECTIVE' => 'activityRetrospective',
            'ACTIVITY_COACH' => 'activityCoach',
            default => throw new \BgaUserException('Invalid activity choice'),
        };

        $this->cards->moveCard($activityCard['id'], 'activities', 100 + $activityLocId);

        $player_id = (int)$this->game->getActivePlayerId();
        $this->ongoingActivity->write([$activity, $player_id]);

        $this->broadcast('${player_name} chooses activity ${activity}', [
            "player_id" => $player_id,
            "player_name" => $this->game->getActivePlayerName(),
            "activity" => $activity,
        ]);
        return $transition;
    }

    public function broadcast(string $message, array $args = []): void
    {
        $this->game->notifyAllPlayers("message", clienttranslate($message), $args);
    }

    public function prepareConference(): void
    {
        $this->broadcast('Tous à la conf!');
        list($ongoingActivity, $activityInitiator) = $this->ongoingActivity->read();
        $players = $this->game->loadPlayersBasicInfos();
        foreach ($players as $player_id => $player) {
            $n = $activityInitiator == $player_id ? 5 : 2;
            $this->cards->pickCardsForLocation($n, 'deck', 'drawn', $player_id);
        }
    }

    public function doCoach(): string
    {
        $player_id = (int)$this->game->getActivePlayerId();
        $this->cards->pickCards(1, 'deck', $player_id);

        $this->broadcast('Coach gives potential to ${player_name}', [
            "player_id" => $player_id,
            "player_name" => $this->game->getActivePlayerName(),
        ]);
        return "nextPlayer";
    }

    public function gotToNextPlayer(): string
    {
        $this->ongoingActivity->write(['', 0]);
        return "nextActivity";
    }

    private function listCards($location, $location_arg = null)
    {
        return array_map(function ($card) {
            return $this->getCardName($card);
        }, $this->cards->getCardsInLocation($location, $location_arg));
    }

    private function getCardName($card)
    {
        return CardsData::getFullName($card['type'], $card['type_arg']);
    }

    public function completeConference($player_id, $cards): bool
    {
        $players = $this->game->loadPlayersBasicInfos();
        $hand = $this->listCards('hand', $player_id);
        $drawn = $this->listCards('drawn', $player_id);
        foreach ($cards as $card) {
            $cardGroup = match (intdiv($card[0], 100)) {
                3 => $hand,
                4 => $drawn,
                default => throw new \BgaUserException('Invalid discard choice'),
            };
            $cardName = $card[1];
            $cardId = array_search($cardName, $cardGroup);
            if (!$cardId)
                throw new \BgaUserException('Invalid discard choice');
            unset($cardGroup[$cardId]);
            $this->cards->playCard($cardId);
            $this->broadcast('DEBUG: ${player_name} discards ${cardName} id ${cardId}', [
                "player_name" => $players[$player_id]['player_name'],
                "cardId" => $cardId,
                "cardName" => $cardName,
            ]);
        }
        $this->cards->moveAllCardsInLocation('drawn', 'hand', $player_id, $player_id);
        return true;
    }

    public function completeDevelopment($player_id, $teams, $products): bool
    {
        $players = $this->game->loadPlayersBasicInfos();
        $inputs = array_map(null, $teams, $products);
        $hand = $this->listCards('hand', $player_id);
        foreach ($inputs as $input) {
            $team = $input[0];
            $teamIndex = $team[0] % 100;
            $product = $input[1];
            $productCardId = array_search($product[1], $hand);
            unset($hand[$productCardId]);
            if (!$productCardId)
                throw new \BgaUserException('Invalid development');
            $this->cards->moveCard($productCardId, 'products', $player_id * 100 + $teamIndex);
            $this->broadcast('DEBUG: ${player_name} develop in team ${teamCard} with potential ${productName}', [
                "player_name" => $players[$player_id]['player_name'],
                "teamCard" => $team[1],
                "productName" => $product[1],
            ]);
        }
        return true;
    }

    public function prepareDeployment(): void
    {
        $index = rand(0, count(CardsData::getAll('EARNINGS')) - 1);
        $this->currentEarnings->write($index);
    }

    private function getSingleCardAt($location, $location_arg) {
        return array_values($this->cards->getCardsInLocation($location, $location_arg))[0];
    }

    public function completeDeployment($player_id, $cards): bool
    {
        $players = $this->game->loadPlayersBasicInfos();
        $earningCard = CardsData::getAll('EARNINGS')[$this->currentEarnings->read()];
        $earnings = CardsData::$details[$earningCard];
        foreach ($cards as $card) {
            $cardName = $card[1];
            $index = $card[0] % 100;
            $product = $this->getSingleCardAt('products', $player_id*100+$index);
            if($cardName != $this->getCardName($product))
                throw new \BgaUserException('Invalid deployment');
            $cardId = $product['id'];
            $team = $this->getSingleCardAt('teams', $player_id*100+$index);
            $teamType = CardsData::$groups[$team['type']][$team['type_arg']];
            $earning = $earnings[$teamType];
            $this->cards->pickCards($earning, 'deck', $player_id);
            $this->broadcast('DEBUG: ${player_name} deploys ${cardName} (${cardId}) from ${teamType} and earns ${earning}', [
                "player_name" => $players[$player_id]['player_name'],
                "cardName" => $cardName,
                "cardId" => $cardId,
                "teamType" => $teamType,
                "earning" => $earning,
            ]);
        }
        return true;
    }

    public function chooseForRetrospective($player_id, $card): bool
    {
        $players = $this->game->loadPlayersBasicInfos();
        $hand = $this->listCards('hand', $player_id);
        $cardName = $card[1];
        $cardId = array_search($cardName, $hand);
        if (!$cardId)
            throw new \BgaUserException('Invalid retrospective choice');
        $this->cards->moveCard($cardId, 'retrospective', $player_id);
        $this->broadcast('DEBUG: ${player_name} choose ${cardName} (${cardId}) during retrospective', [
            "player_name" => $players[$player_id]['player_name'],
            "cardName" => $cardName,
            "cardId" => $cardId,
        ]);
        return true;
    }

    public function payForRetrospective($player_id, $cards): bool
    {
        $players = $this->game->loadPlayersBasicInfos();
        $hand = $this->listCards('hand', $player_id);
        foreach ($cards as $card) {
            $cardGroup = match (intdiv($card[0], 100)) {
                3 => $hand,
                default => throw new \BgaUserException('Invalid payment choice'),
            };
            $cardName = $card[1];
            $cardId = array_search($cardName, $cardGroup);
            if (!$cardId)
                throw new \BgaUserException('Invalid payment choice');
            unset($cardGroup[$cardId]);
            $this->cards->playCard($cardId);
            $this->broadcast('DEBUG: ${player_name} pays with ${cardName} id ${cardId}', [
                "player_name" => $players[$player_id]['player_name'],
                "cardId" => $cardId,
                "cardName" => $cardName,
            ]);
        }
        $this->cards->moveAllCardsInLocation('retrospective', 'company', $player_id, $player_id);
        return true;
    }

    public function getGamePrivateState($player_id): mixed
    {
        $gameState = $this->getGameState();
        return [
            'public' => $gameState['public'],
            '_private' => $gameState['_private'][$player_id],
        ];
    }

    public function updateState($player_id): void
    {
        $this->game->notifyPlayer($player_id, 'updateState', '', $this->getGamePrivateState($player_id));
    }

}