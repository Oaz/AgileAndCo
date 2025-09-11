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

class StartOfRoundRulesTest extends RulesTestCase
{
    public function testStartRound(): void
    {
        // Arrange
        $this->arrange([9, 26, 68, 144]);
        $activityCards = $this->deck->getCardsInLocation("activities");
        foreach ($activityCards as $card) {
            $this->rules->useActivity($card['id'], $card['index']);
        }

        // Act
        $transition = $this->rules->startRound();

        // Assert
        $this->assertEquals("startActivities", $transition);
        $usedActivities = $this->deck->getCardsInLocation('activities', playerId: 1);
        $this->assertEmpty($usedActivities);
    }

    public function testStartActivity(): void
    {
        // Arrange
        $this->arrange([9, 26, 68, 144]);
        $this->rules->ongoingActivity->write(['WHATEVER', 666]);

        // Act
        $transition = $this->rules->startActivity();

        // Assert
        $this->assertEquals("chooseActivity", $transition);
        list($ongoingActivity, $activityInitiator) = $this->rules->ongoingActivity->read();
        $this->assertEquals("", $ongoingActivity);
        $this->assertEquals(0, $activityInitiator);
    }

}