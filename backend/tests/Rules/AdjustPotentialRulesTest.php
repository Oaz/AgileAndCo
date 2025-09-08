<?php

namespace Bga\Games\AgileAndCo\Tests;

class AdjustPotentialRulesTest extends RulesTestCase
{
    /**
     * @dataProvider potentialAdjustment
     */
    public function testCompleteAdjustment($currentPlayer, $playerSelection, $potentialSize, $powers, $expectedMessage): void
    {
        // Arrange
        $this->withMultiActivity('END_OF_ROUND', 26, [9, 26, 68, 144]);
        $initialPotential = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);
        $this->deck->pickCardsForLocation($potentialSize - count($initialPotential), 'deck', 'potential', $currentPlayer);
        $this->addTo('company', $currentPlayer, $powers);
        $selection = new FakeSelection($this->rules, $currentPlayer, $playerSelection);
        $selectedIds = $selection->getCardIds();
        $oldPotential = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);

        // Act
        $result = $this->rules->adjustPotential($currentPlayer, $selection->incomingJson());

        // Assert
        $this->assertTrue($result);

        $discards = $this->rules->repo->listCardIds('discard');
        $this->assertCount($selection->count(), $discards);
        $this->assertEquivalent($selectedIds, $discards);

        $expectedNewPotential = array_diff($oldPotential, $selectedIds);
        $actualNewPotential = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);
        $this->assertEquivalent($expectedNewPotential, $actualNewPotential);

        $this->assertEquals($expectedMessage, $this->game->lastMessage);
    }

    public static function potentialAdjustment(): array
    {
        return [
            [9, [], 4, [], ""],
            [9, [], 5, [], ""],
            [9, [], 6, [], ""],
            [9, [['potential', 1]], 7, [], "playerA loses 1 potential"],
            [9, [['potential', 1], ['potential', 5]], 8, [], "playerA loses 2 potential"],
            [9, [['potential', 1], ['potential', 2], ['potential', 5], ['potential', 9], ['potential', 10]], 11, [], "playerA loses 5 potential"],
            [9, [], 7, ['AGILE_VALUE_COURAGE'], ""],
            [9, [['potential', 5]], 8, ['AGILE_VALUE_COURAGE'], "playerA loses 1 potential"],
            [9, [], 8, ['AGILE_VALUE_COURAGE', 'AGILE_VALUE_SIMPLICITY'], ""],
            [9, [], 10, ['AGILE_MATURITY_AGILE_HR'], ""],
            [9, [['potential', 5]], 11, ['AGILE_MATURITY_AGILE_HR'], "playerA loses 1 potential"],
            [9, [], 12, ['AGILE_MATURITY_AGILE_HR', 'AGILE_VALUE_COURAGE', 'AGILE_VALUE_SIMPLICITY'], ""],
            [9, [['potential', 5]], 13, ['AGILE_MATURITY_AGILE_HR', 'AGILE_VALUE_COURAGE', 'AGILE_VALUE_SIMPLICITY'], "playerA loses 1 potential"],
        ];
    }

    /**
     * @dataProvider cannotAdjustPotential
     */
    public function testCannotCompleteAdjustment($currentPlayer, $playerSelection, $potentialSize, $powers, $expectedMessage): void
    {
        // Arrange
        $this->withMultiActivity('END_OF_ROUND', 26, [9, 26, 68, 144]);
        $initialPotential = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);
        $this->deck->pickCardsForLocation($potentialSize - count($initialPotential), 'deck', 'potential', $currentPlayer);
        $this->addTo('company', $currentPlayer, $powers);
        $selection = new FakeSelection($this->rules, $currentPlayer, $playerSelection);
        $oldPotential = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);

        // Act
        try {
            $this->rules->adjustPotential($currentPlayer, $selection->incomingJson());
            $this->fail("Expected exception BgaUserException was not thrown.");
        } catch (\BgaUserException $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());
        }

        // Assert
        $this->assertCount(0, $this->rules->repo->listCardIds('discard'));
        $this->assertEquivalent($oldPotential, $this->rules->repo->listCardIds('potential', playerId: $currentPlayer));
    }

    public static function cannotAdjustPotential(): array
    {
        return [
            [9, [], 7, [], "Should discard 1 instead of 0"],
            [9, [], 8, [], "Should discard 2 instead of 0"],
            [9, [['potential', 1]], 8, [], "Should discard 2 instead of 1"],
            [9, [], 8, ['AGILE_VALUE_COURAGE'], "Should discard 1 instead of 0"],
            [9, [], 11, ['AGILE_MATURITY_AGILE_HR'], "Should discard 1 instead of 0"],
        ];
    }

}
