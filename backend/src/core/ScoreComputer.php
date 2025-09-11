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