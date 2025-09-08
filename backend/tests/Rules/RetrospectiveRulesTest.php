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
    public function testPayForRetrospective($playerId, $selection, $teams, $products, $powers, $retrospective, $expectedMessage): void
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

        $this->assertEquals($expectedMessage, $this->game->lastMessage);
    }

    public static function retrospectivePayment(): array
    {
        $cost_1_NoPowers = [
            9, [['potential', 0]], [], [], [], ['AGILE_MATURITY_AGILE_PRACTITIONER'],
            "playerA pays 1 and gets Agile Practitioner"
        ];
        $cost_3_NoPowers = [
            9, [['potential', 0],['potential', 1],['potential', 2]], [], [], [], ['AGILE_MATURITY_CLEAN_CODE'],
            "playerA pays 3 and gets Clean Code"
        ];
        $cost_4_MinusOneAsInitiator = [26, [['potential', 0], ['potential', 2], ['potential', 3]],
            [], [], [], ['AGILE_VALUE_FOCUS'],
            "playerB pays 3 and adopts Focus value"
        ];
        $cost_3_MinusOneAsInternalCoach = [9, [['potential', 0], ['potential', 2]],
            [], [],
            ['AGILE_MATURITY_INTERNAL_COACH'], ['AGILE_MATURITY_CLEAN_CODE'],
            "playerA pays 2 and gets Clean Code"
        ];
        $cost_4_MinusOneAsInitiator_MinusOneAsInternalCoach = [26, [['potential', 0], ['potential', 2]],
            [], [],
            ['AGILE_MATURITY_INTERNAL_COACH'], ['AGILE_VALUE_FOCUS'],
            "playerB pays 2 and adopts Focus value"
        ];
        $cost_3_PaidWithProductAsDevops = [9, [['potential', 1], ['products', 0]],
            [], [['AGILE_MATURITY_TEST_TEAM', 0]],
            ['AGILE_MATURITY_DEVOPS'], ['AGILE_MATURITY_CLEAN_CODE'],
            "playerA pays 3 and gets Clean Code"
        ];
        $cost_1_PaidWithProductAsDevops = [9, [['products', 0]],
            [], [['AGILE_MATURITY_TEST_TEAM', 0]],
            ['AGILE_MATURITY_DEVOPS'], ['AGILE_MATURITY_AGILE_PRACTITIONER'],
            "playerA pays 2 and gets Agile Practitioner"
        ];
        $cost_3_MinusOneAsInternalCoach_PaidWithProductAsDevops = [9, [['products', 0]],
            [], [['AGILE_MATURITY_TEST_TEAM', 0]],
            ['AGILE_MATURITY_INTERNAL_COACH', 'AGILE_MATURITY_DEVOPS'], ['AGILE_MATURITY_CLEAN_CODE'],
            "playerA pays 2 and gets Clean Code"
        ];
        $cost_4_PaidWithTwoProductAsDevops = [9, [['products', 0], ['products', 1]],
            [], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]],
            ['AGILE_MATURITY_DEVOPS'], ['AGILE_VALUE_FOCUS'],
            "playerA pays 4 and adopts Focus value"
        ];
        return [
            $cost_1_NoPowers,
            $cost_3_NoPowers,
            $cost_4_MinusOneAsInitiator,
            $cost_3_MinusOneAsInternalCoach,
            $cost_4_MinusOneAsInitiator_MinusOneAsInternalCoach,
            $cost_3_PaidWithProductAsDevops,
            $cost_1_PaidWithProductAsDevops,
            $cost_3_MinusOneAsInternalCoach_PaidWithProductAsDevops,
            $cost_4_PaidWithTwoProductAsDevops
        ];
    }

    /**
 * @dataProvider retrospectivePaymentTeams
 */
    public function testPayForRetrospectiveTeams($playerId, $selection, $teams, $products, $powers, $retrospective, $expectedMessage): void
    {
        // Arrange
        $this->withMultiActivity('ACTIVITY_RETROSPECTIVE', 26, [9, 26, 68, 144]);
        $this->addTo('products', $playerId, $products);
        $this->addTo('teams', $playerId, $teams);
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
        $expectedNewTeams = array_merge(
            $this->rules->repo->listCardIds('teams', playerId: $playerId),
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

        $actualNewTeams = $this->rules->repo->listCardIds('teams', playerId: $playerId);
        $this->assertEquivalent($expectedNewTeams, $actualNewTeams);

        $this->assertCount(0, $this->rules->repo->listCardIds('retrospective', playerId: $playerId));

        $this->assertEquals($expectedMessage, $this->game->lastMessage);
    }

    public static function retrospectivePaymentTeams(): array
    {
        $cost_3_NoPowers = [
            9, [['potential', 0],['potential', 1],['potential', 2]], [], [], [], ['PRODUCT_TEAM_SOCIAL'],
            "playerA pays 3 and hires a new Social games team"
        ];
        $cost_4_MinusOneAsInitiator = [26, [['potential', 0], ['potential', 2], ['potential', 3]],
            [], [], [], ['PRODUCT_TEAM_MMOG'],
            "playerB pays 3 and hires a new Massively multiplayer online games team"
        ];
        $cost_3_MinusOneAsPassionateDeveloper = [9, [['potential', 0], ['potential', 2]],
            [], [],
            ['AGILE_MATURITY_PASSIONATE_DEVELOPER'], ['PRODUCT_TEAM_SOCIAL'],
            "playerA pays 2 and hires a new Social games team"
        ];
        $cost_4_MinusOneAsInitiator_MinusOneAsPassionateDeveloper = [26, [['potential', 0], ['potential', 2]],
            [], [],
            ['AGILE_MATURITY_PASSIONATE_DEVELOPER'], ['PRODUCT_TEAM_MMOG'],
            "playerB pays 2 and hires a new Massively multiplayer online games team"
        ];
        $cost_3_PaidWithProductAsDevops = [9, [['potential', 1], ['products', 0]],
            [], [['AGILE_MATURITY_TEST_TEAM', 0]],
            ['AGILE_MATURITY_DEVOPS'], ['PRODUCT_TEAM_SOCIAL'],
            "playerA pays 3 and hires a new Social games team"
        ];
        $cost_3_MinusOneAsPassionateDeveloper_PaidWithProductAsDevops = [9, [['products', 0]],
            [], [['AGILE_MATURITY_TEST_TEAM', 0]],
            ['AGILE_MATURITY_PASSIONATE_DEVELOPER', 'AGILE_MATURITY_DEVOPS'], ['PRODUCT_TEAM_SOCIAL'],
            "playerA pays 2 and hires a new Social games team"
        ];
        $cost_4_PaidWithTwoProductAsDevops = [9, [['products', 0], ['products', 1]],
            [], [['AGILE_MATURITY_TEST_TEAM', 0], ['AGILE_MATURITY_TEST_TEAM', 1]],
            ['AGILE_MATURITY_DEVOPS'], ['PRODUCT_TEAM_MMOG'],
            "playerA pays 4 and hires a new Massively multiplayer online games team"
        ];
        return [
            $cost_3_NoPowers,
            $cost_4_MinusOneAsInitiator,
            $cost_3_MinusOneAsPassionateDeveloper,
            $cost_4_MinusOneAsInitiator_MinusOneAsPassionateDeveloper,
            $cost_3_PaidWithProductAsDevops,
            $cost_3_MinusOneAsPassionateDeveloper_PaidWithProductAsDevops,
            $cost_4_PaidWithTwoProductAsDevops
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
        $cost_1_NoPowers_paid_0 = [9, [], [], [], [], ['AGILE_MATURITY_AGILE_PRACTITIONER'], "Insufficient payment 0 expected 1"];
        $cost_4_MinusOneAsInternalCoach_paid_1 = [26, [['potential', 2]],
            [], [],
            ['AGILE_MATURITY_INTERNAL_COACH'], ['AGILE_VALUE_FOCUS'], "Insufficient payment 1 expected 2"
        ];
        $cost_3_PaidWithProductAsDevops = [9, [['products', 0]],
            [], [['AGILE_MATURITY_TEST_TEAM', 0]],
            ['AGILE_MATURITY_DEVOPS'], ['AGILE_MATURITY_CLEAN_CODE'], "Insufficient payment 2 expected 3"
        ];
        $tryToPayWithProductWithoutDevops = [9, [['potential', 1], ['products', 0]],
            [], [['AGILE_MATURITY_TEST_TEAM', 0]],
            [], ['AGILE_MATURITY_CLEAN_CODE'], "Invalid retrospective payment - location not allowed"
        ];
        return [
            $cost_1_NoPowers_paid_0,
            $cost_4_MinusOneAsInternalCoach_paid_1,
            $cost_3_PaidWithProductAsDevops,
            $tryToPayWithProductWithoutDevops,
        ];
    }
}
