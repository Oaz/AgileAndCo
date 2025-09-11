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
use Bga\Games\AgileAndCo\IGlobalVariable;
class FakeGlobalVariable implements IGlobalVariable
{
    private mixed $value = 0;
    public function read(): mixed
    {
        return $this->value;
    }

    public function write(mixed $value): void
    {
        $this->value = $value;
    }

    public function delete(): void
    {
        // TODO: Implement delete() method.
    }
}