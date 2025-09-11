<?php
/*
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Bga\Games\AgileAndCo;

class GameAdapter implements IGameAdapter
{
    private $game;

    public function __construct($game)
    {
        $this->game = $game;
    }

    public function loadInfos(): GameInfos
    {
        $infos = new GameInfos(
            $this->game->loadPlayersBasicInfos(),
            $this->game->gamestate->getActivePlayerList()
        );
        return $infos;
    }

    public function getActivePlayerId(): int
    {
        return $this->game->getActivePlayerId();
    }

    public function getActivePlayerName(): string
    {
        return $this->game->getActivePlayerName();
    }

    public function notifyAllPlayers(string $notification, string $message, array $args = []): void
    {
        $this->game->notifyAllPlayers($notification, clienttranslate($message), $args);
    }

    public function notifyPlayer(int $player_id, string $notification, string $message, array $args = []): void
    {
        $this->game->notifyPlayer($player_id, $notification, clienttranslate($message), $args);
    }

    public function globalVariable(string $name): IGlobalVariable
    {
        return new GlobalVariable($this->game, $name);
    }

    public function trace(string $message): void {
        $this->game->trace($message);
    }

    function getScore($player_id):int {
        return $this->game->getUniqueValueFromDB("SELECT `player_score` FROM `player` WHERE `player_id` = '$player_id'");
    }

    function setScore($player_id, $count):void {
        $this->game->DbQuery("UPDATE `player` SET `player_score` = '$count' WHERE `player_id` = '$player_id'");
    }

    public function initializeTableStatistic(string $name, int $value) : void
    {
        $this->game->initStat('table', $name, $value);
    }
    public function initializePlayerStatistic(string $name, int $value) : void
    {
        $this->game->initStat('player', $name, $value);
    }
    public function incrementStatistic(string $name, int $delta, ?int $playerId = null) : void
    {
        $this->game->incStat($delta, $name, $playerId);
    }

}