<?php

namespace Bga\Games\AgileAndCo;

class Rules
{
    public readonly CardsRepository $repo;
    private IDeckAdapter $cards;
    private IGameAdapter $game;
    public readonly IGlobalVariable $ongoingActivity;
    public readonly IGlobalVariable $currentEarnings;
    public readonly IGlobalVariable $completedActivitiesCount;

    private IScoreComputer $scoreComputer;


    public function __construct(IDeckAdapter $cards, IGameAdapter $game, IScoreComputer $scoreComputer = null)
    {
        $this->game = $game;
        $this->cards = $cards;
        $this->repo = new CardsRepository(CardsData::$groups, CardsData::$details, $cards);
        $this->ongoingActivity = $this->game->globalVariable('ONGOING_ACTIVITY');
        $this->currentEarnings = $this->game->globalVariable('CURRENT_EARNINGS');
        $this->completedActivitiesCount = $this->game->globalVariable('COMPLETED_ACTIVITIES_COUNT');
        $this->scoreComputer = $scoreComputer ?? new ScoreComputer($this->repo);
    }

    private bool $isDebugEnabled = false;

    private function debug(string $message, callable $argsCallback): void
    {
        if (!$this->isDebugEnabled)
            return;
        $this->game->notifyAllPlayers("message", 'DEBUG: ' . $message, $argsCallback());
    }

    public function initGame($players): void
    {
        $this->cards->createCards(CardsData::$instances, 'deck');
        $this->cards->deckify('ACTIVITY', 'activities');
        $this->cards->deckify('EARNINGS', 'earnings');
        $startupTeams = array_values($this->cards->getCardsOfType('PRODUCT_TEAM', 0));
        $i = 0;
        foreach ($players as $player_id => $player) {
            $this->cards->moveCard($startupTeams[$i++]['id'], 'teams', playerId: $player_id);
        }
        $this->cards->shuffle('deck');
        foreach ($players as $player_id => $player) {
            $this->cards->pickCardsForLocation(4, 'deck', 'potential', $player_id);
        }
        $this->ongoingActivity->write(['', 0]);
        $this->currentEarnings->write(0);
        $this->completedActivitiesCount->write(0);
    }


    public function broadcast(string $message, array $args = []): void
    {
        $this->game->notifyAllPlayers("message", $message, $args);
    }

    public const TOTAL_ACTIVITY_COUNT = 48;

    public function getGameProgression(): int
    {
        $currentCount = $this->completedActivitiesCount->read();
        return 100 * $currentCount / Rules::TOTAL_ACTIVITY_COUNT;
    }

    //#############################
    // GLOBAL GAME STATE
    //#############################


    public function getGameState(): array
    {
        $infos = $this->game->loadInfos();
        $playerGames = array_map(function ($player) {
            return $this->getPlayerPrivateState($player['player_id']);
        }, $infos->players);
        $publicPlayerGames = array_map(function ($player) {
            $playerCopy = array_map(function ($item) {
                return $item;
            }, $player);
            $playerCopy['potentialSize'] = count($playerCopy['potential']);
            unset($playerCopy['potential']);
            unset($playerCopy['conference']);
            return $playerCopy;
        }, $playerGames);

        list($ongoingActivity, $activityInitiator) = $this->ongoingActivity->read();
        $activities = Helpers::pairsToDictionary(array_map(function ($activityCard) use ($ongoingActivity) {
            $index = $activityCard->index;
            $activityName = $this->repo->getActivities()[$index];
            $selected = $ongoingActivity == $activityName;
            $hidden = !$selected && $activityCard->playerId == 1;
            return [$index, [$activityName, $hidden, $selected]];
        }, $this->repo->loadFromLocation('activities')));

        $earnings = $this->repo->getAll('EARNINGS')[$this->currentEarnings->read()];
        $data = [
            'public' => [
                'active_player' => $ongoingActivity == '' ? $this->game->getActivePlayerId() : 0,
                'central' => [
                    'selection' => false,
                    'activities' => Helpers::orderedArrayValues($activities),
                    'earnings' => [$earnings, $ongoingActivity != 'ACTIVITY_DEPLOYMENT'],
                ],
                'players' => $publicPlayerGames
            ],
            '_private' => $playerGames,
        ];
        if ($this->isDebugEnabled)
            $data['public']['debug'] = $this->getDebugInfos($infos, $playerGames);
        return $data;
    }

    public function getPlayerPrivateState(int $playerId): array
    {
        list($ongoingActivity, $activityInitiator) = $this->ongoingActivity->read();
        $infos = $this->game->loadInfos();
        $currentActivity = in_array($playerId, $infos->activePlayers) ? $ongoingActivity : '';
        if ($currentActivity == 'ACTIVITY_RETROSPECTIVE')
            $currentActivity = 'ACTIVITY_RETROSPECTIVE_CHOOSE';
        $teams = $this->repo->getCardsInLocationSortedByIndexes('teams', $playerId);
        $company = $this->repo->getCardsInLocationSortedByUsage('company', $playerId);
        $potential = $this->repo->getCardsInLocationSortedByUsage('potential', $playerId);
        $selectedInRetrospective = array_values($this->repo->listCards('retrospective', playerId: $playerId));
        if (count($selectedInRetrospective) > 0)
            $currentActivity = 'ACTIVITY_RETROSPECTIVE_PAYMENT';
        $gameIsComplete = $this->getGameProgression() === 100;
        return [
            'id' => $playerId,
            'name' => $infos->players[$playerId]['player_name'],
            'score' => $this->scoreComputer->computeScore($teams, $company, $potential, $gameIsComplete),
            'activity' => $currentActivity,
            'initiate' => $activityInitiator == $playerId,
            'teams' => $teams,
            'products' => $this->repo->getCardsInLocationSortedByIndexes('products', $playerId),
            'company' => $company,
            'potential' => $potential,
            'conference' => $this->repo->getCardsInLocationSortedByUsage('conference', $playerId),
            'retrospective' => $selectedInRetrospective
        ];
    }

    private function getDebugInfos($infos, array $playerGames): array
    {
        return [
            'completed_activities' => $this->completedActivitiesCount->read(),
            'infos' => $infos,
            'act_type' => $this->repo->getActivities(),
            'activities' => $this->repo->loadFromLocation('activities'),
            'earnings' => $this->repo->loadFromLocation('earnings'),
            'teams' => $this->repo->loadFromLocation('teams'),
            'products' => $this->repo->loadFromLocation('products'),
            'potential' => $this->repo->loadAndSortByUsageFromLocation('potential', null),
            'discard' => $this->repo->loadFromLocation('discard'),
            'conference' => $this->repo->loadFromLocation('conference'),
            'deck' => $this->repo->loadFromLocation('deck'),
            'yolo' => $playerGames,
        ];
    }


    public function getGamePrivateState($player_id): array
    {
        $gameState = $this->getGameState();
        return [
            'public' => $gameState['public'],
            '_private' => $gameState['_private'][$player_id],
        ];
    }

    public function updateState($player_id): void
    {
        $gameState = $this->getGameState();
        $this->game->setScore($player_id, $gameState['_private'][$player_id]['score']);
        $this->game->notifyPlayer($player_id, 'updatePrivateState', '', [
            '_private' => $gameState['_private'][$player_id],
        ]);
        $this->game->notifyAllPlayers('updatePublicState', '', [
            'public' => $gameState['public'],
        ]);
    }


    //#############################
    // ROUND MANAGEMENT : START, NEXT PLAYER, CLOSE & ADJUST POTENTIAL
    //#############################


    public function startRound(): string
    {
        $activityCards = $this->cards->getCardsInLocation("activities");
        foreach ($activityCards as $card) {
            $this->cards->moveCard($card['id'], "activities", index: $card['index'], playerId: 0);
        }
        return "startActivities";
    }

    public function startActivity(): string
    {
        $this->ongoingActivity->write(['', 0]);
        return "chooseActivity";
    }

    public function goToNextPlayer(): string
    {
        $currentCount = $this->completedActivitiesCount->read();
        $currentCount++;
        $this->completedActivitiesCount->write($currentCount);
        $infos = $this->game->loadInfos();
        $numberOfPlayers = count($infos->players);
        if ($currentCount % $numberOfPlayers == 0)
            return "endRound";
        return "nextActivity";
    }

    public function endRound(): array
    {
        $currentCount = $this->completedActivitiesCount->read();
        if ($currentCount == Rules::TOTAL_ACTIVITY_COUNT) {
            $gameState = $this->getGameState();
            foreach ($gameState['public']['players'] as $player) {
                $this->game->setScore($player['id'], $player['score']);
            }
            return ["endGame", []];
        }
        $overLimitPlayers = $this->getPlayersOverPotentialLimit();
        if (count($overLimitPlayers) > 0) {
            $this->ongoingActivity->write(['END_OF_ROUND', 0]);
            return ["closeRound", $overLimitPlayers];
        }
        return ["nextRound", []];
    }


    private function getPlayersOverPotentialLimit()
    {
        $gameState = $this->getGameState();
        $players = $gameState['public']['players'];
        $overLimitPlayers = [];
        foreach ($players as $player) {
            if ($player['potentialSize'] > 6) {
                $overLimitPlayers[] = $player['id'];
            }
        }
        return $overLimitPlayers;
    }

    public function adjustPotential($player_id, $cards): bool
    {
        $infos = $this->game->loadInfos();
        $shouldDiscard = $this->computeNumberOfDiscardWhenAdjustingPotentialAtEndOfRound($player_id);
        $wantDiscard = count($cards);
        if ($wantDiscard != $shouldDiscard)
            throw new \BgaUserException("Should discard {$shouldDiscard} instead of {$wantDiscard}");
        if(count($cards) === 0)
            return true;
        $selection = new CardSelection('discard', $player_id, [
            'potential' => SortForSelection::BY_USAGE
        ], $this->repo);
        foreach ($cards as $card) {
            $selected = $selection->take($card);
            $cardId = $selected->id;
            $this->cards->playCard($cardId);
            $this->debug('${player_name} discards ${cardName} id ${cardId}', function () use ($infos, $player_id, $cardId, $selected) {
                return [
                    "player_name" => $infos->getPlayerName($player_id),
                    "cardId" => $cardId,
                    "cardName" => $selected->fullName,
                ];
            });
        }
        $this->broadcast(Text::get('POTENTIAL_ADJUSTMENT'), [
            "player_name" => $infos->getPlayerName($player_id),
            "potential_loss" => $shouldDiscard,
        ]);
        return true;
    }

    public function computeNumberOfDiscardWhenAdjustingPotentialAtEndOfRound($player_id): int
    {
        $player = $this->getPlayerPrivateState($player_id);
        $potentialSize = count($player['potential']);
        $maximumPotential = in_array('AGILE_MATURITY_AGILE_HR', $player['company']) ? 10 : 6;
        $numberOfValues = count(array_filter($player['company'], fn($x) => $this->repo->createCardTemplate($x)->type == 'AGILE_VALUE'));
        $maximumPotential += $numberOfValues;
        $shouldDiscard = $potentialSize > $maximumPotential ? $potentialSize - $maximumPotential : 0;
        return $shouldDiscard;
    }

    //#############################
    // ACTIVITY CHOICE
    //#############################

    public function chooseActivity(string $activity): string
    {
        $activityIndex = $this->repo->getActivityIndex($activity);
        $selection = $this->repo->loadFromLocation('activities', index: $activityIndex);
        if (count($selection) === 0)
            throw new \BgaUserException('Invalid activity choice');
        $activityCard = array_values($selection)[0];

        $transition = match ($activity) {
            'ACTIVITY_CONFERENCE' => 'activityConference',
            'ACTIVITY_DEVELOPMENT' => 'activityDevelopment',
            'ACTIVITY_DEPLOYMENT' => 'activityDeployment',
            'ACTIVITY_RETROSPECTIVE' => 'activityRetrospective',
            'ACTIVITY_COACH' => 'activityCoach',
            default => throw new \BgaUserException('Invalid activity choice'),
        };

        $this->useActivity($activityCard->id, $activityIndex);

        $player_id = $this->game->getActivePlayerId();
        $this->ongoingActivity->write([$activity, $player_id]);

        $this->broadcast(Text::get('ACTIVITY_WAS_CHOSEN'), [
            "player_id" => $player_id,
            "player_name" => $this->game->getActivePlayerName(),
            "activity" => Text::get($activity . '_TITLE'),
        ]);
        return $transition;
    }

    public function useActivity($cardId, $cardIndex)
    {
        $this->cards->moveCard($cardId, "activities", index: $cardIndex, playerId: 1);
    }

    //#############################
    // ACTIVITY: COACH
    //#############################

    public function doCoach(): string
    {
        $player_id = $this->game->getActivePlayerId();
        $this->cards->pickCardsForLocation(1, 'deck', 'potential', $player_id);
        $this->broadcast(Text::get('ACTIVITY_COACH_IMPACT'), [
            "player_name" => $this->game->getActivePlayerName(),
        ]);
        return "nextPlayer";
    }

    //#############################
    // ACTIVITY: CONFERENCE
    //#############################

    public function prepareConference(): void
    {
        list($ongoingActivity, $activityInitiator) = $this->ongoingActivity->read();
        $infos = $this->game->loadInfos();
        foreach ($infos->players as $player_id => $player) {
            $n = $activityInitiator == $player_id ? 5 : 2;
            $this->cards->pickCardsForLocation($n, 'deck', 'conference', $player_id);
        }
    }

    public function completeConference($player_id, $cards): bool
    {
        $infos = $this->game->loadInfos();
        $player = $this->getPlayerPrivateState($player_id);
        $shouldDiscard = $player['initiate'] ? 4 : 1;
        if (in_array('AGILE_MATURITY_AGILE_ORGANIZER', $player['company']))
            $shouldDiscard -= 1;
        $wantDiscard = count($cards);
        if ($wantDiscard != $shouldDiscard)
            throw new \BgaUserException("Should discard {$shouldDiscard} instead of {$wantDiscard}");
        $selection = in_array('AGILE_MATURITY_AGILE_PRACTITIONER', $player['company'])
            ? new CardSelection('discard', $player_id, [
                'potential' => SortForSelection::BY_USAGE,
                'conference' => SortForSelection::BY_USAGE
            ], $this->repo)
            : new CardSelection('discard', $player_id, [
                'conference' => SortForSelection::BY_USAGE
            ], $this->repo);
        foreach ($cards as $card) {
            $selected = $selection->take($card);
            $cardId = $selected->id;
            $this->cards->playCard($cardId);
            $this->debug('${player_name} discards ${cardName} id ${cardId}', function () use ($infos, $player_id, $cardId, $selected) {
                return [
                    "player_name" => $infos->getPlayerName($player_id),
                    "cardId" => $cardId,
                    "cardName" => $selected->fullName,
                ];
            });
        }
        $potentialGain = count($player['conference']) - $shouldDiscard;
        $this->cards->moveAllCardsInLocation('conference', 'potential', playerId: $player_id);
        $this->broadcast(Text::get('ACTIVITY_CONFERENCE_IMPACT'), [
            "player_name" => $infos->getPlayerName($player_id),
            "potential_gain" => $potentialGain,
        ]);
        return true;
    }


    //#############################
    // ACTIVITY: DEVELOPMENT
    //#############################

    public function completeDevelopment($player_id, $teams, $products): bool
    {
        if (count($teams) === 0)
            return true;
        if (count($teams) != count($products))
            throw new \BgaUserException("Should have same number of selected teams and products");
        $infos = $this->game->loadInfos();
        $player = $this->getPlayerPrivateState($player_id);
        $maxNbOfDev = $player['initiate'] ? 2 : 1;
        if (in_array('AGILE_MATURITY_CLEAN_CODE', $player['company']))
            $maxNbOfDev += 1;
        if (count($teams) > $maxNbOfDev)
            throw new \BgaUserException("Cannot develop more than {$maxNbOfDev} product(s)");
        $inputs = array_map(null, $teams, $products);
        $teamSelection = new CardSelection('development team', $player_id, ['teams' => SortForSelection::BY_INDEX], $this->repo);
        $productSelection = new CardSelection('development product', $player_id, ['potential' => SortForSelection::BY_USAGE], $this->repo);
        $products = $player['products'];
        foreach ($inputs as $input) {
            $team = $teamSelection->peek($input[0]);
            if (isset($products[$team->index]) && $products[$team->index] !== false)
                throw new \BgaUserException("Team must deploy before developing another product");
        }
        foreach ($inputs as $input) {
            $team = $teamSelection->take($input[0]);
            $product = $productSelection->take($input[1]);
            $this->cards->moveCard($product->id, 'products', $team->index, $player_id);
            $this->debug('${player_name} develop in team ${teamCard} with potential ${productName}', function () use ($infos, $player_id, $team, $product) {
                return [
                    "player_name" => $infos->getPlayerName($player_id),
                    "teamCard" => $team->fullName,
                    "productName" => $product->fullName,
                ];
            });
        }
        if (count($teams) >= 2 && in_array('AGILE_MATURITY_PAIR_PROGRAMMING', $player['company'])) {
            $this->cards->pickCardsForLocation(1, 'deck', 'potential', $player_id);
            $this->broadcast(Text::get('ACTIVITY_DEVELOPMENT_IMPACT_PLUS'), [
                "player_name" => $infos->getPlayerName($player_id),
                "product_count" => count($inputs),
            ]);
        } else {
            $this->broadcast(Text::get('ACTIVITY_DEVELOPMENT_IMPACT'), [
                "player_name" => $infos->getPlayerName($player_id),
                "product_count" => count($inputs),
            ]);
        }
        return true;
    }

    //#############################
    // ACTIVITY: DEPLOYMENT
    //#############################

    public function prepareDeployment(): void
    {
        $index = \bga_rand(0, count($this->repo->getAll('EARNINGS')) - 1);
        $this->currentEarnings->write($index);
    }

    public function completeDeployment($player_id, $cards): bool
    {
        if (count($cards) === 0)
            return true;
        $infos = $this->game->loadInfos();
        $player = $this->getPlayerPrivateState($player_id);
        $maxNbOfDeployment = $player['initiate'] ? 2 : 1;
        if (in_array('AGILE_MATURITY_CONTINUOUS_DELIVERY', $player['company']))
            $maxNbOfDeployment += 1;
        if (count($cards) > $maxNbOfDeployment)
            throw new \BgaUserException("Cannot deploy more than {$maxNbOfDeployment} product(s)");
        $earningCard = $this->repo->getAll('EARNINGS')[$this->currentEarnings->read()];
        $earnings = CardsData::$details[$earningCard];
        $totalEarning = 0;
        $selection = new CardSelection('deployment', $player_id, ['products' => SortForSelection::BY_INDEX], $this->repo);
        foreach ($cards as $card) {
            $product = $selection->take($card);
            $cardId = $product->id;
            $team = $this->repo->getSingleCard('teams', $product->index, $player_id);
            $earning = $earnings[$team->name];
            $totalEarning += $earning;
            $this->cards->playCard($cardId);
            $this->cards->pickCardsForLocation($earning, 'deck', 'potential', $player_id);
            $this->debug('${player_name} deploys ${cardName} (${cardId}) from ${teamType} and earns ${earning}', function () use ($infos, $player_id, $team, $product, $cardId, $earning) {
                return [
                    "player_name" => $infos->getPlayerName($player_id),
                    "cardName" => $product->name,
                    "cardId" => $cardId,
                    "teamType" => $team->name,
                    "earning" => $earning,
                ];
            });
        }
        if (count($cards) >= 1 && in_array('AGILE_MATURITY_ENGAGED_USERS', $player['company'])) {
            $this->cards->pickCardsForLocation(1, 'deck', 'potential', $player_id);
            $totalEarning += 1;
        }
        if (count($cards) >= 2 && in_array('AGILE_MATURITY_USER_EXPERIENCE', $player['company'])) {
            $this->cards->pickCardsForLocation(1, 'deck', 'potential', $player_id);
            $totalEarning += 2;
        }
        $this->broadcast(Text::get('ACTIVITY_DEPLOYMENT_IMPACT'), [
            "player_name" => $infos->getPlayerName($player_id),
            "product_count" => count($cards),
            "earnings" => $totalEarning,
        ]);
        return true;
    }

    //#############################
    // ACTIVITY: RETROSPECTIVE
    //#############################

    public function chooseForRetrospective($player_id, $card): bool
    {
        if (count($card) == 0)
            return false;
        $infos = $this->game->loadInfos();
        $selection = new CardSelection('retrospective choice', $player_id, ['potential' => SortForSelection::BY_USAGE], $this->repo);
        $player = $this->getPlayerPrivateState($player_id);
        if ($this->computeCost($selection->peek($card), $player) > $this->computeFunds($player))
            throw new \BgaUserException("Insufficient Funds");
        $selected = $selection->take($card);
        $cardId = $selected->id;
        $this->cards->moveCard($cardId, 'retrospective', playerId: $player_id);
        $this->debug('${player_name} choose ${cardName} (${cardId}) during retrospective', function () use ($infos, $player_id, $cardId, $selected) {
            return [
                "player_name" => $infos->getPlayerName($player_id),
                "cardName" => $selected->fullName,
                "cardId" => $cardId,
            ];
        });
        return true;
    }

    public function payForRetrospective($player_id, $cards): bool
    {
        $infos = $this->game->loadInfos();
        $player = $this->getPlayerPrivateState($player_id);
        $selection = in_array('AGILE_MATURITY_DEVOPS', $player['company'])
            ? new CardSelection('retrospective payment', $player_id, [
                'potential' => SortForSelection::BY_USAGE,
                'products' => SortForSelection::BY_INDEX
            ], $this->repo)
            : new CardSelection('retrospective payment', $player_id, [
                'potential' => SortForSelection::BY_USAGE
            ], $this->repo);
        $targetCard = $this->repo->createCardTemplate($player['retrospective'][0]);
        $payment = 0;
        foreach ($cards as $card) {
            $selected = $selection->peek($card);
            $payment += $selected->location === "products" ? 2 : 1;
        }
        $expectedPayment = $this->computeCost($targetCard, $player);
        if ($payment < $expectedPayment)
            throw new \BgaUserException("Insufficient payment {$payment} expected {$expectedPayment}");;
        foreach ($cards as $card) {
            $selected = $selection->take($card);
            $cardId = $selected->id;
            $this->cards->playCard($cardId);
            $this->debug('${player_name} pays with ${cardName} id ${cardId}', function () use ($infos, $player_id, $cardId, $selected) {
                return [
                    "player_name" => $infos->getPlayerName($player_id),
                    "cardId" => $cardId,
                    "cardName" => $selected->fullName,
                ];
            });
        }
        $player = $this->getPlayerPrivateState($player_id);
        $selectedCard = $player['retrospective'][0];
        $cardTemplate = $this->repo->createCardTemplate($selectedCard);
        if ($cardTemplate->type === 'PRODUCT_TEAM') {
            $targetLocation = 'teams';
            $message = 'ACTIVITY_RETROSPECTIVE_IMPACT_TEAM';
            $improvement = $selectedCard;
        } else if ($cardTemplate->type === 'AGILE_VALUE') {
            $targetLocation = 'company';
            $message = 'ACTIVITY_RETROSPECTIVE_IMPACT_VALUE';
            $improvement = $selectedCard . '_TITLE';
        } else {
            $targetLocation = 'company';
            $message = 'ACTIVITY_RETROSPECTIVE_IMPACT_MATURITY';
            $improvement = $selectedCard . '_TITLE';
        }
        $this->repo->moveCardsFromToLocation('retrospective', $targetLocation, playerId: $player_id);
        $this->broadcast(Text::get($message), [
            "player_name" => $infos->getPlayerName($player_id),
            "payment" => $payment,
            "improvement" => Text::get($improvement),
        ]);
        return true;
    }

    public function computeCost(PlayerCard $card, array $player)
    {
        $cost = $card->cost;
        if ($player['initiate'])
            $cost--;
        if (($card->type == 'AGILE_MATURITY' || $card->type == 'AGILE_VALUE')
            && in_array('AGILE_MATURITY_INTERNAL_COACH', $player['company']))
            $cost--;
        if ($card->type == 'PRODUCT_TEAM'
            && in_array('AGILE_MATURITY_PASSIONATE_DEVELOPER', $player['company']))
            $cost--;
        return $cost;
    }

    public function computeFunds(array $player)
    {
        $funds = count($player['potential']) - 1;
        if (in_array('AGILE_MATURITY_DEVOPS', $player['company'])) {
            $products = array_filter($player['products'], fn($x) => $x);
            $funds += 2 * count($products);
        }
        return $funds;
    }


}