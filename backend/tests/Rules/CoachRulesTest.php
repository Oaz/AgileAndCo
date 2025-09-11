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

class CoachRulesTest extends RulesTestCase
{
    public function testCoach(): void
    {
        // Arrange
        $this->withMonoActivity('ACTIVITY_COACH', 26, [9,26,68,144]);
        $nextPicks = $this->rules->repo->listCards('deck');
        $before = $this->rules->getGameState();

        // Act
        $transition = $this->rules->doCoach();

        // Assert
        $this->assertEquals("nextPlayer", $transition);
        $after = $this->rules->getGameState();
        $this->assertEquivalent(
            array_merge($before['_private'][26]['potential'], [$nextPicks[0]]),
            $after['_private'][26]['potential']
        );
        $this->assertEquals("Coach increases playerB potential by 1", $this->game->lastMessage);
    }
}

