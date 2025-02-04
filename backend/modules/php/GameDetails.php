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
        $this->cards->init( "card" );
    }

    public function getData($location) : mixed
    {
        return $this->cards->getCardsInLocation($location);
    }
    public function getAll($type) : mixed
    {
        return $this->cards->getCardsOfType($type);
    }

    public function initGame($players) : void
    {
        $this->cards->createCards( CardsData::$instances, 'deck' );
        $this->deckify('ACTIVITY', 'activities');
        $this->deckify('EARNINGS', 'earnings');
        $startupTeams = array_values($this->cards->getCardsOfType( 'PRODUCT_TEAM', 0 ));
        $i = 0;
        foreach ($players as $player_id => $player) {
            $this->cards->moveCard( $startupTeams[$i++]['id'], 'teams', $player_id * 100 );
        }
        $this->cards->shuffle( 'deck' );
        foreach ($players as $player_id => $player) {
            $this->cards->pickCards( 4, 'deck', $player_id );
        }
    }

    private function deckify($type, $deckName) {
        $i = 0;
        foreach ($this->cards->getCardsOfType( $type ) as $card) {
            $this->cards->moveCard( $card['id'], $deckName, $i++ );
        }
    }

    public function getGameState(): array
    {
        $players = $this->game->loadPlayersBasicInfos();
        return [
            'public' => [
                'active_player' => $this->game->getActivePlayerId(),
                'central' => [
                    'selection' => false,
                    'activities' => [
                        ['ACTIVITY_CONFERENCE', false, false],
                        ['ACTIVITY_DEVELOPMENT', false, false],
                        ['ACTIVITY_DEPLOYMENT', false, false],
                        ['ACTIVITY_RETROSPECTIVE', false, false],
                        ['ACTIVITY_COACH', false, false],
                    ],
                    'earnings' => ['EARNINGS_CARD_1', true],
                ],
                'players' => array_map(function ($player) {
                    return [
                        'name' => $player['player_name'],
                        'teams' => [
                            ['PRODUCT_TEAM_ADVERGAME']
                        ],
                        'company' => [],
                    ];
                }, $players),
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
            '_private' => array_map(function ($v) {
                return [
                    'teams' => [
                        ['PRODUCT_TEAM_ADVERGAME']
                    ],
                    'company' => [],
                    'potential' => [
                        'AGILE_MATURITY_AGILE_PRACTITIONER',
                        'AGILE_VALUE_FEEDBACK',
                        'PRODUCT_TEAM_MMOG',
                        'AGILE_MATURITY_DEVOPS',
                    ],
                ];
            }, $players),
        ];
    }
}