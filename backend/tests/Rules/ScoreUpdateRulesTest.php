<?php

namespace Bga\Games\AgileAndCo\Tests;
use Bga\Games\AgileAndCo\Rules;
use Bga\Games\AgileAndCo\IScoreComputer;

class FakeScoreComputer  implements IScoreComputer
{
    public int $round;
    public function computeScore($teams, $company, $potential, $gameIsComplete) : int {
        return $gameIsComplete ? 1000+$this->round : $this->round;
    }
}
class ScoreUpdateRulesTest extends RulesTestCase
{
    /**
     * @dataProvider roundCases
     */
    public function testPublishScores(array $playerIds): void
    {
        $scoreComputer = new FakeScoreComputer();
        $this->arrange($playerIds, $scoreComputer);
        $rounds = Rules::TOTAL_ACTIVITY_COUNT / count($playerIds);
        for ($round = 1; $round < $rounds; $round++) {
            $scoreComputer->round = $round;
            $this->playRound($playerIds);
            $this->rules->endRound();
            foreach ($playerIds as $playerId) {
                $this->assertEquals($round, $this->game->getScore($playerId));
            }
        }
        $scoreComputer->round = $round;
        $this->playRound($playerIds);
        $this->rules->endRound();
        foreach ($playerIds as $playerId) {
            $this->assertEquals(1000+$round, $this->game->getScore($playerId));
        }
    }

    private function playRound(array $playerIds): void
    {
        foreach ($playerIds as $playerId) {
            $this->rules->updateState($playerId);
            $this->rules->goToNextPlayer();
        }
    }

    public static function roundCases(): array
    {
        return [
            [[9, 26]],
        ];
    }

}