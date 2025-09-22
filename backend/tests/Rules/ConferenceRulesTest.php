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

class ConferenceRulesTest extends RulesTestCase
{
    public function testPrepareConference(): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_CONFERENCE', 26, [9,26,68,144]);
        $before = $this->rules->getGameState();
        $this->assertCount(0, $before['_private'][26]['conference']);
        $this->assertCount(0, $before['_private'][9]['conference']);
        $this->assertCount(0, $before['_private'][68]['conference']);
        $this->assertCount(0, $before['_private'][144]['conference']);

        // Act
        $this->rules->prepareConference();

        // Assert
        $after = $this->rules->getGameState();
        $this->assertCount(5, $after['_private'][26]['conference']);
        $this->assertCount(2, $after['_private'][9]['conference']);
        $this->assertCount(2, $after['_private'][68]['conference']);
        $this->assertCount(2, $after['_private'][144]['conference']);
    }

    /**
     * @dataProvider conferenceCompletion
     */
    public function testCompleteConference($currentPlayer, $playerSelection, $powers, $expectedMessage): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_CONFERENCE', 26, [9,26,68,144]);
        $this->addTo('company', $currentPlayer, $powers);
        $this->rules->prepareConference();
        $selection = new FakeSelection($this->rules, $currentPlayer, $playerSelection);
        $selectedIds = $selection->getCardIds();
        $oldPotential = $this->rules->repo->listCardIds('potential', playerId:$currentPlayer);
        $conference = $this->rules->repo->listCardIds('conference', playerId:$currentPlayer);

        // Act
        $result = $this->rules->completeConference($currentPlayer, $selection->incomingJson());

        // Assert
        $this->assertTrue($result);

        $discards = $this->rules->repo->listCardIds('discard');
        $this->assertCount($selection->count(), $discards);
        $this->assertEquivalent($selectedIds, $discards);

        $expectedNewPotential = array_diff(array_merge($oldPotential,$conference),$selectedIds);
        $actualNewPotential = $this->rules->repo->listCardIds('potential', playerId:$currentPlayer);
        $this->assertEquivalent($expectedNewPotential, $actualNewPotential);
        $this->assertEquals($expectedMessage, $this->game->lastMessage);
    }

    public static function conferenceCompletion(): array
    {
        return [
            [9, [['conference', 0]], [], "ACTIVITY_CONFERENCE_IMPACT playerA 1"],
            [9, [['conference', 1]], [], "ACTIVITY_CONFERENCE_IMPACT playerA 1"],
            [26, [['conference', 0],['conference', 1],['conference', 3],['conference', 4]], [], "ACTIVITY_CONFERENCE_IMPACT playerB 1"],
            [9, [], ['AGILE_MATURITY_AGILE_ORGANIZER'], "ACTIVITY_CONFERENCE_IMPACT playerA 2"],
            [26, [['conference', 1],['conference', 3],['conference', 4]], ['AGILE_MATURITY_AGILE_ORGANIZER'], "ACTIVITY_CONFERENCE_IMPACT playerB 2"],
            [9, [['potential', 0]], ['AGILE_MATURITY_AGILE_PRACTITIONER'], "ACTIVITY_CONFERENCE_IMPACT playerA 1"],
            [26, [['potential', 0],['conference', 1],['conference', 3],['conference', 4]], ['AGILE_MATURITY_AGILE_PRACTITIONER'], "ACTIVITY_CONFERENCE_IMPACT playerB 1"],
            [26, [['potential', 1],['conference', 3],['conference', 4]], ['AGILE_MATURITY_AGILE_ORGANIZER', 'AGILE_MATURITY_AGILE_PRACTITIONER'], "ACTIVITY_CONFERENCE_IMPACT playerB 2"],
        ];
    }

    /**
     * @dataProvider conferenceNonCompletion
     */
    public function testCannotCompleteConference($currentPlayer, $playerSelection, $powers, $expectedMessage): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_CONFERENCE', 26, [9,26,68,144]);
        $this->addTo('company', $currentPlayer, $powers);
        $this->rules->prepareConference();
        $selection = new FakeSelection($this->rules, $currentPlayer, $playerSelection);
        $potential = $this->rules->repo->listCardIds('potential', playerId:$currentPlayer);
        $conference = $this->rules->repo->listCardIds('conference', playerId:$currentPlayer);

        // Act
        try {
            $this->rules->completeConference($currentPlayer, $selection->incomingJson());
            $this->fail("Expected exception BgaUserException was not thrown.");
        } catch (\BgaUserException $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());
        }

        // Assert
        $this->assertCount(0, $this->rules->repo->listCardIds('discard'));
        $this->assertEquivalent($potential, $this->rules->repo->listCardIds('potential', playerId:$currentPlayer));
        $this->assertEquivalent($conference, $this->rules->repo->listCardIds('conference', playerId:$currentPlayer));

    }

    public static function conferenceNonCompletion(): array
    {
        return [
            [9, [], [], "Should discard 1 instead of 0"],
            [26, [['conference', 0],['conference', 1],['conference', 3]], [], "Should discard 4 instead of 3"],
            [26, [['conference', 0],['conference', 3]], ['AGILE_MATURITY_AGILE_ORGANIZER'], "Should discard 3 instead of 2"],
            [9, [['potential', 0]], [], "Invalid discard - location not allowed"],
            [26, [['potential', 0],['conference', 1],['conference', 3],['conference', 4]], [], "Invalid discard - location not allowed"],

        ];
    }

}
