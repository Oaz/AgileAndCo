<?php

namespace Bga\Games\AgileAndCo;

readonly class GameInfos
{
    public function __construct($players, $activePlayers)
    {
        $this->players = $players;
        $this->activePlayers = $activePlayers;
    }
    public array $players;
    public array $activePlayers;
    public function getPlayerName($playerId): string
    {
        return $this->players[$playerId]['player_name'];
    }
}