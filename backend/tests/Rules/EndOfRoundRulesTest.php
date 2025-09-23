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
use Bga\Games\AgileAndCo\Rules;

class EndOfRoundRulesTest extends RulesTestCase
{
    /**
     * @dataProvider roundCases
     */
    public function testMoveToNextRound(array $playerIds): void
    {
        $this->arrange($playerIds);
        $rounds = $this->rules->totalActivityCount->readState() / count($playerIds);
        for ($round = 1; $round < $rounds; $round++) {
            $this->playRound($playerIds);
            $this->checkEndRound("nextRound", []);
        }
        $this->playRound($playerIds);
        $this->checkEndRound("endGame", []);
    }

    private function playRound(array $playerIds): void
    {
        for ($player = 0; $player < count($playerIds); $player++) {
            $this->rules->goToNextPlayer();
        }
    }

    public static function roundCases(): array
    {
        return [
            [[9, 26]],
            [[9, 26, 68]],
            [[9, 26, 68, 144]],
        ];
    }

    public function testMoveToCloseRoundIfSomePlayerHasTooManyCardsInPotential(): void
    {
        $playerIds = [9, 26, 68, 144];
        $this->arrange($playerIds);
        for ($round = 1; $round < 3; $round++) {
            $this->playRound($playerIds);
            $this->checkEndRound("nextRound", []);
        }
        $this->playRound($playerIds);
        $this->deck->pickCardsForLocation(3, 'deck', 'potential', 26);
        $this->deck->pickCardsForLocation(3, 'deck', 'potential', 68);
        $this->addTo('company', 144, ['AGILE_MATURITY_AGILE_HR']);
        $this->deck->pickCardsForLocation(4, 'deck', 'potential', 144);
        $this->checkEndRound("closeRound", [26, 68]);
    }

    private function checkEndRound($expectedTransition, $expectedOverLimitPlayers): void
    {
        list($transition, $overLimitPlayers) = $this->rules->endRound();
        $this->assertEquals($expectedTransition, $transition);
        $this->assertEquals($expectedOverLimitPlayers, $overLimitPlayers);
    }

}