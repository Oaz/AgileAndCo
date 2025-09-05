<?php

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