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

class GlobalVariable implements IGlobalVariable
{
    private mixed $game;
    private mixed $name;
    public function __construct($game, $name)
    {
        $this->game = $game;
        $this->name = $name;
    }

    public function readState(int $defaultValue) : int|string {
        return $this->game->getGameStateValue($this->name, $defaultValue);
    }

    public function read() : mixed {
        return $this->game->globals->get($this->name);
    }

    public function write(mixed $value) : void {
        $this->game->globals->set($this->name, $value);
    }

    public function delete() : void {
        $this->game->globals->delete($this->name);
    }
}