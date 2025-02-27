<?php

namespace Bga\Games\AgileAndCo\Tests;

class DeploymentRulesTest extends RulesTestCase
{
    public function testPrepareDeployment(): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_DEPLOYMENT', 26, [9,26,68,144]);
        set_bga_rand(2);

        // Act
        $this->rules->prepareDeployment();

        $currentEarnings = $this->game->globalVariable('CURRENT_EARNINGS')->read();
        $this->assertEquals(2, $currentEarnings);
    }

    /**
     * @dataProvider deploymentCompletion
     */
    public function testCompleteDeployment($currentPlayer, int $gain, $selection, $teams, $products, $powers): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_DEPLOYMENT', 26, [9, 26, 68, 144]);
        set_bga_rand(0);
        $this->addTo('teams', $currentPlayer, $teams);
        $this->addTo('products', $currentPlayer, $products);
        $this->addTo('company', $currentPlayer, $powers);
        $selected = new FakeSelection($this->rules, $currentPlayer, $selection);
        $potentialBefore = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);
        $productsBefore = $this->rules->repo->listCardIds('products', playerId: $currentPlayer);
        $expectedNewProducts = array_diff($productsBefore, $selected->getCardIds());

        // Act
        $result = $this->rules->completeDeployment($currentPlayer, $selected->incomingJson());

        // Assert
        $this->assertTrue($result);

        $actualNewPotential = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);
        $this->assertEquals(count($potentialBefore)+$gain, count($actualNewPotential));

        $actualNewProducts = $this->rules->repo->listCardIds('products', playerId: $currentPlayer);
        $this->assertEquivalent($expectedNewProducts, $actualNewProducts);
    }

    public static function deploymentCompletion(): array
    {
        return [
            [9, 0, [], [], [], []],
            [9, 2, [['products', 0]], [], [['AGILE_MATURITY_TEST_TEAM', 0]], []],
            [26, 5,
                [['products', 0], ['products', 1]],
                [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]], []
            ],
            [9, 5,
                [['products', 0], ['products', 1]],
                [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]],
                ['AGILE_MATURITY_CONTINUOUS_DELIVERY']
            ],
            [26, 8,
                [['products', 0], ['products', 1], ['products', 2]],
                [['PRODUCT_TEAM_MMOG', 1], ['PRODUCT_TEAM_MMOG', 2]],
                [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1], ['AGILE_MATURITY_TEST_TEAM', 2]],
                ['AGILE_MATURITY_CONTINUOUS_DELIVERY']
            ],

        ];
    }


    /**
     * @dataProvider deploymentNonCompletion
     */
    public function testCannotCompleteDeployment($currentPlayer, $selection, $teams, $products, $powers, $expectedMessage): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_DEPLOYMENT', 26, [9, 26, 68, 144]);
        set_bga_rand(0);
        $this->addTo('teams', $currentPlayer, $teams);
        $this->addTo('products', $currentPlayer, $products);
        $this->addTo('company', $currentPlayer, $powers);
        $selected = new FakeSelection($this->rules, $currentPlayer, $selection);
        $potentialBefore = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);
        $productsBefore = $this->rules->repo->listCardIds('products', playerId: $currentPlayer);

        // Act
        try {
            $this->rules->completeDeployment($currentPlayer, $selected->incomingJson());
            $this->fail("Expected exception BgaUserException was not thrown.");
        } catch (\BgaUserException $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());
        }

        // Assert
        $this->assertEquivalent($potentialBefore, $this->rules->repo->listCardIds('potential', playerId:$currentPlayer));
        $this->assertEquivalent($productsBefore, $this->rules->repo->listCardIds('products', playerId:$currentPlayer));
    }

    public static function deploymentNonCompletion(): array
    {
        return [
            [9,
                [['products', 0], ['products', 1]],
                [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]], [],
                "Cannot deploy more than 1 product(s)"
            ],
            [26,
                [['products', 0], ['products', 1], ['products', 2]],
                [['PRODUCT_TEAM_MMOG', 1], ['PRODUCT_TEAM_MMOG', 2]],
                [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1], ['AGILE_MATURITY_TEST_TEAM', 2]],
                [],
                "Cannot deploy more than 2 product(s)"
            ],
        ];
    }
}
