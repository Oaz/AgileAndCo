<?php

namespace Bga\Games\AgileAndCo;

class CardsRepository
{
    public function __construct(array $groups, array $details, IDeckAdapter $deckAdapter)
    {
        $this->groups = $groups;
        $this->details = $details;
        $this->deck = $deckAdapter;
    }

    private array $groups;
    private array $details;
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
        $cards = $this->loadFromLocation($location, playerId: $playerId);
        usort($cards, fn($a, $b) => $a->index - $b->index);
        $result = [];
        foreach ($cards as $card) {
            while (count($result) < $card->index) {
                $result[] = false;
            }
            $result[] = $card->fullName;
        }
        return $result;
    }

    public function getCardsInLocationSortedByUsage(string $location, int $playerId): array
    {
        return array_map(function ($card) {
            return $card->fullName;
        }, $this->loadAndSortFromLocation($location, $playerId));
    }

    public function loadAndSortFromLocation(string $location, ?int $playerId): array
    {
        $cards = $this->loadFromLocation($location, playerId: $playerId);
        usort($cards, [$this, 'naturalOrder']);
        return $cards;
    }

    public function naturalOrder(PlayerCard $a, PlayerCard $b):int {
        if($a->type == $b->type)
            return $a->cost - $b->cost;
        $types = array_keys($this->groups);
        $ia = array_search($a->type, $types);
        $ib = array_search($b->type, $types);
        return $ia - $ib;
    }

    public function listCards($location, ?int $index = null, ?int $playerId = null)
    {
        return array_map(function ($card) {
            return $card->fullName;
        }, $this->loadFromLocation($location, $index, $playerId));
    }

    public function getSingleCard($location, ?int $index = null, ?int $playerId = null) {
        return array_values($this->loadFromLocation($location, $index, $playerId))[0];
    }

    public function getCardName($card): string
    {
        return $card['type'] . '_' . $this->getCardSubName($card);
    }

    public function getCardSubName($card): string
    {
        return $this->groups[$card['type']][$card['type_arg']];
    }

    public function loadFromLocation(string $location, ?int $index = null, ?int $playerId = null): array {
        return array_map(function ($data) {
            return $this->createCard($data);
        }, $this->deck->getCardsInLocation($location, $index, $playerId));
    }

    public function createCard(array $data) {
        $group = $this->groups[$data['type']];
        $card = new Card(
            $data['id'], $data['type'], $group[$data['type_arg']],
            $data['player_id'], $data['location'], $data['index']
        );
        $details = $this->details[$card->fullName] ?? [];
        $cost = $details['cost'] ?? null;
        if($cost == null)
            return $card;
        return new PlayerCard($card, $cost);
    }

}