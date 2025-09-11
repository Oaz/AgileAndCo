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

class GameInitializationRulesTest extends RulesTestCase
{
    /**
     * @dataProvider gameInitProvider
     */
    public function testInitGameWithNPlayers(array $playerIds): void
    {
        // Arrange
        $this->arrange($playerIds);

        // Assert
        $this->assertCount(98-5*count($playerIds), $this->deck->getCardsInLocation('deck'));
        $this->assertCount(5, $this->deck->getCardsInLocation('activities'));
        $this->assertCount(4, $this->deck->getCardsInLocation('earnings'));
        foreach ($playerIds as $playerId) {
            $actualTeams = $this->deck->getCardsInLocation('teams', playerId:$playerId);
            $this->assertCount(1, $actualTeams);
            $this->assertEquals('PRODUCT_TEAM', $actualTeams[0]['type']);
            $this->assertEquals(0, $actualTeams[0]['type_arg']);
            $this->assertCount(4, $this->deck->getCardsInLocation('potential', playerId:$playerId));
        }
    }

    public static function gameInitProvider(): array
    {
        return [
            [[9,26]],
            [[9,26,68]],
            [[9,26,68,144]],
        ];
    }
}
