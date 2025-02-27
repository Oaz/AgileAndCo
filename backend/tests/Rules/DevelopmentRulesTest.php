<?php

namespace Bga\Games\AgileAndCo\Tests;

class DevelopmentRulesTest extends RulesTestCase
{

    /**
     * @dataProvider developmentCompletion
     */
    public function testCompleteDevelopment($currentPlayer, $teamSelection, $productSelection, $teams, $products, $powers, $bonus): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_DEVELOPMENT', 26, [9, 26, 68, 144]);
        $this->addTo('teams', $currentPlayer, $teams);
        $this->addTo('products', $currentPlayer, $products);
        $this->addTo('company', $currentPlayer, $powers);
        $selectedTeams = new FakeSelection($this->rules, $currentPlayer, $teamSelection);
        $selectedProducts = new FakeSelection($this->rules, $currentPlayer, $productSelection);
        $potentialBefore = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);
        $expectedNewPotential = array_merge( array_diff($potentialBefore, $selectedProducts->getCardIds()), $bonus);
        $productsBefore = $this->rules->repo->listCardIds('products', playerId: $currentPlayer);
        $expectedNewProducts = array_merge($productsBefore, $selectedProducts->getCardIds());

        // Act
        $result = $this->rules->completeDevelopment($currentPlayer, $selectedTeams->incomingJson(), $selectedProducts->incomingJson());

        // Assert
        $this->assertTrue($result);

        $actualNewPotential = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);
        $this->assertEquivalent($expectedNewPotential, $actualNewPotential);

        $actualNewProducts = $this->rules->repo->listCardIds('products', playerId: $currentPlayer);
        $this->assertEquivalent($expectedNewProducts, $actualNewProducts);
    }

    public static function developmentCompletion(): array
    {
        return [
            [9, [], [], [], [], [], []],
            [9, [['teams', 0]], [['potential', 0]], [], [], [], []],
            [9, [['teams', 1]], [['potential', 3]], [['PRODUCT_TEAM_MMOG',1]], [], [], []],
            [26, [['teams', 0],['teams', 1]], [['potential', 0],['potential', 3]], [['PRODUCT_TEAM_MMOG',1]], [], [], []],
            [26,
                [['teams', 0], ['teams', 2]], [['potential', 0], ['potential', 3]],
                [['PRODUCT_TEAM_MMOG', 1], ['PRODUCT_TEAM_MMOG', 2]], [], [], []
            ],
            [9,
                [['teams', 0],['teams', 1]], [['potential', 0],['potential', 3]],
                [['PRODUCT_TEAM_MMOG',1]], [], ['AGILE_MATURITY_CLEAN_CODE'], []
            ],
            [26,
                [['teams', 0], ['teams', 2]], [['potential', 0], ['potential', 3]],
                [['PRODUCT_TEAM_MMOG', 1], ['PRODUCT_TEAM_MMOG', 2]], [['AGILE_MATURITY_TEST_TEAM', 1]], [], []
            ],
            [9,
                [['teams', 0],['teams', 1]], [['potential', 0],['potential', 3]],
                [['PRODUCT_TEAM_MMOG',1]], [], ['AGILE_MATURITY_CLEAN_CODE', 'AGILE_MATURITY_PAIR_PROGRAMMING'], [21]
            ],
        ];
    }

    /**
     * @dataProvider developmentNonCompletion
     */
    public function testCannotCompleteDevelopment($currentPlayer, $teamSelection, $productSelection, $teams, $products, $powers, $expectedMessage): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_DEVELOPMENT', 26, [9, 26, 68, 144]);
        $this->addTo('teams', $currentPlayer, $teams);
        $this->addTo('products', $currentPlayer, $teams);
        $this->addTo('company', $currentPlayer, $powers);
        $selectedTeams = new FakeSelection($this->rules, $currentPlayer, $teamSelection);
        $selectedProducts = new FakeSelection($this->rules, $currentPlayer, $productSelection);
        $potentialBefore = $this->rules->repo->listCardIds('potential', playerId: $currentPlayer);
        $productsBefore = $this->rules->repo->listCardIds('products', playerId: $currentPlayer);

        // Act
        try {
            $this->rules->completeDevelopment($currentPlayer, $selectedTeams->incomingJson(), $selectedProducts->incomingJson());
            $this->fail("Expected exception BgaUserException was not thrown.");
        } catch (\BgaUserException $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());
        }

        // Assert
        $this->assertEquivalent($potentialBefore, $this->rules->repo->listCardIds('potential', playerId: $currentPlayer));
        $this->assertEquivalent($productsBefore, $this->rules->repo->listCardIds('products', playerId: $currentPlayer));
    }

    public static function developmentNonCompletion(): array
    {
        return [
            [9, [['teams', 0]], [], [], [], [], "Should have same number of selected teams and products"],
            [9, [['teams', 0],['teams', 1]], [['potential', 0],['potential', 3]], [['PRODUCT_TEAM_MMOG',1]], [], [], "Cannot develop more than 1 product(s)"],
            [26,
                [['teams', 0], ['teams', 1], ['teams', 2]], [['potential', 0], ['potential', 2], ['potential', 3]],
                [['PRODUCT_TEAM_MMOG', 1], ['PRODUCT_TEAM_MMOG', 2]], [], [], "Cannot develop more than 2 product(s)"
            ],
            [26,
                [['teams', 0], ['teams', 1]], [['potential', 0], ['potential', 3]],
                [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 1]], [], "Team must deploy before developing another product"
            ],
        ];
    }
}
