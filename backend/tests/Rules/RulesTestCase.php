<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\IDeckAdapter;
use Bga\Games\AgileAndCo\IGameAdapter;
use Bga\Games\AgileAndCo\Rules;
use PHPUnit\Framework\TestCase;

abstract class RulesTestCase extends TestCase
{
    protected IDeckAdapter $deck;
    protected IGameAdapter $game;
    protected Rules $rules;

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

    protected function addTo($location, $playerId, $content)
    {
        foreach ($content as $cardDef) {
            $index = null;
            if(is_array($cardDef))
                list($fullName,$index) = $cardDef;
            else
                $fullName = $cardDef;
            $picks = array_filter(
                $this->rules->repo->loadFromLocation('deck'),
                fn($card) => $card->fullName === $fullName
            );
            $pick = reset($picks);
            $this->deck->moveCard($pick->id, $location, $index, $playerId);
        }
    }

    protected function clear($location, $playerId): void {
        $cardIds = $this->rules->repo->listCardIds($location, playerId: $playerId);
        foreach ($cardIds as $cardId) {
            $this->deck->playCard($cardId);
        }
    }
}
