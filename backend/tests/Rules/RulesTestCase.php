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
}
