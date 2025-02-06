<?php

namespace Bga\Games\AgileAndCo;

class CardsData
{
    public static mixed $groups = [
        'PRODUCT_TEAM' => [
            'ADVERGAME', 'EDUCATION', 'SOCIAL', 'MMOG',
        ],
        'AGILE_MATURITY' => [
            'PASSIONATE_DEVELOPER', 'AGILE_PRACTITIONER', 'USER_EXPERIENCE', 'PAIR_PROGRAMMING',
            'AGILE_ORGANIZER', 'FEEDBACK_SESSIONS', 'CLEAN_CODE', 'CONTINUOUS_DELIVERY', 'DEVOPS',
            'AGILE_HR', 'ENGAGED_USERS', 'INTERNAL_COACH',
            'DETAILED_PLANNING', 'TEST_TEAM', 'APPLICATION_FRAMEWORK', 'AGILE_CERTIFICATION',
            'SOFTWARE_CRAFTSMANSHIP', 'AGILE_SENSEI', 'PRODUCT_VISION'
        ],
        'AGILE_VALUE' => [
            'HUMOR', 'FEEDBACK', 'SIMPLICITY', 'TRUST', 'TRANSPARENCY', 'COURAGE', 'RESPECT',
        ],
        'ACTIVITY' => [
            'CONFERENCE', 'DEVELOPMENT', 'DEPLOYMENT', 'RETROSPECTIVE', 'COACH',
        ],
        'EARNINGS' => [
            'CARD_1', 'CARD_2', 'CARD_3', 'CARD_4',
        ],
    ];

    public static function getFullName($groupName,$index): string
    {
       return $groupName . '_' . CardsData::$groups[$groupName][$index];
    }

    public static function getAll($groupName): array
    {
        return array_map(function ($a) use($groupName) {
            return $groupName . '_' . $a;
        }, CardsData::$groups[$groupName]);
    }

    public static function getActivities(): array
    {
        return CardsData::getAll('ACTIVITY');
    }

    public static function getActivityIndex($activityName): int
    {
        return array_search($activityName, CardsData::getActivities());
    }

    public static mixed $details = [
        'PRODUCT_TEAM_ADVERGAME' => ['cost' => 1, 'score' => 1,],
        'PRODUCT_TEAM_EDUCATION' => ['cost' => 2, 'score' => 1,],
        'PRODUCT_TEAM_SOCIAL' => ['cost' => 3, 'score' => 2,],
        'PRODUCT_TEAM_MMOG' => ['cost' => 4, 'score' => 2,],
        'AGILE_MATURITY_PASSIONATE_DEVELOPER' => ['cost' => 1, 'score' => 1,],
        'AGILE_MATURITY_AGILE_PRACTITIONER' => ['cost' => 1, 'score' => 1,],
        'AGILE_MATURITY_USER_EXPERIENCE' => ['cost' => 2, 'score' => 1,],
        'AGILE_MATURITY_PAIR_PROGRAMMING' => ['cost' => 2, 'score' => 1,],
        'AGILE_MATURITY_AGILE_ORGANIZER' => ['cost' => 3, 'score' => 2,],
        'AGILE_MATURITY_FEEDBACK_SESSIONS' => ['cost' => 3, 'score' => 2,],
        'AGILE_MATURITY_CLEAN_CODE' => ['cost' => 3, 'score' => 2,],
        'AGILE_MATURITY_CONTINUOUS_DELIVERY' => ['cost' => 3, 'score' => 2,],
        'AGILE_MATURITY_DEVOPS' => ['cost' => 3, 'score' => 2,],
        'AGILE_MATURITY_AGILE_HR' => ['cost' => 4, 'score' => 2,],
        'AGILE_MATURITY_ENGAGED_USERS' => ['cost' => 4, 'score' => 2,],
        'AGILE_MATURITY_INTERNAL_COACH' => ['cost' => 4, 'score' => 2,],
        'AGILE_MATURITY_DETAILED_PLANNING' => ['cost' => 1, 'score' => -4,],
        'AGILE_MATURITY_TEST_TEAM' => ['cost' => 1, 'score' => -4,],
        'AGILE_MATURITY_APPLICATION_FRAMEWORK' => ['cost' => 1, 'score' => -5,],
        'AGILE_MATURITY_AGILE_CERTIFICATION' => ['cost' => 1, 'score' => -5,],
        'AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP' => ['cost' => 5,],
        'AGILE_MATURITY_AGILE_SENSEI' => ['cost' => 5,],
        'AGILE_MATURITY_PRODUCT_VISION' => ['cost' => 5,],
        'AGILE_VALUE_HUMOR' => ['cost' => 4,],
        'AGILE_VALUE_FEEDBACK' => ['cost' => 4,],
        'AGILE_VALUE_SIMPLICITY' => ['cost' => 4,],
        'AGILE_VALUE_TRUST' => ['cost' => 4,],
        'AGILE_VALUE_TRANSPARENCY' => ['cost' => 4,],
        'AGILE_VALUE_COURAGE' => ['cost' => 4,],
        'AGILE_VALUE_RESPECT' => ['cost' => 4,],
    ];
    public static mixed $instances = [
        ['type' => 'PRODUCT_TEAM', 'type_arg' => 0, 'nbr' => 10],
        ['type' => 'PRODUCT_TEAM', 'type_arg' => 1, 'nbr' => 7],
        ['type' => 'PRODUCT_TEAM', 'type_arg' => 2, 'nbr' => 7],
        ['type' => 'PRODUCT_TEAM', 'type_arg' => 3, 'nbr' => 7],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 0, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 1, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 2, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 3, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 4, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 5, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 6, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 7, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 8, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 9, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 10, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 11, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 12, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 13, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 14, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 15, 'nbr' => 3],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 16, 'nbr' => 2],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 17, 'nbr' => 2],
        ['type' => 'AGILE_MATURITY', 'type_arg' => 18, 'nbr' => 2],
        ['type' => 'AGILE_VALUE', 'type_arg' => 0, 'nbr' => 1],
        ['type' => 'AGILE_VALUE', 'type_arg' => 1, 'nbr' => 2],
        ['type' => 'AGILE_VALUE', 'type_arg' => 2, 'nbr' => 2],
        ['type' => 'AGILE_VALUE', 'type_arg' => 3, 'nbr' => 2],
        ['type' => 'AGILE_VALUE', 'type_arg' => 4, 'nbr' => 2],
        ['type' => 'AGILE_VALUE', 'type_arg' => 5, 'nbr' => 2],
        ['type' => 'AGILE_VALUE', 'type_arg' => 6, 'nbr' => 2],
        ['type' => 'ACTIVITY', 'type_arg' => 0, 'nbr' => 1],
        ['type' => 'ACTIVITY', 'type_arg' => 1, 'nbr' => 1],
        ['type' => 'ACTIVITY', 'type_arg' => 2, 'nbr' => 1],
        ['type' => 'ACTIVITY', 'type_arg' => 3, 'nbr' => 1],
        ['type' => 'ACTIVITY', 'type_arg' => 4, 'nbr' => 1],
        ['type' => 'EARNINGS', 'type_arg' => 0, 'nbr' => 1],
        ['type' => 'EARNINGS', 'type_arg' => 1, 'nbr' => 1],
        ['type' => 'EARNINGS', 'type_arg' => 2, 'nbr' => 1],
        ['type' => 'EARNINGS', 'type_arg' => 3, 'nbr' => 1],
    ];
}
