<?php

namespace Bga\Games\AgileAndCo\Tests;


class RetrospectiveRulesTest extends RulesTestCase
{

    /**
     * @dataProvider cardCost
     */
    public function testCardCost($cardFullName, $expectedCost, $playerId, $powers): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_RETROSPECTIVE', 26, [9, 26, 68, 144]);
        $this->addTo('company', $playerId, $powers);
        $card = $this->rules->repo->createCardTemplate($cardFullName);

        // Act
        $player = $this->rules->getPlayerPrivateState($playerId);
        $actualCost = $this->rules->computeCost($card, $player);

        // Assert
        $this->assertEquals($expectedCost, $actualCost);
    }

    public static function cardCost(): array
    {
        return [
            ['PRODUCT_TEAM_ADVERGAME', 1, 9, []],
            ['PRODUCT_TEAM_MMOG', 4, 9, []],
            ['AGILE_MATURITY_CLEAN_CODE', 3, 9, []],
            ['AGILE_VALUE_COURAGE', 4, 9, []],
            ['PRODUCT_TEAM_ADVERGAME', 0, 26, []],
            ['PRODUCT_TEAM_MMOG', 3, 26, []],
            ['AGILE_MATURITY_CLEAN_CODE', 2, 26, []],
            ['AGILE_VALUE_COURAGE', 3, 26, []],
            ['PRODUCT_TEAM_ADVERGAME', 1, 9, ['AGILE_MATURITY_INTERNAL_COACH']],
            ['PRODUCT_TEAM_MMOG', 4, 9, ['AGILE_MATURITY_INTERNAL_COACH']],
            ['AGILE_MATURITY_CLEAN_CODE', 2, 9, ['AGILE_MATURITY_INTERNAL_COACH']],
            ['AGILE_VALUE_COURAGE', 3, 9, ['AGILE_MATURITY_INTERNAL_COACH']],
            ['PRODUCT_TEAM_ADVERGAME', 0, 9, ['AGILE_MATURITY_PASSIONATE_DEVELOPER']],
            ['PRODUCT_TEAM_MMOG', 3, 9, ['AGILE_MATURITY_PASSIONATE_DEVELOPER']],
            ['AGILE_MATURITY_CLEAN_CODE', 3, 9, ['AGILE_MATURITY_PASSIONATE_DEVELOPER']],
            ['AGILE_VALUE_COURAGE', 4, 9, ['AGILE_MATURITY_PASSIONATE_DEVELOPER']],
            ['AGILE_MATURITY_CLEAN_CODE', 1, 26, ['AGILE_MATURITY_INTERNAL_COACH']],
        ];
    }

    /**
     * @dataProvider playerFunds
     */
    public function testPlayerFunds($expectedFunds, $playerId, $teams, $products, $potential, $powers): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_RETROSPECTIVE', 26, [9, 26, 68, 144]);
        $this->addTo('teams', $playerId, $teams);
        $this->addTo('products', $playerId, $products);
        $this->addTo('potential', $playerId, $potential);
        $this->addTo('company', $playerId, $powers);

        // Act
        $player = $this->rules->getPlayerPrivateState($playerId);
        $actualFunds = $this->rules->computeFunds($player);

        // Assert
        $this->assertEquals($expectedFunds, $actualFunds);
    }

    public static function playerFunds(): array
    {
        return [
            [3, 9, [], [], [], []],
            [4, 9, [], [], ['AGILE_MATURITY_AGILE_ORGANIZER'], []],
            [3, 26, [], [], [], []],
            [4, 26, [], [], ['AGILE_MATURITY_AGILE_ORGANIZER'], []],
            [5, 9, [], [['AGILE_MATURITY_TEST_TEAM', 0]], [], ['AGILE_MATURITY_DEVOPS']],
            [7, 9, [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]], [], ['AGILE_MATURITY_DEVOPS']],
            [7, 9, [['PRODUCT_TEAM_MMOG', 1], ['PRODUCT_TEAM_MMOG', 2]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 2]], [], ['AGILE_MATURITY_DEVOPS']],
            [8, 9, [['PRODUCT_TEAM_MMOG', 1]], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]], ['AGILE_MATURITY_AGILE_ORGANIZER'], ['AGILE_MATURITY_DEVOPS']],
        ];
    }

    /**
     * @dataProvider retrospectiveChoice
     */
    public function testChooseForRetrospective($playerId, $potential, $products, $powers): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_RETROSPECTIVE', 26, [9, 26, 68, 144]);
        $this->addTo('products', $playerId, $products);
        $this->addTo('company', $playerId, $powers);
        $this->clear('potential', $playerId);
        $this->addTo('potential', $playerId, $potential);
        $selected = new FakeSelection($this->rules, $playerId, [['potential', count($potential)-1]]);
        $expectedChoice = $selected->getCardIds();
        $expectedNewPotential = array_diff($this->rules->repo->listCardIds('potential', playerId: $playerId),$expectedChoice);

        // Act
        $result = $this->rules->chooseForRetrospective($playerId, $selected->incomingJson()[0]);

        // Assert
        $this->assertTrue($result);

        $actualNewPotential = $this->rules->repo->listCardIds('potential', playerId: $playerId);
        $this->assertEquivalent($expectedNewPotential, $actualNewPotential);

        $actualChoice = $this->rules->repo->listCardIds('retrospective', playerId: $playerId);
        $this->assertEquals($expectedChoice, $actualChoice);
    }

    public static function retrospectiveChoice(): array
    {
        return [
            [9,
                [['AGILE_MATURITY_TEST_TEAM', 0],['PRODUCT_TEAM_MMOG', 1],['AGILE_MATURITY_USER_EXPERIENCE', 2]],
                [], []
            ],
            [9,
                [['AGILE_MATURITY_TEST_TEAM', 0],['AGILE_MATURITY_USER_EXPERIENCE', 1]],
                [], ['AGILE_MATURITY_INTERNAL_COACH']
            ],
            [9,
                [['AGILE_MATURITY_USER_EXPERIENCE', 0]],
                [['AGILE_MATURITY_TEST_TEAM', 0]], ['AGILE_MATURITY_DEVOPS']
            ],
        ];
    }

    public function testDoNotWantToRetrospective(): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_RETROSPECTIVE', 26, [9, 26, 68, 144]);

        // Act
        $result = $this->rules->chooseForRetrospective(9, []);

        // Assert
        $this->assertFalse($result);

        $actualChoice = $this->rules->repo->listCardIds('retrospective', playerId: 9);
        $this->assertEquals([], $actualChoice);
    }


    /**
     * @dataProvider retrospectiveNonChoice
     */
    public function testCannotChooseForRetrospective($playerId, $potential, $products, $powers, $expectedMessage): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_RETROSPECTIVE', 26, [9, 26, 68, 144]);
        $p = $this->rules->repo->listCardIds('potential', playerId: $playerId);
        $this->deck->playCard($p[3]);
        $this->addTo('products', $playerId, $products);
        $this->addTo('company', $playerId, $powers);
        $this->clear('potential', $playerId);
        $this->addTo('potential', $playerId, $potential);
        $expectedNewPotential = $this->rules->repo->listCardIds('potential', playerId: $playerId);
        $selected = new FakeSelection($this->rules, $playerId, [['potential', count($potential)-1]]);

        // Act
        try {
            $this->rules->chooseForRetrospective($playerId, $selected->incomingJson()[0]);
            $this->fail("Expected exception BgaUserException was not thrown.");
        } catch (\BgaUserException $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());
        }

        // Assert
        $actualNewPotential = $this->rules->repo->listCardIds('potential', playerId: $playerId);
        $this->assertEquivalent($expectedNewPotential, $actualNewPotential);

        $this->assertEquivalent([], $this->rules->repo->listCardIds('retrospective', playerId: $playerId));

    }

    public static function retrospectiveNonChoice(): array
    {
        return [
            [9, [['AGILE_VALUE_COURAGE', 0]], [], [], "Insufficient Funds"],
            [9,
                [['AGILE_MATURITY_TEST_TEAM', 0],['AGILE_MATURITY_USER_EXPERIENCE', 2]],
                [], [], "Insufficient Funds"
            ],
            [9,
                [['AGILE_MATURITY_USER_EXPERIENCE', 0]],
                [], ['AGILE_MATURITY_INTERNAL_COACH'], "Insufficient Funds"
            ],
            [9,
                [['AGILE_MATURITY_CLEAN_CODE', 0]],
                [['AGILE_MATURITY_TEST_TEAM', 0]], ['AGILE_MATURITY_DEVOPS'], "Insufficient Funds"
            ],
            [9,
                [['AGILE_MATURITY_USER_EXPERIENCE', 0]],
                [['AGILE_MATURITY_TEST_TEAM', 0]], [], "Insufficient Funds"
            ],
        ];
    }


    /**
     * @dataProvider retrospectivePayment
     */
    public function testPayForRetrospective($playerId, $selection, $teams, $products, $powers, $retrospective): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_RETROSPECTIVE', 26, [9, 26, 68, 144]);
        $this->addTo('products', $playerId, $products);
        $this->addTo('company', $playerId, $powers);
        $this->addTo('retrospective', $playerId, $retrospective);
        $selected = new FakeSelection($this->rules, $playerId, $selection);
        $expectedNewPotential = array_diff(
            $this->rules->repo->listCardIds('potential', playerId: $playerId),
            $selected->getCardIds()
        );
        $expectedNewProducts = array_diff(
            $this->rules->repo->listCardIds('products', playerId: $playerId),
            $selected->getCardIds()
        );
        $expectedNewCompany = array_merge(
            $this->rules->repo->listCardIds('company', playerId: $playerId),
            $this->rules->repo->listCardIds('retrospective', playerId: $playerId)
        );

        // Act
        $result = $this->rules->payForRetrospective($playerId, $selected->incomingJson());

        // Assert
        $this->assertTrue($result);

        $actualNewPotential = $this->rules->repo->listCardIds('potential', playerId: $playerId);
        $this->assertEquivalent($expectedNewPotential, $actualNewPotential);

        $actualNewProducts = $this->rules->repo->listCardIds('products', playerId: $playerId);
        $this->assertEquivalent($expectedNewProducts, $actualNewProducts);

        $actualNewCompany = $this->rules->repo->listCardIds('company', playerId: $playerId);
        $this->assertEquivalent($expectedNewCompany, $actualNewCompany);

        $this->assertCount(0, $this->rules->repo->listCardIds('retrospective', playerId: $playerId));
    }

    public static function retrospectivePayment(): array
    {
        return [
            [9, [['potential', 0]], [], [], [], ['AGILE_MATURITY_AGILE_PRACTITIONER']],
            [26, [['potential', 0], ['potential', 2]],
                [], [],
                ['AGILE_MATURITY_INTERNAL_COACH'], ['AGILE_VALUE_TRUST']
            ],
            [9, [['potential', 1], ['products', 0]],
                [], [['AGILE_MATURITY_TEST_TEAM', 0]],
                ['AGILE_MATURITY_DEVOPS'], ['AGILE_MATURITY_CLEAN_CODE']
            ],
        ];
    }

    /**
     * @dataProvider retrospectiveNonPayment
     */
    public function testCannotPayForRetrospective($playerId, $selection, $teams, $products, $powers, $retrospective, $expectedMessage): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_RETROSPECTIVE', 26, [9, 26, 68, 144]);
        $this->addTo('products', $playerId, $products);
        $this->addTo('company', $playerId, $powers);
        $this->addTo('retrospective', $playerId, $retrospective);
        $selected = new FakeSelection($this->rules, $playerId, $selection);
        $expectedNewPotential = $this->rules->repo->listCardIds('potential', playerId: $playerId);
        $expectedNewProducts = $this->rules->repo->listCardIds('products', playerId: $playerId);
        $expectedNewCompany = $this->rules->repo->listCardIds('company', playerId: $playerId);
        $expectedNewRetrospective = $this->rules->repo->listCardIds('retrospective', playerId: $playerId);

        // Act
        try {
            $this->rules->payForRetrospective($playerId, $selected->incomingJson());
            $this->fail("Expected exception BgaUserException was not thrown.");
        } catch (\BgaUserException $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());
        }

        // Assert
        $actualNewPotential = $this->rules->repo->listCardIds('potential', playerId: $playerId);
        $this->assertEquivalent($expectedNewPotential, $actualNewPotential);

        $actualNewProducts = $this->rules->repo->listCardIds('products', playerId: $playerId);
        $this->assertEquivalent($expectedNewProducts, $actualNewProducts);

        $actualNewCompany = $this->rules->repo->listCardIds('company', playerId: $playerId);
        $this->assertEquivalent($expectedNewCompany, $actualNewCompany);

        $actualNewRetrospective = $this->rules->repo->listCardIds('retrospective', playerId: $playerId);
        $this->assertEquivalent($expectedNewRetrospective, $actualNewRetrospective);
    }

    public static function retrospectiveNonPayment(): array
    {
        return [
            [9, [], [], [], [], ['AGILE_MATURITY_AGILE_PRACTITIONER'], "Insufficient payment"],
            [26, [['potential', 2]],
                [], [],
                ['AGILE_MATURITY_INTERNAL_COACH'], ['AGILE_VALUE_TRUST'], "Insufficient payment"
            ],
            [9, [['products', 0]],
                [], [['AGILE_MATURITY_TEST_TEAM', 0]],
                ['AGILE_MATURITY_DEVOPS'], ['AGILE_MATURITY_CLEAN_CODE'], "Insufficient payment"
            ],
            [9, [['potential', 1], ['products', 0]],
                [], [['AGILE_MATURITY_TEST_TEAM', 0]],
                [], ['AGILE_MATURITY_CLEAN_CODE'], "Invalid retrospective payment - location not allowed"
            ],
        ];
    }
}
