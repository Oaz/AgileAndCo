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

    public function testDevelopmentDeployment(): void
    {
        $scenario = [
            [ 9 => [[0], 1, 2], 26 => [[0], 1, 2], 68 => [[0], 1, 2], 'all' => [3,6] ],
            [ 9 => [[0], 2, 4], 26 => [[0], 2, 4], 68 => [[0], 2, 4], 'all' => [6,12] ],
            [ 9 => [[0,1], 4, 9], 26 => [[], 2, 4], 68 => [[], 2, 4], 'all' => [8,17] ],
        ];
        $playerIds = [9, 26, 68];
        $this->arrange($playerIds);
        $this->addTo('teams', 9, [['PRODUCT_TEAM_MMOG',1]]);
        foreach ($scenario as $step) {
            $this->startActivity('ACTIVITY_DEVELOPMENT', 9, $playerIds);
            foreach ($playerIds as $playerId) {
                $indexes = $step[$playerId][0];
                $expectedProducts = $step[$playerId][1];
                $expectedEarnings = $step[$playerId][2];
                $this->rules->completeDevelopment(
                    $playerId,
                    $this->selection($playerId, 'teams', $indexes),
                    $this->selection($playerId, 'potential', $indexes),
                );
                $this->checkStats($playerId, 'product_development_count', $expectedProducts);
                $this->rules->completeDeployment(
                    $playerId,
                    $this->selection($playerId, 'products', $indexes),
                );
                $this->checkStats($playerId, 'product_deployment_count', $expectedProducts);
                $this->checkStats($playerId, 'total_earnings', $expectedEarnings);
            }
            $stats = $this->game->stats;
            $this->assertEquals($step['all'][0], $stats['table']['product_development_count']);
            $this->assertEquals($step['all'][0], $stats['table']['product_deployment_count']);
            $this->assertEquals($step['all'][1], $stats['table']['total_earnings']);
        }
    }
    
    private function selection(int $playerId, string $zone, array $indexes): array
    {
        $fake = new FakeSelection($this->rules, $playerId, array_map(fn($index) => [$zone, $index], $indexes));
        return $fake->incomingJson();
    }

    public function checkStats(int $playerId, string $id, int $expected): void
    {
        $stats = $this->game->stats;
        $this->assertEquals($expected, $stats['player'][$playerId][$id]);
    }

}



