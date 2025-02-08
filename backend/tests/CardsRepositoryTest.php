<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\CardsData;
use Bga\Games\AgileAndCo\CardsRepository;
use PHPUnit\Framework\TestCase;

class CardsRepositoryTest extends TestCase
{
    public function testCardName()
    {
        $repo = new CardsRepository(CardsData::$groups, new FakeDeck());
        $this->assertEquals('ACTIVITY_DEPLOYMENT', $repo->getCardName(['type' => 'ACTIVITY', 'type_arg' => 2]));
        $this->assertEquals('AGILE_MATURITY_DEVOPS', $repo->getCardName(['type' => 'AGILE_MATURITY', 'type_arg' => 8]));
    }
}
