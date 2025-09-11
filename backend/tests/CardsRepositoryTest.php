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

use Bga\Games\AgileAndCo\CardsData;
use Bga\Games\AgileAndCo\CardsRepository;
use PHPUnit\Framework\TestCase;

class CardsRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        $this->deck = new FakeDeck();
        $this->deck->createCards(CardsData::$instances, 'deck');
        $this->repo = new CardsRepository(CardsData::$groups, CardsData::$details, $this->deck);
        $data = array_map(function ($data) {
            $data['id'] = 0;
            $data['player_id'] = 0;
            $data['location'] = '';
            $data['index'] = 0;
            return $data;
        }, CardsData::$instances);

        $cards = array_map(fn($d) => $this->repo->createCard($d), $data);
        $this->cards = array_combine(array_map(fn($c) => $c->fullName, $cards), $cards);
    }

    private CardsRepository $repo;
    private FakeDeck $deck;
    private array $cards;

    public function testNaturalOrder()
    {
        $this->assertEquals(-1, $this->repo->naturalOrder($this->cards['PRODUCT_TEAM_ADVERGAME'], $this->cards['PRODUCT_TEAM_EDUCATION']));
        $this->assertEquals(1, $this->repo->naturalOrder($this->cards['PRODUCT_TEAM_EDUCATION'], $this->cards['PRODUCT_TEAM_ADVERGAME']));
        $this->assertEquals(-2, $this->repo->naturalOrder($this->cards['PRODUCT_TEAM_ADVERGAME'], $this->cards['PRODUCT_TEAM_SOCIAL']));
        $this->assertEquals(-2, $this->repo->naturalOrder($this->cards['AGILE_MATURITY_PASSIONATE_DEVELOPER'], $this->cards['AGILE_MATURITY_DEVOPS']));
        $this->assertEquals(1, $this->repo->naturalOrder($this->cards['AGILE_MATURITY_PASSIONATE_DEVELOPER'], $this->cards['PRODUCT_TEAM_SOCIAL']));
        $this->assertEquals(1, $this->repo->naturalOrder($this->cards['AGILE_MATURITY_DEVOPS'], $this->cards['PRODUCT_TEAM_SOCIAL']));
        $this->assertEquals(-1, $this->repo->naturalOrder($this->cards['PRODUCT_TEAM_SOCIAL'], $this->cards['AGILE_MATURITY_DEVOPS']));
    }

    public function testIndexes()
    {
        $this->assertEquals(0, $this->repo->getIndex('PRODUCT_TEAM', 'ADVERGAME'));
        $this->assertEquals(1, $this->repo->getIndex('PRODUCT_TEAM', 'EDUCATION'));
        $this->assertEquals(2, $this->repo->getIndex('PRODUCT_TEAM', 'SOCIAL'));
        $this->assertEquals(0, $this->repo->getIndex('AGILE_MATURITY', 'PASSIONATE_DEVELOPER'));
        $this->assertEquals(8, $this->repo->getIndex('AGILE_MATURITY', 'DEVOPS'));
    }

    public function testGetCardsInLocationSortedByIndexes()
    {
        $this->repo->moveCardsToLocation([
            'AGILE_MATURITY_DEVOPS', 'PRODUCT_TEAM_EDUCATION', 'PRODUCT_TEAM_SOCIAL',
            'AGILE_MATURITY_PASSIONATE_DEVELOPER', 'PRODUCT_TEAM_ADVERGAME'], 'potential', 23);
        $cards = $this->repo->getCardsInLocationSortedByIndexes('potential', 23);
        $this->assertEquals([
            'AGILE_MATURITY_DEVOPS', 'PRODUCT_TEAM_EDUCATION', 'PRODUCT_TEAM_SOCIAL',
            'AGILE_MATURITY_PASSIONATE_DEVELOPER', 'PRODUCT_TEAM_ADVERGAME'], $cards);
    }

    public function testGetCardsInLocationSortedByIncompleteIndexes()
    {
        $deck_cards = $this->repo->getCards('AGILE_MATURITY_DEVOPS');
        $deck_card = $deck_cards[0];
        $this->deck->moveCard($deck_card['id'], 'products', 1, 23);
        $cards = $this->repo->getCardsInLocationSortedByIndexes('products', 23);
        $this->assertEquals([false, 'AGILE_MATURITY_DEVOPS'], $cards);
    }

    public function testIndexesAreIncrementedWhenMovingCards()
    {
        $this->repo->moveCardsToLocation(
            ['PRODUCT_TEAM_EDUCATION', 'PRODUCT_TEAM_SOCIAL'], 'teams', 23);
        $this->repo->moveCardsToLocation(
            ['PRODUCT_TEAM_MMOG', 'PRODUCT_TEAM_ADVERGAME'], 'teams', 23);
        $cards = $this->repo->loadFromLocation('teams', playerId: 23);
        $indexes = array_map(function ($card) {
            return $card->index;
        }, $cards);
        $this->assertEquals([0,1,2,3], $indexes);
    }

    public function testIndexesAreIncrementedWhenMovingCardsFromLocation()
    {
        $this->repo->moveCardsToLocation(
            ['PRODUCT_TEAM_EDUCATION', 'PRODUCT_TEAM_SOCIAL'], 'teams', 23);
        $this->repo->moveCardsToLocation(
            ['PRODUCT_TEAM_MMOG', 'PRODUCT_TEAM_ADVERGAME'], 'somewhere', 23);
        $this->repo->moveCardsFromToLocation('somewhere', 'teams', 23);
        $indexes = array_map(function ($card) {
            return $card->index;
        }, $this->repo->loadFromLocation('teams', playerId: 23));
        $this->assertEquals([0,1,2,3], $indexes);
    }

    public function testGetCardsInLocationSortedByUsage()
    {
        $this->repo->moveCardsToLocation([
            'AGILE_MATURITY_DEVOPS', 'PRODUCT_TEAM_EDUCATION', 'PRODUCT_TEAM_SOCIAL',
            'AGILE_MATURITY_PASSIONATE_DEVELOPER', 'PRODUCT_TEAM_ADVERGAME'], 'potential', 23);
        $cards = $this->repo->getCardsInLocationSortedByUsage('potential', 23);
        $this->assertEquals([
            'PRODUCT_TEAM_ADVERGAME', 'PRODUCT_TEAM_EDUCATION', 'PRODUCT_TEAM_SOCIAL',
            'AGILE_MATURITY_PASSIONATE_DEVELOPER', 'AGILE_MATURITY_DEVOPS'], $cards);
    }

    public function testGetCardsInLocationSortedByUsageWithMultiples()
    {
        $this->repo->moveCardsToLocation([
            'AGILE_MATURITY_DEVOPS', 'PRODUCT_TEAM_SOCIAL', 'AGILE_MATURITY_PASSIONATE_DEVELOPER', 'PRODUCT_TEAM_EDUCATION', 'PRODUCT_TEAM_SOCIAL',
            'AGILE_MATURITY_AGILE_PRACTITIONER', 'AGILE_MATURITY_PASSIONATE_DEVELOPER', 'PRODUCT_TEAM_ADVERGAME'], 'potential', 23);
        $cards = $this->repo->getCardsInLocationSortedByUsage('potential', 23);
        $this->assertEquals([
            'PRODUCT_TEAM_ADVERGAME', 'PRODUCT_TEAM_EDUCATION', 'PRODUCT_TEAM_SOCIAL', 'PRODUCT_TEAM_SOCIAL', 'AGILE_MATURITY_AGILE_PRACTITIONER',
            'AGILE_MATURITY_PASSIONATE_DEVELOPER', 'AGILE_MATURITY_PASSIONATE_DEVELOPER', 'AGILE_MATURITY_DEVOPS'], $cards);
    }
}
