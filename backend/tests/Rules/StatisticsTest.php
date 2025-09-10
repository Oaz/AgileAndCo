<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\Statistics;
use Bga\Games\AgileAndCo\Rules;

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
            [
                9 => [[0], 1, 2, 2+Rules::INITIAL_PLAYER_POTENTIAL],
                26 => [[0], 1, 2, 3+Rules::INITIAL_PLAYER_POTENTIAL],
                68 => [[0], 1, 2, 2+Rules::INITIAL_PLAYER_POTENTIAL],
                'all' => [3,6,7+3*Rules::INITIAL_PLAYER_POTENTIAL] ],
            [
                9 => [[0], 2, 4, 4+Rules::INITIAL_PLAYER_POTENTIAL],
                26 => [[0], 2, 4, 6+Rules::INITIAL_PLAYER_POTENTIAL],
                68 => [[0], 2, 4, 4+Rules::INITIAL_PLAYER_POTENTIAL],
                'all' => [6,12,14+3*Rules::INITIAL_PLAYER_POTENTIAL] ],
            [
                9 => [[0,1], 4, 9, 10+Rules::INITIAL_PLAYER_POTENTIAL],
                26 => [[], 2, 4, 6+Rules::INITIAL_PLAYER_POTENTIAL],
                68 => [[], 2, 4, 4+Rules::INITIAL_PLAYER_POTENTIAL],
                'all' => [8,17,20+3*Rules::INITIAL_PLAYER_POTENTIAL]
            ],
        ];
        $playerIds = [9, 26, 68];
        $this->arrange($playerIds);
        $this->addTo('teams', 9, [['PRODUCT_TEAM_MMOG',1]]);
        $this->addTo('company', 26, ['AGILE_MATURITY_ENGAGED_USERS']);
        $this->addTo('company', 9, ['AGILE_MATURITY_PAIR_PROGRAMMING']);
        foreach ($scenario as $step) {
            foreach ($playerIds as $playerId) {
                $indexes = $step[$playerId][0];
                $expectedProducts = $step[$playerId][1];
                $expectedEarnings = $step[$playerId][2];
                $expectedPotentialStream = $step[$playerId][3];
                $this->startActivity('ACTIVITY_DEVELOPMENT', 9, $playerIds);
                $this->rules->completeDevelopment(
                    $playerId,
                    $this->selection($playerId, 'teams', $indexes),
                    $this->selection($playerId, 'potential', $indexes),
                );
                $this->checkStats($playerId, 'product_development_count', $expectedProducts);
                $this->startActivity('ACTIVITY_DEPLOYMENT', 9, $playerIds);
                $this->rules->completeDeployment(
                    $playerId,
                    $this->selection($playerId, 'products', $indexes),
                );
                $this->checkStats($playerId, 'product_deployment_count', $expectedProducts);
                $this->checkStats($playerId, 'total_earnings', $expectedEarnings);
                $this->checkStats($playerId, 'potential_stream', $expectedPotentialStream);
            }
            $stats = $this->game->stats;
            $this->assertEquals($step['all'][0], $stats['table']['product_development_count']);
            $this->assertEquals($step['all'][0], $stats['table']['product_deployment_count']);
            $this->assertEquals($step['all'][1], $stats['table']['total_earnings']);
            $this->assertEquals($step['all'][2], $stats['table']['potential_stream']);
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


    public function testConference(): void
    {
        $playerIds = [9, 26, 68];
        $this->withMultiActivity('ACTIVITY_CONFERENCE', 26, $playerIds);
        $this->addTo('company', 26, ['AGILE_MATURITY_AGILE_ORGANIZER']);
        $this->rules->prepareConference();
        foreach ([9,68] as $playerId) {
            $selection = new FakeSelection($this->rules, $playerId, [['conference', 0]]);
            $this->rules->completeConference($playerId, $selection->incomingJson());
            $this->checkStats($playerId, 'potential_stream', 1+Rules::INITIAL_PLAYER_POTENTIAL);
        }
        $selection = new FakeSelection($this->rules, 26, [['conference', 0],['conference', 1],['conference', 2]]);
        $this->rules->completeConference(26, $selection->incomingJson());
        $this->checkStats(26, 'potential_stream', 2+Rules::INITIAL_PLAYER_POTENTIAL);
        $stats = $this->game->stats;
        $this->assertEquals(4+3*Rules::INITIAL_PLAYER_POTENTIAL, $stats['table']['potential_stream']);
    }

    public function testCoach(): void
    {
        $scenario = [
            [9, 1+Rules::INITIAL_PLAYER_POTENTIAL, 1+3*Rules::INITIAL_PLAYER_POTENTIAL],
            [9, 2+Rules::INITIAL_PLAYER_POTENTIAL, 2+3*Rules::INITIAL_PLAYER_POTENTIAL],
            [26, 1+Rules::INITIAL_PLAYER_POTENTIAL, 3+3*Rules::INITIAL_PLAYER_POTENTIAL],
        ];
        $playerIds = [9, 26, 68];
        $this->arrange($playerIds);
        foreach ($scenario as $step) {
            $playerId = $step[0];
            $expectedPotentialStream = $step[1];
            $expectedTablePotentialStream = $step[2];
            $this->startActivity('ACTIVITY_COACH', $playerId, [$playerId]);
            $this->rules->doCoach();
            $this->checkStats($playerId, 'potential_stream', $expectedPotentialStream);
            $stats = $this->game->stats;
            $this->assertEquals($expectedTablePotentialStream, $stats['table']['potential_stream']);
        }
    }


    public function testRetrospective(): void
    {
        $playerIds = [9, 26, 68];
        $this->arrange($playerIds);
        $scenario = [
            [9, 'AGILE_MATURITY_CLEAN_CODE', 1+Rules::INITIAL_PLAYER_POTENTIAL, 1+3*Rules::INITIAL_PLAYER_POTENTIAL],
            [68, 'AGILE_MATURITY_CLEAN_CODE', 1+Rules::INITIAL_PLAYER_POTENTIAL, 2+3*Rules::INITIAL_PLAYER_POTENTIAL],
            [9, 'PRODUCT_TEAM_SOCIAL', 1+Rules::INITIAL_PLAYER_POTENTIAL, 2+3*Rules::INITIAL_PLAYER_POTENTIAL],
            [68, 'AGILE_MATURITY_CLEAN_CODE', 2+Rules::INITIAL_PLAYER_POTENTIAL, 3+3*Rules::INITIAL_PLAYER_POTENTIAL],
        ];
        $this->addTo('company', 9, ['AGILE_MATURITY_FEEDBACK_SESSIONS']);
        $this->addTo('company', 68, ['AGILE_MATURITY_FEEDBACK_SESSIONS']);
        $this->addTo('potential', 9, ['PRODUCT_TEAM_MMOG','PRODUCT_TEAM_MMOG','PRODUCT_TEAM_MMOG']);
        $this->addTo('potential', 68, ['PRODUCT_TEAM_MMOG','PRODUCT_TEAM_MMOG','PRODUCT_TEAM_MMOG']);

        foreach ($scenario as $step) {
            $playerId = $step[0];
            $retrospective = [$step[1]];
            $expectedPotentialStream = $step[2];
            $expectedTablePotentialStream = $step[3];
            $selected = new FakeSelection($this->rules, $playerId, [['potential', 0],['potential', 1],['potential', 2]]);
            $this->clear('retrospective', $playerId);
            $this->addTo('retrospective', $playerId, $retrospective);
            $this->startActivity('ACTIVITY_RETROSPECTIVE_PAYMENT', 26, $playerIds);
            $this->rules->payForRetrospective($playerId, $selected->incomingJson());
            $stats = $this->game->stats;
            $this->assertEquals($expectedPotentialStream, $stats['player'][$playerId]['potential_stream']);
            $this->assertEquals($expectedTablePotentialStream, $stats['table']['potential_stream']);
        }
    }


    public function testAdjustPotential(): void
    {
        $playerIds = [9, 26, 68];

        $this->arrange($playerIds);
        $scenario = [
            [9, 10, [], 0, 0],
            [26, 7, [], 0, 0],
            [68, 6, [], 0, 0],
            [68, 7, [['potential', 0]], 1, 1],
            [26, 8, [['potential', 0]], 1, 2],
            [9, 11, [['potential', 0]], 1, 3],
            [9, 13, [['potential', 0],['potential', 1],['potential', 2]], 4, 6],
            [26, 10, [['potential', 0],['potential', 1],['potential', 2]], 4, 9],
            [68, 9, [['potential', 0],['potential', 1],['potential', 2]], 4, 12],
        ];
        $this->addTo('company', 9, ['AGILE_MATURITY_AGILE_HR']);
        $this->addTo('company', 26, ['AGILE_VALUE_HUMOR']);

        foreach ($scenario as $step) {
            $playerId = $step[0];
            $currentPotential = $step[1];
            $playerSelection = $step[2];
            $expectedLoss = $step[3];
            $expectedTableLoss = $step[4];
            $remainingPotential = count($this->rules->repo->listCardIds('potential', playerId: $playerId));
            $this->deck->pickCardsForLocation($currentPotential - $remainingPotential, 'deck', 'potential', $playerId);
            $selection = new FakeSelection($this->rules, $playerId, $playerSelection);
            $this->rules->adjustPotential($playerId, $selection->incomingJson());
            $stats = $this->game->stats;
            $this->assertEquals($expectedLoss, $stats['player'][$playerId]['potential_loss']);
            $this->assertEquals($expectedTableLoss, $stats['table']['potential_loss']);
        }
    }

}



