<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\GameInfos;
use Bga\Games\AgileAndCo\IGameAdapter;
use Bga\Games\AgileAndCo\IGlobalVariable;

class FakeGame implements IGameAdapter
{
    public string $lastMessage = "";
    
    private array $players;
    private array $activePlayers = [];
    private array $globalVariables = [];

    public function __construct(array $playerIds)
    {
        $this->players = array_combine($playerIds, array_map(function($id, $no) {
            return [
                "player_id" => $id,
                "player_name" => "player" . chr(65 + $no),
                "player_no" => $no,
            ];
        }, $playerIds, array_keys($playerIds)));
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function loadInfos(): GameInfos
    {
        return new GameInfos($this->getPlayers(), $this->activePlayers);
    }

    public function getActivePlayerId(): int
    {
        if(count($this->activePlayers) === 0)
            return 0;
        if(count($this->activePlayers) === 1)
            return $this->activePlayers[0];
        throw new \BgaUserException("Should not call getActivePlayerId when multiple active players");
    }

    public function setActivePlayers(array $playerIds): void
    {
        $this->activePlayers = $playerIds;
    }

    public function getActivePlayerName(): string
    {
        if(count($this->activePlayers) === 0)
            return "";
        if(count($this->activePlayers) === 1)
            return $this->getPlayers()[$this->activePlayers[0]]["player_name"];
        throw new \BgaUserException("Should not call getActivePlayerName when multiple active players");
    }

    public function notifyAllPlayers(string $notification, string $message, array $args = []): void
    {
        $formattedMessage = $message;
        foreach ($args as $key => $value) {
            $formattedMessage = str_replace('${' . $key . '}', $value, $formattedMessage);
        }
        $this->lastMessage = $formattedMessage;
    }

    public function notifyPlayer(int $player_id, string $notification, string $message, array $args = []): void
    {
        // TODO: Implement notifyPlayer() method.
    }

    public function globalVariable(string $name): IGlobalVariable
    {
        return $this->globalVariables[$name] ?? ($this->globalVariables[$name] = new FakeGlobalVariable());
    }

    public function trace(string $message): void
    {
        // TODO: Implement trace() method.
    }
}