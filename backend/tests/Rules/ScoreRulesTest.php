<?php

namespace Bga\Games\AgileAndCo\Tests;
use Bga\Games\AgileAndCo\Rules;
use Bga\Games\AgileAndCo\ScoreComputer;

class ScoreRulesTest extends RulesTestCase
{
    /**
     * @dataProvider scores
     */
    public function testScore($expectedScore, $expectedEndingScore, $teams, $company, $potential): void
    {
        $this->arrange([]);
        $computer = new ScoreComputer($this->rules->repo);
        $this->assertEquals(
            $expectedScore,
            $computer->computeScore($teams, $company, $potential, false)
        );
        $this->assertEquals(
            $expectedEndingScore,
            $computer->computeScore($teams, $company, $potential, true)
        );
    }

    public static function scores(): array
    {
        return [
            // Base case: No cards in any category
            [0, 0, [], [], []],

            // PRODUCT_TEAM scores
            [1, 1, ['PRODUCT_TEAM_ADVERGAME'], [], []],
            [1, 1, ['PRODUCT_TEAM_EDUCATION'], [], []],
            [2, 2, ['PRODUCT_TEAM_SOCIAL'], [], []],
            [2, 2, ['PRODUCT_TEAM_MMOG'], [], []],

            // AGILE_MATURITY scores
            [1, 1, [], ['AGILE_MATURITY_PASSIONATE_DEVELOPER'], []],
            [2, 2, [], ['AGILE_MATURITY_DEVOPS'], []],
            [3, 3, [], ['AGILE_MATURITY_PASSIONATE_DEVELOPER','AGILE_MATURITY_DEVOPS'], []],

            // AGILE_VALUE scores
            [1, 1, [], ['AGILE_VALUE_HUMOR'], []],
            [4, 4, [], ['AGILE_VALUE_HUMOR', 'AGILE_VALUE_FEEDBACK'], []],
            [9, 9, [], ['AGILE_VALUE_HUMOR', 'AGILE_VALUE_FEEDBACK', 'AGILE_VALUE_FOCUS'], []],
            [16, 16, [], ['AGILE_VALUE_HUMOR', 'AGILE_VALUE_FEEDBACK', 'AGILE_VALUE_FOCUS', 'AGILE_VALUE_COURAGE'], []],

            // AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP: Each team gets +2 points
            [1, 3, ['PRODUCT_TEAM_ADVERGAME'], ['AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP'], []],
            [3, 7, ['PRODUCT_TEAM_ADVERGAME', 'PRODUCT_TEAM_SOCIAL'], ['AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP'], []],

            // AGILE_MATURITY_AGILE_SENSEI : Each value/maturity gets +1 point
            [3, 6, [], ['AGILE_MATURITY_PASSIONATE_DEVELOPER', 'AGILE_MATURITY_DEVOPS', 'AGILE_MATURITY_AGILE_SENSEI'], []],

            // AGILE_MATURITY_PRODUCT_VISION: Increases final score by 30%
            [7, 7,
                ['PRODUCT_TEAM_MMOG', 'PRODUCT_TEAM_MMOG'],
                ['AGILE_MATURITY_AGILE_ORGANIZER', 'AGILE_MATURITY_USER_EXPERIENCE'], []
            ],
            [7, 9,
                ['PRODUCT_TEAM_MMOG', 'PRODUCT_TEAM_MMOG'],
                ['AGILE_MATURITY_AGILE_ORGANIZER', 'AGILE_MATURITY_PRODUCT_VISION', 'AGILE_MATURITY_USER_EXPERIENCE'], []
            ],
            [16, 16,
                ['PRODUCT_TEAM_SOCIAL', 'PRODUCT_TEAM_MMOG', 'PRODUCT_TEAM_MMOG'],
                ['AGILE_MATURITY_USER_EXPERIENCE', 'AGILE_VALUE_HUMOR', 'AGILE_VALUE_FEEDBACK', 'AGILE_VALUE_FOCUS'], []
            ],
            [16, 20,
                ['PRODUCT_TEAM_SOCIAL', 'PRODUCT_TEAM_MMOG', 'PRODUCT_TEAM_MMOG'],
                ['AGILE_MATURITY_PRODUCT_VISION', 'AGILE_MATURITY_USER_EXPERIENCE', 'AGILE_VALUE_HUMOR', 'AGILE_VALUE_FEEDBACK', 'AGILE_VALUE_FOCUS'], []
            ],
            [13, 17,
                ['PRODUCT_TEAM_SOCIAL', 'PRODUCT_TEAM_MMOG'],
                ['AGILE_MATURITY_AGILE_SENSEI', 'AGILE_VALUE_HUMOR', 'AGILE_VALUE_FEEDBACK', 'AGILE_VALUE_FOCUS'], []
            ],
            [13, 23,
                ['PRODUCT_TEAM_SOCIAL', 'PRODUCT_TEAM_MMOG'],
                ['AGILE_MATURITY_AGILE_SENSEI', 'AGILE_MATURITY_PRODUCT_VISION', 'AGILE_VALUE_HUMOR', 'AGILE_VALUE_FEEDBACK', 'AGILE_VALUE_FOCUS'], []
            ],

            // Negative scores
            [0, -4, [], [], ['AGILE_MATURITY_DETAILED_PLANNING']],
            [0, -9, [], [], ['AGILE_MATURITY_DETAILED_PLANNING', 'AGILE_MATURITY_APPLICATION_FRAMEWORK']],

            // Negative scores with sensei
            [0, -2, [], ['AGILE_MATURITY_AGILE_SENSEI'], ['AGILE_MATURITY_DETAILED_PLANNING']],
            [0, -6, [], ['AGILE_MATURITY_AGILE_SENSEI'], ['AGILE_MATURITY_DETAILED_PLANNING', 'AGILE_MATURITY_APPLICATION_FRAMEWORK']],
        ];
    }

}



