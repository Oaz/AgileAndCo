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

readonly class Card
{
    public function __construct(int $id, string $type, string $name, int $playerId, string $location, int $index)
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->fullName = $type . '_' . $name;
        $this->playerId = $playerId;
        $this->location = $location;
        $this->index = $index;
    }

    public int $id;
    public string $type;
    public string $name;
    public string $fullName;
    public int $playerId;
    public string $location;
    public int $index;

    public function withIndex(int $index): Card {
        return new self($this->id, $this->type, $this->name, $this->playerId, $this->location, $index);
    }

    public function __toString(): string
    {
        return json_encode(get_object_vars($this));
    }
}