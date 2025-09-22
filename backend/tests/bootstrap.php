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

require_once __DIR__ . '/../src/ports/GameInfos.php';
require_once __DIR__ . '/../src/ports/IDeckAdapter.php';
require_once __DIR__ . '/../src/ports/IGlobalVariable.php';
require_once __DIR__ . '/../src/ports/IGameAdapter.php';
require_once __DIR__ . '/../src/ports/IScoreComputer.php';

require_once __DIR__ . '/../src/core/CardsData.php';
require_once __DIR__ . '/../src/core/Card.php';
require_once __DIR__ . '/../src/core/PlayerCard.php';
require_once __DIR__ . '/../src/core/CardsRepository.php';
require_once __DIR__ . '/../src/core/CardSelection.php';
require_once __DIR__ . '/../src/core/Helpers.php';
require_once __DIR__ . '/../src/core/ScoreComputer.php';
require_once __DIR__ . '/../src/core/Statistics.php';
require_once __DIR__ . '/../src/core/Rules.php';
require_once __DIR__ . '/../src/core/Zombie.php';

require_once __DIR__ . '/Fakes/misc.php';
require_once __DIR__ . '/Fakes/FakeSelection.php';
require_once __DIR__ . '/Fakes/FakeDeck.php';
require_once __DIR__ . '/Fakes/FakeGlobalVariable.php';
require_once __DIR__ . '/Fakes/FakeGame.php';
require_once __DIR__ . '/Fakes/BgaUserException.php';
require_once __DIR__ . '/Rules/RulesTestCase.php';
