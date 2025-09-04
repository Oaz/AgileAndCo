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
            $this->checkEndTurn("nextTurn", []);
            $this->checkAllActivitiesAreAvailableAgain();
        }
        $this->playRound($playerIds);
        $this->checkEndTurn("endGame", []);
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

    public function testMoveToCloseTurnIfSomePlayerHasTooManyCardsInPotential(): void
    {
        $playerIds = [9, 26, 68, 144];
        $this->arrange($playerIds);
        for ($round = 1; $round < 3; $round++) {
            $this->playRound($playerIds);
            $this->checkEndTurn("nextTurn", []);
        }
        $this->playRound($playerIds);
        $this->addTo('potential', 26, [
            ['AGILE_MATURITY_TEST_TEAM', 20],
            ['AGILE_MATURITY_USER_EXPERIENCE', 21],
            ['PRODUCT_TEAM_MMOG', 22]
        ]);
        $this->addTo('potential', 68, [
            ['AGILE_MATURITY_TEST_TEAM', 10],
            ['AGILE_MATURITY_USER_EXPERIENCE', 11],
            ['PRODUCT_TEAM_MMOG', 12],
            ['AGILE_VALUE_COURAGE', 13]
        ]);
        $this->checkEndTurn("closeTurn", [26, 68]);
    }

    private function checkEndTurn($expectedTransition, $expectedOverLimitPlayers): void
    {
        list($transition, $overLimitPlayers) = $this->rules->endTurn();
        $this->assertEquals($expectedTransition, $transition);
        $this->assertEquals($expectedOverLimitPlayers, $overLimitPlayers);
    }

    private function checkAllActivitiesAreAvailableAgain()
    {
        $usedActivities = $this->deck->getCardsInLocation('activities', playerId: 1);
        $this->assertEmpty($usedActivities);
    }

}