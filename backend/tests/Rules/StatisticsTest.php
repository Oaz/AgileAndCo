<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\Statistics;

class StatisticsTest extends RulesTestCase
{
    public function testActivity(): void
    {
        $scenario = [
            [9, 'ACTIVITY_CONFERENCE', [1, 0, 0, 0, 0], [1, 0, 0, 0, 0], [0, 0, 0, 0, 0], [0, 0, 0, 0, 0]],
            [26, 'ACTIVITY_DEVELOPMENT', [1, 1, 0, 0, 0], [1, 0, 0, 0, 0], [0, 1, 0, 0, 0], [0, 0, 0, 0, 0]],
            [68, 'ACTIVITY_DEPLOYMENT', [1, 1, 1, 0, 0], [1, 0, 0, 0, 0], [0, 1, 0, 0, 0], [0, 0, 1, 0, 0]],
            [9, 'ACTIVITY_RETROSPECTIVE', [1, 1, 1, 1, 0], [1, 0, 0, 1, 0], [0, 1, 0, 0, 0], [0, 0, 1, 0, 0]],
            [26, 'ACTIVITY_COACH', [1, 1, 1, 1, 1], [1, 0, 0, 1, 0], [0, 1, 0, 0, 1], [0, 0, 1, 0, 0]],
            [68, 'ACTIVITY_DEVELOPMENT', [1, 2, 1, 1, 1], [1, 0, 0, 1, 0], [0, 1, 0, 0, 1], [0, 1, 1, 0, 0]],
            [9, 'ACTIVITY_CONFERENCE', [2, 2, 1, 1, 1], [2, 0, 0, 1, 0], [0, 1, 0, 0, 1], [0, 1, 1, 0, 0]],
            [26, 'ACTIVITY_DEPLOYMENT', [2, 2, 2, 1, 1], [2, 0, 0, 1, 0], [0, 1, 1, 0, 1], [0, 1, 1, 0, 0]],
            [68, 'ACTIVITY_DEVELOPMENT', [2, 3, 2, 1, 1], [2, 0, 0, 1, 0], [0, 1, 1, 0, 1], [0, 2, 1, 0, 0]],
        ];
        $this->arrange([9, 26, 68]);
        foreach ($scenario as $step) {
            $this->game->setActivePlayers([$step[0]]);
            $this->rules->chooseActivity($step[1]);
            $stats = $this->game->stats;
            $this->assertEquals($step[2], $this->getActivityStats($stats['table']));
            $this->assertEquals($step[3], $this->getActivityStats($stats['player'][9]));
            $this->assertEquals($step[4], $this->getActivityStats($stats['player'][26]));
            $this->assertEquals($step[5], $this->getActivityStats($stats['player'][68]));
        }
    }

    private function getActivityStats(array $data): array
    {
        return [
            $data['activity_count_conference'],
            $data['activity_count_development'],
            $data['activity_count_deployment'],
            $data['activity_count_retrospective'],
            $data['activity_count_coach'],
        ];
    }

}



