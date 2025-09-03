<?php

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
        $this->assertEquals("endTurn", $this->rules->goToNextPlayer());
        $this->assertEquals(2, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(3, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("endTurn", $this->rules->goToNextPlayer());
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
        $this->assertEquals("endTurn", $this->rules->goToNextPlayer());
        $this->assertEquals(4, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(5, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(6, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("nextActivity", $this->rules->goToNextPlayer());
        $this->assertEquals(7, $this->rules->completedActivitiesCount->read());
        $this->assertEquals("endTurn", $this->rules->goToNextPlayer());
        $this->assertEquals(8, $this->rules->completedActivitiesCount->read());
    }
}