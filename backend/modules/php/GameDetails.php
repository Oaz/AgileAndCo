<?php

namespace Bga\Games\AgileAndCo;

class GameDetails
{
    private mixed $cards;
    private mixed $game;

    public function __construct($cards, $game)
    {
        $this->game = $game;
        $this->cards = $cards;
        $this->cards->init("card");
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
        $players = $this->game->loadPlayersBasicInfos();
        $playerGames = array_map(function ($player) {
            return [
                'name' => $player['player_name'],
                'teams' => [
                    ['PRODUCT_TEAM_ADVERGAME']
                ],
                'company' => array_values($this->listCards('company', $player['player_id'])),
                'potential' => array_values($this->listCards('hand', $player['player_id'])),
            ];
        }, $players);
        $publicPlayerGames = array_map(function ($player) {
            $playerCopy = array_map(function ($item) {
                return $item;
            }, $player);
            unset($playerCopy['potential']);
            return $playerCopy;
        }, $playerGames);

        $activities = $this->pairsToDictionary(array_map(function ($activityCard) {
            $locArg = $activityCard['location_arg'];
            $index = $locArg % 100;
            $hidden = intdiv($locArg, 100) == 1;
            return [$index, [CardsData::getActivities()[$index], $hidden, false]];
        }, $this->cards->getCardsInLocation('activities')));

        return [
            'public' => [
                'active_player' => $this->game->getActivePlayerId(),
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
                    'pteams' => $this->getData('teams'),
                    'hand' => $this->getData('hand'),
                    'deck' => $this->getData('deck'),
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

    public function gotToNextPLayer(): string
    {
        $this->game->activeNextPlayer();
        return "nextActivity";
    }


}