<?php

namespace Bga\Games\AgileAndCo;

class GameDetails
{
    private IDeckAdapter $cards;
    private mixed $game;
    private GlobalVariable $ongoingActivity;
    private GlobalVariable $currentEarnings;

    public function __construct($cards, $game)
    {
        $this->game = $game;
        $this->cards = $cards;
        $this->ongoingActivity = new GlobalVariable($game, 'ONGOING_ACTIVITY');
        $this->currentEarnings = new GlobalVariable($game, 'CURRENT_EARNINGS');
    }

    public function initGame($players): void
    {
        $this->cards->createCards(CardsData::$instances, 'deck');
        $this->cards->deckify('ACTIVITY', 'activities');
        $this->cards->deckify('EARNINGS', 'earnings');
        $startupTeams = array_values($this->cards->getCardsOfType('PRODUCT_TEAM', 0));
        $i = 0;
        foreach ($players as $player_id => $player) {
            $this->cards->moveCard($startupTeams[$i++]['id'], 'teams', playerId: $player_id);
        }
        $this->cards->shuffle('deck');
        foreach ($players as $player_id => $player) {
            $this->cards->pickCardsForLocation(4, 'deck', 'potential', $player_id);
        }
        $this->ongoingActivity->write(['', 0]);
        $this->currentEarnings->write(0);
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

    private function getCardsInLocationSortedByIndexes(string $location, ?int $playerId = null) : array
    {
        $cards = $this->cards->getCardsInLocation($location, playerId: $playerId);
        usort($cards, fn($a, $b) => $a['index'] - $b['index']);
        $result = [];
        foreach ($cards as $card) {
            while (count($result) < $card['index']) {
                $result[] = false;
            }
            $result[] = $this->getCardName($card);
        }
        return $result;
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
            $potential = array_values($this->listCards('potential', playerId: $player['player_id']));
            $selectedInRetrospective = array_values($this->listCards('retrospective', playerId: $player['player_id']));
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
                'teams' => $this->getCardsInLocationSortedByIndexes('teams', $player_id),
                'products' => $this->getCardsInLocationSortedByIndexes('products', $player_id),
                'company' => array_values($this->listCards('company', playerId: $player['player_id'])),
                'potential' => $potential,
                'conference' => array_values($this->listCards('conference', playerId: $player['player_id'])),
            ];
        }, $players);
        $publicPlayerGames = array_map(function ($player) {
            $playerCopy = array_map(function ($item) {
                return $item;
            }, $player);
            unset($playerCopy['potential']);
            unset($playerCopy['conference']);
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
            'act_type' => $this->cards->getCardsOfType('ACTIVITY'),
            'activities' => $this->cards->getCardsInLocation('activities'),
            'earnings' => $this->cards->getCardsInLocation('earnings'),
            'teams' => $this->cards->getCardsInLocation('teams'),
            'products' => $this->cards->getCardsInLocation('products'),
            'potential' => $this->cards->getCardsInLocation('potential'),
            'discard' => $this->cards->getCardsInLocation('discard'),
            'conference' => $this->cards->getCardsInLocation('conference'),
            'deck' => $this->cards->getCardsInLocation('deck'),
            'yolo' => $playerGames,
        ];
    }

    public function chooseActivity(string $activity): string
    {
        $activityIndex = CardsData::getActivityIndex($activity);
        $selection = $this->cards->getCardsInLocation('activities', index:$activityIndex);
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

        $this->cards->moveCard($activityCard['id'], 'activities', index: $activityIndex, playerId: 1);

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
            $this->cards->pickCardsForLocation($n, 'deck', 'conference', $player_id);
        }
    }

    public function doCoach(): string
    {
        $player_id = (int)$this->game->getActivePlayerId();
        $this->cards->pickCardsForLocation(1, 'deck', 'potential', $player_id);

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

    private function listCards($location, ?int $index = null, ?int $playerId = null)
    {
        return array_map(function ($card) {
            return $this->getCardName($card);
        }, $this->cards->getCardsInLocation($location, $index, $playerId));
    }

    private function getCardName($card)
    {
        return CardsData::getFullName($card['type'], $card['type_arg']);
    }

    public function completeConference($player_id, $cards): bool
    {
        $players = $this->game->loadPlayersBasicInfos();
        $potential = $this->listCards('potential', playerId: $player_id);
        $conference = $this->listCards('conference', playerId: $player_id);
        foreach ($cards as $card) {
            $cardGroup = match (intdiv($card[0], 100)) {
                3 => $potential,
                4 => $conference,
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
        $this->cards->moveAllCardsInLocation('conference', 'potential', playerId: $player_id);
        return true;
    }

    public function completeDevelopment($player_id, $teams, $products): bool
    {
        $players = $this->game->loadPlayersBasicInfos();
        $inputs = array_map(null, $teams, $products);
        $potential = $this->listCards('potential', playerId: $player_id);
        foreach ($inputs as $input) {
            $team = $input[0];
            $teamIndex = $team[0] % 100;
            $product = $input[1];
            $productCardId = array_search($product[1], $potential);
            unset($potential[$productCardId]);
            if (!$productCardId)
                throw new \BgaUserException('Invalid development');
            $this->cards->moveCard($productCardId, 'products', $teamIndex, $player_id);
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

    private function getSingleCard($location, ?int $index = null, ?int $playerId = null) {
        return array_values($this->cards->getCardsInLocation($location, $index, $playerId))[0];
    }

    public function completeDeployment($player_id, $cards): bool
    {
        $players = $this->game->loadPlayersBasicInfos();
        $earningCard = CardsData::getAll('EARNINGS')[$this->currentEarnings->read()];
        $earnings = CardsData::$details[$earningCard];
        foreach ($cards as $card) {
            $cardName = $card[1];
            $index = $card[0] % 100;
            $product = $this->getSingleCard('products', $index, $player_id);
            if($cardName != $this->getCardName($product))
                throw new \BgaUserException('Invalid deployment');
            $cardId = $product['id'];
            $team = $this->getSingleCard('teams', $index, $player_id);
            $teamType = CardsData::$groups[$team['type']][$team['type_arg']];
            $earning = $earnings[$teamType];
            $this->cards->pickCardsForLocation($earning, 'deck', 'potential', $player_id);
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
        $potential = $this->listCards('potential', playerId: $player_id);
        $cardName = $card[1];
        $cardId = array_search($cardName, $potential);
        if (!$cardId)
            throw new \BgaUserException('Invalid retrospective choice');
        $this->cards->moveCard($cardId, 'retrospective', playerId:$player_id);
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
        $potential = $this->listCards('potential', playerId: $player_id);
        foreach ($cards as $card) {
            $cardGroup = match (intdiv($card[0], 100)) {
                3 => $potential,
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
        $this->cards->moveAllCardsInLocation('retrospective', 'company', playerId:$player_id);
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