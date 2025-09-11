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

class Statistics
{
    private IGameAdapter $game;

    public static array $names = [
        "activity_count_conference",
        "activity_count_development",
        "activity_count_deployment",
        "activity_count_retrospective",
        "activity_count_coach",
        "product_development_count",
        "product_deployment_count",
        "total_earnings",
        "potential_stream",
        "potential_loss"
    ];

    public function __construct(IGameAdapter $game)
    {
        $this->game = $game;
    }

    public function initialize(): void
    {
        foreach (self::$names as $name) {
            $this->game->initializeTableStatistic($name, 0);
            $this->game->initializePlayerStatistic($name, 0);
        }
    }

    public function addActivity(string $activity, int $playerId): void {
        $name = match ($activity) {
            'ACTIVITY_CONFERENCE' => 'activity_count_conference',
            'ACTIVITY_DEVELOPMENT' => 'activity_count_development',
            'ACTIVITY_DEPLOYMENT' => 'activity_count_deployment',
            'ACTIVITY_RETROSPECTIVE' => 'activity_count_retrospective',
            'ACTIVITY_COACH' => 'activity_count_coach',
            default => throw new \BgaUserException('Invalid activity choice'),
        };
        $this->game->incrementStatistic($name, 1, $playerId);
        $this->game->incrementStatistic($name, 1);
    }

    public function addProductDevelopment(int $count, int $playerId): void {
        $this->game->incrementStatistic("product_development_count", $count, $playerId);
        $this->game->incrementStatistic("product_development_count", $count);
    }
    public function addProductDeployment(int $count, int $playerId): void {
        $this->game->incrementStatistic("product_deployment_count", $count, $playerId);
        $this->game->incrementStatistic("product_deployment_count", $count);
    }
    public function addEarnings(int $earnings, int $playerId): void {
        $this->game->incrementStatistic("total_earnings", $earnings, $playerId);
        $this->game->incrementStatistic("total_earnings", $earnings);
    }
    public function addPotentialStream(int $incoming, int $playerId): void {
        $this->game->incrementStatistic("potential_stream", $incoming, $playerId);
        $this->game->incrementStatistic("potential_stream", $incoming);
    }
    public function addPotentialLoss(int $loss, int $playerId): void {
        $this->game->incrementStatistic("potential_loss", $loss, $playerId);
        $this->game->incrementStatistic("potential_loss", $loss);
    }

}