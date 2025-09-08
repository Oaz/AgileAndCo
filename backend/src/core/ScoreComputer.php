<?php

namespace Bga\Games\AgileAndCo;

readonly class ScoreComputer implements IScoreComputer
{
    public function __construct(CardsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function computeScore($teams, $company, $potential, $gameIsComplete): int
    {
        $score = 0;
        foreach ($teams as $team) {
            $card = $this->repo->createCardTemplate($team);
            $score += $card->score;
        }
        $valuesCount = 0;
        $agileSensei = false;
        $productVision = false;
        $softwareCraftsmanship = false;
        foreach ($company as $action) {
            $card = $this->repo->createCardTemplate($action);
            $score += $card->score;
            if ($card->type == 'AGILE_VALUE')
                $valuesCount += 1;
            $agileSensei = $agileSensei || ($action == 'AGILE_MATURITY_AGILE_SENSEI');
            $productVision = $productVision || ($action == 'AGILE_MATURITY_PRODUCT_VISION');
            $softwareCraftsmanship = $softwareCraftsmanship || ($action == 'AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP');
        }
        $score += $valuesCount * $valuesCount;
        $malusCount = 0;
        if ($gameIsComplete) {
            foreach ($potential as $malus) {
                $card = $this->repo->createCardTemplate($malus);
                if ($card->score >= 0)
                    continue;
                $score += $card->score;
                $malusCount++;
            }
            if ($agileSensei)
                $score += count($company) + $malusCount;
            if ($softwareCraftsmanship)
                $score += 2 * count($teams);
            if ($productVision)
                $score = 1.3 * $score;
        }
        return $score;
    }

    private CardsRepository $repo;
}