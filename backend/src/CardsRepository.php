<?php

namespace Bga\Games\AgileAndCo;

class CardsRepository
{
    public function __construct(array $groups, IDeckAdapter $deckAdapter)
    {
        $this->groups = $groups;
        $this->deck = $deckAdapter;
    }

    private array $groups;
    public readonly IDeckAdapter $deck;

    public function getAll($groupName): array
    {
        return array_map(function ($a) use ($groupName) {
            return $groupName . '_' . $a;
        }, $this->groups[$groupName]);
    }

    public function getActivities(): array
    {
        return $this->getAll('ACTIVITY');
    }

    public function getActivityIndex($activityName): int
    {
        return array_search($activityName, $this->getActivities());
    }

    public function getCardsInLocationSortedByIndexes(string $location, ?int $playerId = null): array
    {
        $cards = $this->deck->getCardsInLocation($location, playerId: $playerId);
        usort($cards, fn($a, $b) => $a['index'] - $b['index']);
        $result = [];
        foreach ($cards as $card) {
            while (count($result) < $card['index']) {
                $result[] = false;
            }
            $result[] = $this->getCardName($card);
        }
        return $result;
    }

    public function listCards($location, ?int $index = null, ?int $playerId = null)
    {
        return array_map(function ($card) {
            return $this->getCardName($card);
        }, $this->deck->getCardsInLocation($location, $index, $playerId));
    }

    public function getSingleCard($location, ?int $index = null, ?int $playerId = null) {
        return array_values($this->deck->getCardsInLocation($location, $index, $playerId))[0];
    }

    public function getCardName($card): string
    {
        return $card['type'] . '_' . $this->getCardSubName($card);
    }

    public function getCardSubName($card): string
    {
        return $this->groups[$card['type']][$card['type_arg']];
    }

}