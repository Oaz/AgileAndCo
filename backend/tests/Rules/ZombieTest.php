<?php
/*
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\Zombie;

class ZombieTest extends RulesTestCase
{
    /**
     * @dataProvider activities
     */
    public function testChooseActivity($usedActivities, $nextRandomNumber, $expectedActivityChoice): void
    {
        $this->arrange([9, 26]);
        $zombie = new Zombie($this->rules);
        set_bga_rand($nextRandomNumber);
        foreach ($usedActivities as $activity) {
            $this->rules->chooseActivity($activity);
        }
        $this->assertEquals(
            5 - count($usedActivities),
            count($zombie->getAvailableActivities())
        );
        $this->assertEquals(
            $expectedActivityChoice,
            $zombie->chooseActivity()
        );
    }

    public static function activities(): array
    {
        return [
            [[], 0, 'ACTIVITY_CONFERENCE'],
            [[], 1, 'ACTIVITY_DEVELOPMENT'],
            [[], 2, 'ACTIVITY_DEPLOYMENT'],
            [[], 3, 'ACTIVITY_RETROSPECTIVE'],
            [[], 4, 'ACTIVITY_COACH'],
            [[], 5, 'ACTIVITY_CONFERENCE'],
            [['ACTIVITY_CONFERENCE'], 0, 'ACTIVITY_DEVELOPMENT'],
            [['ACTIVITY_CONFERENCE'], 2, 'ACTIVITY_RETROSPECTIVE'],
            [['ACTIVITY_CONFERENCE', 'ACTIVITY_RETROSPECTIVE'], 2, 'ACTIVITY_COACH'],
            [['ACTIVITY_CONFERENCE', 'ACTIVITY_RETROSPECTIVE', 'ACTIVITY_COACH'], 2, 'ACTIVITY_DEVELOPMENT'],
        ];
    }

    /**
     * @dataProvider conferences
     */
    public function testDiscardConference(int $playerId, array $powers, array $expectedDiscard): void
    {
        $this->withMultiActivity('ACTIVITY_CONFERENCE', 26, [9, 26, 68, 144]);
        $this->addTo('company', $playerId, $powers);
        $this->rules->prepareConference();
        $zombie = new Zombie($this->rules);
        $this->assertEquals(
            $expectedDiscard,
            $zombie->discardConference($playerId)
        );
    }

    public static function conferences(): array
    {
        return [
            [9, [], [['zone' => 'conference', 'index' => 0, 'name' => 'PRODUCT_TEAM_SOCIAL']]],
            [26, [], [
                ['zone' => 'conference', 'index' => 0, 'name' => 'PRODUCT_TEAM_SOCIAL'],
                ['zone' => 'conference', 'index' => 1, 'name' => 'PRODUCT_TEAM_SOCIAL'],
                ['zone' => 'conference', 'index' => 2, 'name' => 'PRODUCT_TEAM_MMOG'],
                ['zone' => 'conference', 'index' => 3, 'name' => 'PRODUCT_TEAM_MMOG'],
            ]],
            [9, ['AGILE_MATURITY_AGILE_ORGANIZER'], []],
            [26, ['AGILE_MATURITY_AGILE_ORGANIZER'], [
                ['zone' => 'conference', 'index' => 0, 'name' => 'PRODUCT_TEAM_SOCIAL'],
                ['zone' => 'conference', 'index' => 1, 'name' => 'PRODUCT_TEAM_SOCIAL'],
                ['zone' => 'conference', 'index' => 2, 'name' => 'PRODUCT_TEAM_MMOG'],
            ]],
        ];
    }

    /**
     * @dataProvider potential
     */
    public function testDiscardPotential(int $playerId, int $potentialSize, array $powers, array $expectedDiscard): void
    {
        $this->withMultiActivity('END_OF_ROUND', 26, [9, 26, 68, 144]);
        $this->addTo('company', $playerId, $powers);
        $initialPotential = $this->rules->repo->listCardIds('potential', playerId: $playerId);
        $this->deck->pickCardsForLocation($potentialSize - count($initialPotential), 'deck', 'potential', $playerId);

        $zombie = new Zombie($this->rules);
        $this->assertEquals(
            $expectedDiscard,
            $zombie->discardPotential($playerId)
        );
    }

    public static function potential(): array
    {
        return [
            [68, 6, [], []],
            [68, 7, [], [
                ['zone' => 'potential', 'index' => 0, 'name' => 'PRODUCT_TEAM_EDUCATION']
            ]],
            [68, 8, [], [
                ['zone' => 'potential', 'index' => 0, 'name' => 'PRODUCT_TEAM_EDUCATION'],
                ['zone' => 'potential', 'index' => 1, 'name' => 'PRODUCT_TEAM_EDUCATION']
            ]],
            [68, 12, ['AGILE_MATURITY_AGILE_HR', 'AGILE_VALUE_COURAGE', 'AGILE_VALUE_SIMPLICITY'], []],
            [68, 13, ['AGILE_MATURITY_AGILE_HR', 'AGILE_VALUE_COURAGE', 'AGILE_VALUE_SIMPLICITY'], [
                ['zone' => 'potential', 'index' => 0, 'name' => 'PRODUCT_TEAM_EDUCATION']
            ]],
        ];
    }

}



