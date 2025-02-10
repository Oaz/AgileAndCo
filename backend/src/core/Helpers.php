<?php

namespace Bga\Games\AgileAndCo;

class Helpers
{
    public static function pairsToDictionary(array $pairs)
    {
        return array_reduce($pairs, fn($carry, $item) => [$item[0] => $item[1]] + $carry, []);
    }

    public static function orderedArrayValues(array $array)
    {
        $keys = array_keys($array);
        sort($keys);
        return array_map(function ($key) use ($array) {
            return $array[$key];
        }, $keys);
    }

    public static function getCardName($card) : string
    {
        return self::getFullName($card['type'], $card['type_arg']);
    }

    public static function getFullName($groupName, $index): string
    {
        return $groupName . '_' . CardsData::$groups[$groupName][$index];
    }

}
