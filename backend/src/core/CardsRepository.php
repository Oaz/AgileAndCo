<?php

namespace Bga\Games\AgileAndCo;

class CardsRepository
{
    public function __construct(array $groups, array $details, IDeckAdapter $deckAdapter)
    {
        $this->groups = $groups;
        $this->details = $details;
        $this->deck = $deckAdapter;
        $cards = array_reduce(array_keys($groups), function($carry, $groupName) use($groups) {
            return array_merge($carry, array_map(
                fn($index) => new Card(0, $groupName, $groups[$groupName][$index], 0, $groupName, $index),
                array_keys($groups[$groupName])));
        }, []);
        $this->cardsReference = array_combine(array_map(fn($c) => $c->fullName, $cards), $cards);
    }

    private array $groups;
    private array $details;
    private array $cardsReference;
    public readonly IDeckAdapter $deck;

    public function getAll($groupName): array
    {
        return array_map(function ($a) use ($groupName) {
            return $groupName . '_' . $a;
        }, $this->groups[$groupName]);
    }

    public function getIndex($groupName, $cardName): int
    {
        return array_search($cardName, $this->groups[$groupName]);
    }

    public function getActivities(): array
    {
        return $this->getAll('ACTIVITY');
    }

    public function getActivityIndex($activityName): int
    {
        return array_search($activityName, $this->getActivities());
    }

    public function getCardsInLocationSortedByIndexes(string $location, int $playerId): array
    {
        return array_map(function ($card) {
            return $card->fullName;
        }, $this->loadAndSortByIndexFromLocation($location, $playerId));
    }

    public function loadAndSortByIndexFromLocation(string $location, ?int $playerId): array
    {
        $cards = $this->loadFromLocation($location, playerId: $playerId);
        usort($cards, fn($a, $b) => $a->index - $b->index);
        $result = [];
        foreach ($cards as $card) {
            while (count($result) < $card->index) {
                $result[] = false;
            }
            $result[] = $card;
        }
        return $result;
    }

    public function getCardsInLocationSortedByUsage(string $location, int $playerId): array
    {
        return array_map(function ($card) {
            return $card->fullName;
        }, $this->loadAndSortByUsageFromLocation($location, $playerId));
    }

    public function loadAndSortByUsageFromLocation(string $location, ?int $playerId): array
    {
        $cards = $this->loadFromLocation($location, playerId: $playerId);
        usort($cards, [$this, 'naturalOrder']);
        return $cards;
    }

    public function naturalOrder(PlayerCard $a, PlayerCard $b): int
    {
        if ($a->type != $b->type) {
            $types = array_keys($this->groups);
            $ia = array_search($a->type, $types);
            $ib = array_search($b->type, $types);
            return $ia - $ib;
        }
        if ($a->cost != $b->cost)
            return $a->cost - $b->cost;
        if ($a->name == $b->name)
            return 0;
        return $a->name > $b->name ? 1 : -1;
    }

    public function listCards($location, ?int $index = null, ?int $playerId = null)
    {
        return array_map(function ($card) {
            return $card->fullName;
        }, $this->loadFromLocation($location, $index, $playerId));
    }

    public function getSingleCard($location, ?int $index = null, ?int $playerId = null)
    {
        return array_values($this->loadFromLocation($location, $index, $playerId))[0];
    }

    public function loadFromLocation(string $location, ?int $index = null, ?int $playerId = null): array
    {
        return array_map(function ($data) {
            return $this->createCard($data);
        }, $this->deck->getCardsInLocation($location, $index, $playerId));
    }

    public function createCard(array $data)
    {
        $group = $this->groups[$data['type']];
        $card = new Card(
            $data['id'], $data['type'], $group[$data['type_arg']],
            $data['player_id'], $data['location'], $data['index']
        );
        $details = $this->details[$card->fullName] ?? [];
        $cost = $details['cost'] ?? null;
        if ($cost == null)
            return $card;
        return new PlayerCard($card, $cost);
    }

    public function moveCardsToLocation(array $fullNames, string $location, ?int $playerId = null): void
    {
        $moved = [];
        foreach ($fullNames as $fullName) {
            $cardReference = $this->cardsReference[$fullName];
            $deck_cards = $this->deck->getCardsOfType($cardReference->type, $cardReference->index);
            foreach ($deck_cards as $deck_card) {
                $cardId = $deck_card['id'];
                if (isset($moved[$cardId]))
                    continue;
                $this->deck->moveCard($cardId, $location, playerId: $playerId);
                $moved[$cardId] = true;
                break;
            }
        }
    }

}