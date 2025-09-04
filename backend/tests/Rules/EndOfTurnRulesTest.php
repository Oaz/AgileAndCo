<?php

namespace Bga\Games\AgileAndCo\Tests;

class EndOfTurnRulesTest extends RulesTestCase
{
    /**
     * @dataProvider turnCases
     */
    public function testMoveToNextTurn(array $playerIds): void
    {
        $this->arrange($playerIds);
        $rounds = 48 / count($playerIds);
        for ($round = 1; $round < $rounds; $round++) {
            $this->playRound($playerIds);
            $activityCards = $this->deck->getCardsInLocation("activities");
            foreach ($activityCards as $card) {
                $this->rules->useActivity($card['id'],$card['index']);
            }
            $this->assertEquals("nextTurn", $this->rules->endTurn());
            $usedActivities = $this->deck->getCardsInLocation("activities", playerId: 1);
            $this->assertEmpty($usedActivities);
        }
        $this->playRound($playerIds);
        $this->assertEquals("endGame", $this->rules->endTurn());
    }

    private function playRound(array $playerIds): void
    {
        for ($player = 0; $player < count($playerIds); $player++) {
            $this->rules->goToNextPlayer();
        }
    }

    public static function turnCases(): array
    {
        return [
            [[9, 26]],
            [[9, 26, 68]],
            [[9, 26, 68, 144]],
        ];
    }
}