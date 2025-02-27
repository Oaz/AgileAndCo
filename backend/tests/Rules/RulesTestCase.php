<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\Rules;
use PHPUnit\Framework\TestCase;

abstract class RulesTestCase extends TestCase
{
    protected $deck;
    protected $game;
    protected $rules;

    protected function arrange(array $playerIds): void
    {
        $this->deck = new FakeDeck();
        $this->game = new FakeGame($playerIds);
        $this->rules = new Rules($this->deck, $this->game);
        $this->rules->initGame($this->game->getPlayers());
        $this->game->globalVariable('ONGOING_ACTIVITY')->write(['', 0]);

    }

    protected function assertEquivalent(array $a, array $b) : void {
        sort($a);
        sort($b);
        $this->assertEquals($a, $b);
    }

    protected function withMonoActivity(string $activity, int $initiator, array $playerIds): void
    {
        $this->arrange($playerIds);
        $this->rules->ongoingActivity->write([$activity, $initiator]);
        $this->game->setActivePlayers([$initiator]);
    }

    protected function withMultiActivity(string $activity, int $initiator, array $playerIds): void
    {
        $this->arrange($playerIds);
        $this->rules->ongoingActivity->write([$activity, $initiator]);
        $this->game->setActivePlayers($playerIds);
    }

    protected function addCardsToCompany($currentPlayer, $powers)
    {
        $pickable = $this->rules->repo->loadFromLocation('deck');
        foreach ($powers as $power) {
            $picks = array_filter($pickable, fn($card) => $card->fullName === $power);
            $pick = reset($picks);
            $this->deck->moveCard($pick->id, 'company', playerId: $currentPlayer);
        }
    }
}
