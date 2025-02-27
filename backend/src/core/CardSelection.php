<?php

namespace Bga\Games\AgileAndCo;

enum SortForSelection {
    case BY_INDEX;
    case BY_USAGE;
}

class CardSelection
{
    private array $taken;
    private string $usage;
    private array $locations;

    public function __construct(string $usage, int $playerId, array $allowedLocations, CardsRepository $repo)
    {
        $this->usage = $usage;
        $this->taken = [];
        $this->locations =  array_reduce(
            array_keys($allowedLocations),
            function ($carry, $location) use ($repo, $playerId, $allowedLocations) {
                $cards = match ($allowedLocations[$location]) {
                    SortForSelection::BY_INDEX => $repo->loadAndSortByIndexFromLocation($location, $playerId),
                    SortForSelection::BY_USAGE => $repo->loadAndSortByUsageFromLocation($location, $playerId),
                };
                return $carry + [$location => $cards];
            },
            []
        );
    }

    public function take(array $card) : Card {
        $selected = $this->peek($card);
        $this->taken[$selected->id] = true;
        return $selected;
    }

    public function peek(array $card) : Card {
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
        if($this->taken[$selected->id] ?? false)
            throw new \BgaUserException("Invalid {$this->usage} - duplicate card");
        return $selected;
    }
}