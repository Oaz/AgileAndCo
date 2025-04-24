<?php

namespace Bga\Games\AgileAndCo;

readonly class PlayerCard extends Card
{
    public function __construct(Card $card, int $cost, int $score)
    {
        parent::__construct($card->id, $card->type, $card->name, $card->playerId, $card->location, $card->index);
        $this->cost = $cost;
        $this->score = $score;
    }

    public int $cost;
    public int $score;
}