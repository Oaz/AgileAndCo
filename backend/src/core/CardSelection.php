<?php

namespace Bga\Games\AgileAndCo;

class CardSelection
{
    private CardsRepository $repo;
    private array $taken;
    private string $usage;
    private int $playerId;
    private array $locations;

    public function __construct(string $usage, int $playerId, array $allowedLocations, CardsRepository $repo)
    {
        $this->usage = $usage;
        $this->playerId = $playerId;
        $this->repo = $repo;
        $this->taken = [];
        $this->locations =  array_reduce(
            $allowedLocations,
            fn($carry, $location) => $carry + [$location => $this->repo->loadAndSortFromLocation($location, $this->playerId)],
            []
        );
    }

    public function take(array $card) : Card {
        $cardGroup = $this->locations[$card['zone']] ?? null;
        if($cardGroup == null)
            throw new \BgaUserException("Invalid {$this->usage} - location not allowed");
        /** @var Card $selected */
        $selected = $cardGroup[$card['index']] ?? null;
        if ($selected === null)
            throw new \BgaUserException("Invalid {$this->usage} - unexpected index");
        $selected = $selected->withIndex($card['index']);
        if ($selected->fullName !== $card['name'])
            throw new \BgaUserException("Invalid {$this->usage} - card name mismatch: expected {$selected->fullName} but was {$card['name']}");
        $cardId = $selected->id;
        if($this->taken[$cardId] ?? false)
            throw new \BgaUserException("Invalid {$this->usage} - duplicate card");
        $this->taken[$cardId] = true;
        return $selected;
    }
}