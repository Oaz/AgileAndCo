<?php

namespace Bga\Games\AgileAndCo\Tests;

class ActivityChoiceRulesTest extends RulesTestCase
{
    /**
     * @dataProvider noActivityProvider
     */
    public function testGetGameStateWithNoOngoingActivity(array $playerIds): void
    {
        // Arrange
        $this->arrange($playerIds);

        // Act
        $gameState = $this->rules->getGameState();

        // Assert
        $this->assertEquals(0, $gameState['public']['active_player']);
        $this->assertFalse($gameState['public']['central']['selection']);
        $this->assertCount(5, $gameState['public']['central']['activities']);
        $this->assertCount(count($playerIds), $gameState['public']['players']);
        $this->assertEquals('', $gameState['public']['players'][0]['activity']);
        $this->assertEquals('', $gameState['public']['players'][1]['activity']);
    }

    public static function noActivityProvider(): array
    {
        return [
            [[9,26]],
            [[9,26,68]],
            [[9,26,68,144]],
        ];
    }

    /**
     * @dataProvider activityProvider
     */
    public function testChooseActivity(string $activity, int $initPlayer, array $activated, string $expectedTransition, string $expectedPlayerActivity): void
    {
        // Arrange
        $this->arrange([9,26,68,144]);
        $this->game->setActivePlayers([$initPlayer]);

        // Act
        $transition = $this->rules->chooseActivity($activity);
        $this->game->setActivePlayers($activated);

        // Assert
        $this->assertEquals($expectedTransition, $transition);
        list($ongoingActivity, $initiator) = $this->game->globalVariable('ONGOING_ACTIVITY')->read();
        $this->assertEquals($activity, $ongoingActivity);
        $this->assertEquals($initPlayer, $initiator);

        $state = $this->rules->getGameState()['public'];
        $this->assertContains([$activity, false, true], $state['central']['activities']);

        $player = $state['players'][$initPlayer];
        $this->assertTrue($player['initiate']);
        $allPlayers = array_keys($this->game->getPlayers());
        foreach (array_diff($allPlayers, [$initPlayer]) as $playerId) {
            $player = $state['players'][$playerId];
            $this->assertFalse($player['initiate']);
        }
        foreach ($activated as $playerId) {
            $player = $state['players'][$playerId];
            $this->assertEquals($expectedPlayerActivity, $player['activity']);
        }
    }

    public static function activityProvider(): array
    {
        return [
            ['ACTIVITY_CONFERENCE', 9, [9,26,68,144], 'activityConference', 'ACTIVITY_CONFERENCE'],
            ['ACTIVITY_DEVELOPMENT', 68, [9,26,68,144], 'activityDevelopment', 'ACTIVITY_DEVELOPMENT'],
            ['ACTIVITY_DEPLOYMENT', 26, [9,26,68,144], 'activityDeployment', 'ACTIVITY_DEPLOYMENT'],
            ['ACTIVITY_RETROSPECTIVE', 144, [9,26,68,144], 'activityRetrospective', 'ACTIVITY_RETROSPECTIVE_CHOOSE'],
            ['ACTIVITY_COACH', 68, [68], 'activityCoach', 'ACTIVITY_COACH'],
        ];
    }

    public function testChooseInvalidActivityThrowsException(): void
    {
        $this->arrange([9,26,68,144]);
        $this->expectException(\BgaUserException::class);
        $this->rules->chooseActivity('INVALID_ACTIVITY');
    }

}
