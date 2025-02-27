<?php

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
    }
}

