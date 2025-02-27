<?php

require_once __DIR__ . '/../src/ports/GameInfos.php';
require_once __DIR__ . '/../src/ports/IDeckAdapter.php';
require_once __DIR__ . '/../src/ports/IGlobalVariable.php';
require_once __DIR__ . '/../src/ports/IGameAdapter.php';

require_once __DIR__ . '/../src/core/CardsData.php';
require_once __DIR__ . '/../src/core/Card.php';
require_once __DIR__ . '/../src/core/PlayerCard.php';
require_once __DIR__ . '/../src/core/CardsRepository.php';
require_once __DIR__ . '/../src/core/CardSelection.php';
require_once __DIR__ . '/../src/core/Helpers.php';
require_once __DIR__ . '/../src/core/Rules.php';

require_once __DIR__ . '/Fakes/FakeSelection.php';
require_once __DIR__ . '/Fakes/FakeDeck.php';
require_once __DIR__ . '/Fakes/FakeGlobalVariable.php';
require_once __DIR__ . '/Fakes/FakeGame.php';
require_once __DIR__ . '/Fakes/BgaUserException.php';
require_once __DIR__ . '/Rules/RulesTestCase.php';
