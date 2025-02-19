<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\CardsData;
use Bga\Games\AgileAndCo\CardsRepository;
use Bga\Games\AgileAndCo\CardSelection;
use Bga\Games\AgileAndCo\SortForSelection;
use PHPUnit\Framework\TestCase;

class CardSelectionTest extends TestCase
{
    private CardsRepository $repo;
    private FakeDeck $deck;
    private CardSelection $sut;

    protected function setUp(): void
    {
        $this->deck = new FakeDeck();
        $this->repo = new CardsRepository(CardsData::$groups, CardsData::$details, $this->deck);

        $this->repo->moveCardsToLocation(
            ['AGILE_MATURITY_DEVOPS', 'PRODUCT_TEAM_EDUCATION', 'AGILE_VALUE_HUMOR'], 'location1', 23
        );
        $this->repo->moveCardsToLocation(
            ['AGILE_MATURITY_PASSIONATE_DEVELOPER', 'PRODUCT_TEAM_ADVERGAME'], 'location2', 23
        );

        $this->sut = new CardSelection('foo', 23, [
            'location1' => SortForSelection::BY_INDEX,
            'location2' => SortForSelection::BY_USAGE
        ], $this->repo);
    }

    public function testTakeValidCard()
    {
        $selectedCard = $this->sut->take(['zone' => 'location1', 'index' => 1, 'name' => 'PRODUCT_TEAM_EDUCATION']);
        $this->assertEquals('PRODUCT_TEAM_EDUCATION', $selectedCard->fullName);
        $this->assertEquals(1, $selectedCard->index);
    }

    public function testTakeOtherValidCard()
    {
        $selectedCard = $this->sut->take(['zone' => 'location2', 'index' => 0, 'name' => 'PRODUCT_TEAM_ADVERGAME']);
        $this->assertEquals('PRODUCT_TEAM_ADVERGAME', $selectedCard->fullName);
        $this->assertEquals(0, $selectedCard->index);
    }

    public function testTakeInvalidLocation()
    {
        $this->expectException(\BgaUserException::class);
        $this->expectExceptionMessage('Invalid foo - location not allowed');
        $this->sut->take(['zone' => 'invalid_location', 'index' => 0, 'name' => 'FOO']);
    }

    public function testTakeInvalidIndex()
    {
        $this->expectException(\BgaUserException::class);
        $this->expectExceptionMessage('Invalid foo - unexpected index');
        $this->sut->take(['zone' => 'location1', 'index' => 99, 'name' => 'FOO']);
    }

    public function testTakeCardNameMismatch()
    {
        $this->expectException(\BgaUserException::class);
        $this->expectExceptionMessage('Invalid foo - card name mismatch: expected PRODUCT_TEAM_EDUCATION but was FOO');
        $this->sut->take(['zone' => 'location1', 'index' => 1, 'name' => 'FOO']);
    }

    public function testTakeDuplicateCard()
    {
        $card = ['zone' => 'location1', 'index' => 1, 'name' => 'PRODUCT_TEAM_EDUCATION'];
        $this->sut->take($card);

        $this->expectException(\BgaUserException::class);
        $this->expectExceptionMessage('Invalid foo - duplicate card');
        $this->sut->take($card);
    }
}
