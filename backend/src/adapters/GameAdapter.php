<?php

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
}