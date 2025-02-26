<?php

namespace Bga\Games\AgileAndCo\Tests;

class GameInitializationRulesTest extends RulesTestCase
{
    /**
     * @dataProvider gameInitProvider
     */
    public function testInitGameWithNPlayers(array $playerIds): void
    {
        // Arrange
        $this->arrange($playerIds);

        // Assert
        $this->assertCount(98-5*count($playerIds), $this->deck->getCardsInLocation('deck'));
        $this->assertCount(5, $this->deck->getCardsInLocation('activities'));
        $this->assertCount(4, $this->deck->getCardsInLocation('earnings'));
        foreach ($playerIds as $playerId) {
            $actualTeams = $this->deck->getCardsInLocation('teams', playerId:$playerId);
            $this->assertCount(1, $actualTeams);
            $this->assertEquals('PRODUCT_TEAM', $actualTeams[0]['type']);
            $this->assertEquals(0, $actualTeams[0]['type_arg']);
            $this->assertCount(4, $this->deck->getCardsInLocation('potential', playerId:$playerId));
        }
    }

    public static function gameInitProvider(): array
    {
        return [
            [[9,26]],
            [[9,26,68]],
            [[9,26,68,144]],
        ];
    }
}
