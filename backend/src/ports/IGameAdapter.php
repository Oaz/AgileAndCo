<?php

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