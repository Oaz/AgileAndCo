<?php

namespace Bga\Games\AgileAndCo;

interface IScoreComputer
{
    public function computeScore($teams, $company, $potential, $gameIsComplete): int;
}