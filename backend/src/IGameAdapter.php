<?php

namespace Bga\Games\AgileAndCo;

interface IGlobalVariable
{
    public function read() : mixed ;

    public function write(mixed $value) : void;

    public function delete() : void;
}

interface IGameAdapter
{
    public function loadInfos(): GameInfos;
    public function getActivePlayerId(): int;
    public function getActivePlayerName(): string;

    public function notifyAllPlayers(string $notification, string $message, array $args = []): void;

    public function notifyPlayer(int $player_id, string $notification, string $message, array $args = []): void;

    public function globalVariable(string $name) : IGlobalVariable;
}