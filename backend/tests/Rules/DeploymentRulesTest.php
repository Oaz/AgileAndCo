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
    public function testCompleteDeployment($currentPlayer, int $gain, $selection, $teams, $products, $powers, $expectedMessage): void
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

        $this->assertEquals($expectedMessage, $this->game->lastMessage);
    }

    public static function deploymentCompletion(): array
    {
        return [
            [9, 0, [], [], [], [], ""],
            [9,
                2, [['products', 0]], [], [['AGILE_MATURITY_TEST_TEAM', 0]], [],
                "ACTIVITY_DEPLOYMENT_IMPACT playerA 1 2"
            ],
            [26, 5,
                [['products', 0], ['products', 1]],
                [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]], [],
                "ACTIVITY_DEPLOYMENT_IMPACT playerB 2 5"
            ],
            [9, 5,
                [['products', 0], ['products', 1]],
                [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]],
                ['AGILE_MATURITY_CONTINUOUS_DELIVERY'],
                "ACTIVITY_DEPLOYMENT_IMPACT playerA 2 5"
            ],
            [26, 8,
                [['products', 0], ['products', 1], ['products', 2]],
                [['PRODUCT_TEAM_MMOG', 1], ['PRODUCT_TEAM_MMOG', 2]],
                [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1], ['AGILE_MATURITY_TEST_TEAM', 2]],
                ['AGILE_MATURITY_CONTINUOUS_DELIVERY'],
                "ACTIVITY_DEPLOYMENT_IMPACT playerB 3 8"
            ],
            [9,
                3, [['products', 0]], [], [['AGILE_MATURITY_TEST_TEAM', 0]], ['AGILE_MATURITY_ENGAGED_USERS'],
                "ACTIVITY_DEPLOYMENT_IMPACT playerA 1 3"
            ],
            [9, 6,
                [['products', 0], ['products', 1]],
                [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]],
                ['AGILE_MATURITY_CONTINUOUS_DELIVERY','AGILE_MATURITY_USER_EXPERIENCE'],
                "ACTIVITY_DEPLOYMENT_IMPACT playerA 2 7"
            ],
            [9, 7,
                [['products', 0], ['products', 1]],
                [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]],
                ['AGILE_MATURITY_CONTINUOUS_DELIVERY','AGILE_MATURITY_USER_EXPERIENCE','AGILE_MATURITY_ENGAGED_USERS'],
                "ACTIVITY_DEPLOYMENT_IMPACT playerA 2 8"
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
