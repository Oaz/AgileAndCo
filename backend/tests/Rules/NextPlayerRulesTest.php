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

class NextPlayerRulesTest extends RulesTestCase
{
    public function testGoThrough2PlayersUntilAllChosenAnActivity(): void
    {
        $playerIds = [9,26];
        $this->arrange($playerIds);

        $this->assertEquals(0, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(1, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("endRound", $this->rules->goToNextPlayer());
        $this->assertEquals(2, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(3, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("endRound", $this->rules->goToNextPlayer());
        $this->assertEquals(4, $this->rules->completedActivitiesCount->read());
    }

    public function testGoThrough4PlayersUntilAllChosenAnActivity(): void
    {
        $playerIds = [9,26,68,144];
        $this->arrange($playerIds);

        $this->assertEquals(0, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(1, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(2, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(3, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("endRound", $this->rules->goToNextPlayer());
        $this->assertEquals(4, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(5, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(6, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(7, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("endRound", $this->rules->goToNextPlayer());
        $this->assertEquals(8, $this->rules->completedActivitiesCount->read());
    }
}