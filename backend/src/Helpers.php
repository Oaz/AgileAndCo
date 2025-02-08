<?php

namespace Bga\Games\AgileAndCo;

class Helpers
{
    public static function getCardsInLocationSortedByIndexes(
        IDeckAdapter $deckAdapter, string $location, ?int $playerId = null
    ) : array
    {
        $cards = $deckAdapter->getCardsInLocation($location, playerId: $playerId);
        usort($cards, fn($a, $b) => $a['index'] - $b['index']);
        $result = [];
        foreach ($cards as $card) {
            while (count($result) < $card['index']) {
                $result[] = false;
            }
            $result[] = self::getCardName($card);
        }
        return $result;
    }

    public static function getCardName($card)
    {
        return CardsData::getFullName($card['type'], $card['type_arg']);
    }
}
