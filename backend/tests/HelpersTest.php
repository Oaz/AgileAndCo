<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\Helpers;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function testCardName()
    {
        var_dump(class_exists('Bga\Games\AgileAndCo\Helpers'));

        $card = ['type' => 'ACTIVITY', 'type_arg' => 2];
        $this->assertEquals('ACTIVITY_DEPLOYMENT', Helpers::getCardName($card));
    }
}
