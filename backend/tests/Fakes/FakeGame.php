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
    private array $scores = [];
    public array $stats;

    public function __construct(array $playerIds)
    {
        $this->players = array_combine($playerIds, array_map(function ($id, $no) {
            return [
                "player_id" => $id,
                "player_name" => "player" . chr(65 + $no),
                "player_no" => $no,
            ];
        }, $playerIds, array_keys($playerIds)));
        $this->stats = [
            'table' => [],
            'player' => array_combine($playerIds, array_fill(0, count($playerIds), [])),
        ];
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
        if (count($this->activePlayers) === 0)
            return 0;
        if (count($this->activePlayers) === 1)
            return $this->activePlayers[0];
        throw new \BgaUserException("Should not call getActivePlayerId when multiple active players");
    }

    public function setActivePlayers(array $playerIds): void
    {
        $this->activePlayers = $playerIds;
    }

    public function getActivePlayerName(): string
    {
        if (count($this->activePlayers) === 0)
            return "";
        if (count($this->activePlayers) === 1)
            return $this->getPlayers()[$this->activePlayers[0]]["player_name"];
        throw new \BgaUserException("Should not call getActivePlayerName when multiple active players");
    }

    public function notifyAllPlayers(string $notification, string $message, array $args = []): void
    {
        $formattedMessage = $message;
        if (strlen($formattedMessage) > 0) {
            foreach ($args as $key => $value) {
                if($key == 'i18n')
                    continue;
                $formattedMessage = str_replace('${' . $key . '}', $value, $formattedMessage);
            }
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

    function getScore($player_id): int
    {
        return $this->scores[$player_id] ?? 0;
    }

    function setScore($player_id, $count): void
    {
        $this->scores[$player_id] = $count;
    }

    public function initializeTableStatistic(string $name, int $value) : void
    {
        $this->stats['table'][$name] = $value;
    }
    public function initializePlayerStatistic(string $name, int $value) : void
    {
        foreach ($this->players as $playerId => $player) {
            $this->stats['player'][$playerId][$name] = $value;
        }
    }
    public function incrementStatistic(string $name, int $delta, ?int $playerId = null) : void
    {
        if ($playerId === null) {
            $this->stats['table'][$name] += $delta;
        } else {
            $this->stats['player'][$playerId][$name] += $delta;
        }
    }

    public function getText(string $messageId): string
    {
        switch ($messageId) {
            case 'ACTIVITY_WAS_CHOSEN' :
                return 'ACTIVITY_WAS_CHOSEN ${player_name} ${activity}';
            case 'POTENTIAL_ADJUSTMENT' :
                return 'POTENTIAL_ADJUSTMENT ${player_name} ${potential_loss}';
            case 'ACTIVITY_DEVELOPMENT_IMPACT' :
                return 'ACTIVITY_DEVELOPMENT_IMPACT ${player_name} ${product_count}';
            case 'ACTIVITY_DEVELOPMENT_IMPACT_PLUS' :
                return 'ACTIVITY_DEVELOPMENT_IMPACT_PLUS ${player_name} ${product_count}';
            case 'ACTIVITY_DEPLOYMENT_IMPACT' :
                return 'ACTIVITY_DEPLOYMENT_IMPACT ${player_name} ${product_count} ${earnings}';
            case 'ACTIVITY_CONFERENCE_IMPACT' :
                return 'ACTIVITY_CONFERENCE_IMPACT ${player_name} ${potential_gain}';
            case 'ACTIVITY_COACH_IMPACT' :
                return 'ACTIVITY_COACH_IMPACT ${player_name}';
            case 'ACTIVITY_RETROSPECTIVE_IMPACT_MATURITY' :
                return 'ACTIVITY_RETROSPECTIVE_IMPACT_MATURITY ${player_name} ${payment} ${improvement}';
            case 'ACTIVITY_RETROSPECTIVE_IMPACT_VALUE' :
                return 'ACTIVITY_RETROSPECTIVE_IMPACT_VALUE ${player_name} ${payment} ${improvement}';
            case 'ACTIVITY_RETROSPECTIVE_IMPACT_TEAM' :
                return 'ACTIVITY_RETROSPECTIVE_IMPACT_TEAM ${player_name} ${payment} ${improvement}';
            default:
                return $messageId;
        }
    }
}