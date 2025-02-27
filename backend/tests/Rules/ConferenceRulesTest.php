<?php

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
    public function testCompleteConference($currentPlayer, $playerSelection): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_CONFERENCE', 26, [9,26,68,144]);
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
    }

    public static function conferenceCompletion(): array
    {
        return [
            [9, [['conference', 0]]],
            [26, [['conference', 0],['conference', 1],['conference', 3],['conference', 4]]],
        ];
    }

    /**
     * @dataProvider conferenceNonCompletion
     */
    public function testCannotCompleteConference($currentPlayer, $playerSelection): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_CONFERENCE', 26, [9,26,68,144]);
        $this->rules->prepareConference();
        $selection = new FakeSelection($this->rules, $currentPlayer, $playerSelection);
        $potential = $this->rules->repo->listCardIds('potential', playerId:$currentPlayer);
        $conference = $this->rules->repo->listCardIds('conference', playerId:$currentPlayer);

        // Act
        $result = $this->rules->completeConference($currentPlayer, $selection->incomingJson());

        // Assert
        $this->assertFalse($result);
        $this->assertCount(0, $this->rules->repo->listCardIds('discard'));
        $this->assertEquivalent($potential, $this->rules->repo->listCardIds('potential', playerId:$currentPlayer));
        $this->assertEquivalent($conference, $this->rules->repo->listCardIds('conference', playerId:$currentPlayer));
    }

    public static function conferenceNonCompletion(): array
    {
        return [
            [9, []],
        ];
    }
}
