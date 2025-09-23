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

use Bga\Games\AgileAndCo\IDeckAdapter;
use Bga\Games\AgileAndCo\IGameAdapter;
use Bga\Games\AgileAndCo\IScoreComputer;
use Bga\Games\AgileAndCo\Rules;
use PHPUnit\Framework\TestCase;

abstract class RulesTestCase extends TestCase
{
    protected IDeckAdapter $deck;
    protected IGameAdapter $game;
    protected Rules $rules;


    protected function arrange(array $playerIds, IScoreComputer $scoreComputer = null): void
    {
        $this->deck = new FakeDeck();
        $this->game = new FakeGame($playerIds);
        $this->game->globalVariable('game_length')->write(48);
        $this->rules = new Rules($this->deck, $this->game, $scoreComputer);
        $this->rules->initGame($this->game->getPlayers());
        $this->game->globalVariable('ONGOING_ACTIVITY')->write(['', 0]);
    }

    protected function assertEquivalent(array $a, array $b) : void {
        sort($a);
        sort($b);
        $this->assertEquals($a, $b);
    }

    protected function withMonoActivity(string $activity, int $initiator, array $playerIds): void
    {
        $this->arrange($playerIds);
        $this->startActivity($activity, $initiator, [$initiator]);
    }

    protected function withMultiActivity(string $activity, int $initiator, array $playerIds): void
    {
        $this->arrange($playerIds);
        $this->startActivity($activity, $initiator, $playerIds);
    }

    public function startActivity(string $activity, int $initiator, array $playerIds): void
    {
        $this->rules->ongoingActivity->write([$activity, $initiator]);
        $this->game->setActivePlayers($playerIds);
    }

    protected function addTo($location, $playerId, $content)
    {
        foreach ($content as $cardDef) {
            $index = null;
            if(is_array($cardDef))
                list($fullName,$index) = $cardDef;
            else
                $fullName = $cardDef;
            $picks = array_filter(
                $this->rules->repo->loadFromLocation('deck'),
                fn($card) => $card->fullName === $fullName
            );
            $pick = reset($picks);
            $this->deck->moveCard($pick->id, $location, $index, $playerId);
        }
    }

    protected function clear($location, $playerId): void {
        $cardIds = $this->rules->repo->listCardIds($location, playerId: $playerId);
        foreach ($cardIds as $cardId) {
            $this->deck->playCard($cardId);
        }
    }
}
