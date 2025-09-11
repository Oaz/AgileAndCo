<?php

namespace Bga\Games\AgileAndCo\Tests;
use Bga\Games\AgileAndCo\Rules;

class EndOfRoundRulesTest extends RulesTestCase
{
    /**
     * @dataProvider roundCases
     */
    public function testMoveToNextRound(array $playerIds): void
    {
        $this->arrange($playerIds);
        $rounds = Rules::TOTAL_ACTIVITY_COUNT / count($playerIds);
        for ($round = 1; $round < $rounds; $round++) {
            $this->playRound($playerIds);
            $this->checkEndRound("nextRound", []);
        }
        $this->playRound($playerIds);
        $this->checkEndRound("endGame", []);
    }

    private function playRound(array $playerIds): void
    {
        for ($player = 0; $player < count($playerIds); $player++) {
            $this->rules->goToNextPlayer();
        }
    }

    public static function roundCases(): array
    {
        return [
            [[9, 26]],
            [[9, 26, 68]],
            [[9, 26, 68, 144]],
        ];
    }

    public function testMoveToCloseRoundIfSomePlayerHasTooManyCardsInPotential(): void
    {
        $playerIds = [9, 26, 68, 144];
        $this->arrange($playerIds);
        for ($round = 1; $round < 3; $round++) {
            $this->playRound($playerIds);
            $this->checkEndRound("nextRound", []);
        }
        $this->playRound($playerIds);
        $this->deck->pickCardsForLocation(3, 'deck', 'potential', 26);
        $this->deck->pickCardsForLocation(3, 'deck', 'potential', 68);
        $this->addTo('company', 144, ['AGILE_MATURITY_AGILE_HR']);
        $this->deck->pickCardsForLocation(4, 'deck', 'potential', 144);
        $this->checkEndRound("closeRound", [26, 68]);
    }

    private function checkEndRound($expectedTransition, $expectedOverLimitPlayers): void
    {
        list($transition, $overLimitPlayers) = $this->rules->endRound();
        $this->assertEquals($expectedTransition, $transition);
        $this->assertEquals($expectedOverLimitPlayers, $overLimitPlayers);
    }

}