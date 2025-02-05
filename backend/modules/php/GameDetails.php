<?php

namespace Bga\Games\AgileAndCo;

class GameDetails
{
    private mixed $cards;
    private mixed $game;
    private GlobalVariable $ongoingActivity;

    public function __construct($cards, $game)
    {
        $this->game = $game;
        $this->cards = $cards;
        $this->cards->init("card");
        $this->cards->autoreshuffle = true;
        $this->ongoingActivity = new GlobalVariable($game, 'ONGOING_ACTIVITY');
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

    public function listCards($location, $locationArg): array
    {
        return array_map(function ($card) {
            $card_type = $card['type'];
            $card_name = CardsData::$groups[$card_type][$card['type_arg']];
            return $card_type . '_' . $card_name;
        }, $this->cards->getCardsInLocation($location, $locationArg));
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

    public function getGameState(): array
    {
        list($ongoingActivity, $activityInitiator) = $this->ongoingActivity->read();

        $players = $this->game->loadPlayersBasicInfos();
        $activePlayers = $this->game->gamestate->getActivePlayerList();
        $playerGames = array_map(function ($player) use($ongoingActivity, $activityInitiator, $activePlayers) {
            $player_id = $player['player_id'];
            return [
                'activity' =>  in_array($player_id, $activePlayers) ? $ongoingActivity : '',
                'initiate' => $activityInitiator == $player_id,
                'teams' => [
                    ['PRODUCT_TEAM_ADVERGAME']
                ],
                'company' => array_values($this->listCards('company', $player['player_id'])),
                'potential' => array_values($this->listCards('hand', $player['player_id'])),
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

        $activities = $this->pairsToDictionary(array_map(function ($activityCard) use($ongoingActivity) {
            $locArg = $activityCard['location_arg'];
            $index = $locArg % 100;
            $activityName = CardsData::getActivities()[$index];
            $selected = $ongoingActivity == $activityName;
            $hidden = !$selected && intdiv($locArg, 100) == 1;
            return [$index, [$activityName, $hidden, $selected]];
        }, $this->cards->getCardsInLocation('activities')));


        return [
            'public' => [
                'active_player' => $ongoingActivity=='' ? $this->game->getActivePlayerId() : 0,
                'central' => [
                    'selection' => false,
                    'activities' => $this->orderedArrayValues($activities),
                    'earnings' => ['EARNINGS_CARD_1', true],
                ],
                'players' => $publicPlayerGames,
                'misc' => [
                    'players' => $players,
                    'act_type' => $this->getAll('ACTIVITY'),
                    'activities' => $this->getData('activities'),
                    'earnings' => $this->getData('earnings'),
                    'teams' => $this->getData('teams'),
                    'hand' => $this->getData('hand'),
                    'drawn' => $this->getData('drawn'),
                    'deck' => $this->getData('deck'),
                    'yolo' => $playerGames,
                ]
            ],
            '_private' => $playerGames,
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

    public function doConference(): void
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

    private function getCardsFullNames($location, $location_arg=null) {
        return array_map(function ($card) {
            return CardsData::getFullName($card['type'],$card['type_arg']);
        }, $this->cards->getCardsInLocation($location, $location_arg));
    }

    public function completeConference($player_id, $cards) : bool
    {
        $players = $this->game->loadPlayersBasicInfos();
        $hand = $this->getCardsFullNames('hand', $player_id);
        $drawn = $this->getCardsFullNames('drawn', $player_id);
        foreach ($cards as $card) {
            $cardGroup = match (intdiv($card[0], 100)) {
                3 => $hand,
                4 => $drawn,
                default => throw new \BgaUserException('Invalid discard choice'),
            };
            $cardName = $card[1];
            $cardId = array_search($cardName, $cardGroup);
            if(!$cardId)
                throw new \BgaUserException('Invalid discard choice');
            unset($cardGroup[$cardId]);
            $this->cards->playCard($cardId);
//            $this->broadcast('Discard ${player_name} (${player_id}): CARD name ${cardName} id ${cardId}', [
//                "player_id" => $player_id,
//                "player_name" => $players[$player_id]['player_name'],
//                "cardId" => $cardId,
//                "cardName" => $cardName,
//            ]);
        }
        $this->cards->moveAllCardsInLocation( 'drawn', 'hand', $player_id, $player_id );
        return true;
    }

    public function updateState($player_id): void
    {
        $gameState = $this->getGameState();
        $this->game->notifyPlayer($player_id, 'updateState', '', [
            'public' => $gameState['public'],
            '_private' => $gameState['_private'][$player_id],
        ]);
    }
}