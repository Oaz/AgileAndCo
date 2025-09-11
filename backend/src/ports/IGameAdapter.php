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

interface IGameAdapter
{
    public function loadInfos(): GameInfos;
    public function getActivePlayerId(): int;
    public function getActivePlayerName(): string;

    public function notifyAllPlayers(string $notification, string $message, array $args = []): void;

    public function notifyPlayer(int $player_id, string $notification, string $message, array $args = []): void;

    public function globalVariable(string $name) : IGlobalVariable;

    public function trace(string $message): void;

    function getScore($player_id): int;

    function setScore($player_id, $count): void;

    public function initializeTableStatistic(string $name, int $value) : void;
    public function initializePlayerStatistic(string $name, int $value) : void;
    public function incrementStatistic(string $name, int $delta, ?int $playerId = null) : void;

}