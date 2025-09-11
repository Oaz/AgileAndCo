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

readonly class Zombie
{
    private Rules $rules;

    public function __construct(Rules $rules)
    {
        $this->rules = $rules;
    }

    public function chooseActivity(): string
    {
        $available_activities = $this->getAvailableActivities();
        return $available_activities[bga_rand(0, count($available_activities) - 1)];
    }

    public function getAvailableActivities(): array
    {
        $state = $this->rules->getGameState()['public'];
        $available_activities = array_map(function ($activity) use ($state) {
            return $activity[0];
        },
            array_filter($state['central']['activities'], function ($activity) use ($state) {
                return !$activity[1] && !$activity[2];
            }));
        return array_values($available_activities);
    }

    public function discardConference(int $playerId): array
    {
        $player = $this->rules->getGameState()['_private'][$playerId];
        $conference = $player['conference'];
        $shouldDiscard = $this->rules->shouldDiscardForConference($player);
        if($shouldDiscard === 0)
            return [];
        $discard = array_map(function ($index) use ($conference) {
            return ['zone' => 'conference', 'index' => $index, 'name' => $conference[$index]];
        },range(0, $shouldDiscard - 1));
        return $discard;
    }

    public function discardPotential(int $playerId): array
    {
        $player = $this->rules->getGameState()['_private'][$playerId];
        $potential = $player['potential'];
        $shouldDiscard = $this->rules->computeNumberOfDiscardWhenAdjustingPotentialAtEndOfRound($playerId);
        if($shouldDiscard === 0)
            return [];
        $discard = array_map(function ($index) use ($potential) {
            return ['zone' => 'potential', 'index' => $index, 'name' => $potential[$index]];
        },range(0, $shouldDiscard - 1));
        return $discard;
    }
}
