<?php

namespace Bga\Games\AgileAndCo;

class Rules
{
    public readonly CardsRepository $repo;
    private IDeckAdapter $cards;
    private IGameAdapter $game;
    public readonly IGlobalVariable $ongoingActivity;
    public readonly IGlobalVariable $currentEarnings;

    public function __construct(IDeckAdapter $cards, IGameAdapter $game)
    {
        $this->game = $game;
        $this->cards = $cards;
        $this->repo = new CardsRepository(CardsData::$groups, CardsData::$details, $cards);
        $this->ongoingActivity = $this->game->globalVariable('ONGOING_ACTIVITY');
        $this->currentEarnings = $this->game->globalVariable('CURRENT_EARNINGS');
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
    }

    public function getGameState(): array
    {
        list($ongoingActivity, $activityInitiator) = $this->ongoingActivity->read();

        $infos = $this->game->loadInfos();
        $playerGames = array_map(function ($player) use ($ongoingActivity, $activityInitiator, $infos) {
            $player_id = $player['player_id'];
            $currentActivity = in_array($player_id, $infos->activePlayers) ? $ongoingActivity : '';
            if($currentActivity == 'ACTIVITY_RETROSPECTIVE')
                $currentActivity = 'ACTIVITY_RETROSPECTIVE_CHOOSE';
            $potential = $this->repo->getCardsInLocationSortedByUsage('potential', $player['player_id']);
            $selectedInRetrospective = array_values($this->repo->listCards('retrospective', playerId: $player['player_id']));
            if(count($selectedInRetrospective) > 0)
                $currentActivity = 'ACTIVITY_RETROSPECTIVE_PAYMENT';
            return [
                'name' => $player['player_name'],
                'activity' => $currentActivity,
                'initiate' => $activityInitiator == $player_id,
                'teams' => $this->repo->getCardsInLocationSortedByIndexes('teams', $player_id),
                'products' => $this->repo->getCardsInLocationSortedByIndexes('products', $player_id),
                'company' => $this->repo->getCardsInLocationSortedByUsage('company', $player['player_id']),
                'potential' => $potential,
                'conference' => $this->repo->getCardsInLocationSortedByUsage('conference', $player['player_id']),
                'retrospective' => $selectedInRetrospective
            ];
        }, $infos->players);
        $publicPlayerGames = array_map(function ($player) {
            $playerCopy = array_map(function ($item) {
                return $item;
            }, $player);
            unset($playerCopy['potential']);
            unset($playerCopy['conference']);
            return $playerCopy;
        }, $playerGames);

        $activities = Helpers::pairsToDictionary(array_map(function ($activityCard) use ($ongoingActivity) {
            $index = $activityCard->index;
            $activityName = $this->repo->getActivities()[$index];
            $selected = $ongoingActivity == $activityName;
            $hidden = !$selected && $activityCard->playerId == 1;
            return [$index, [$activityName, $hidden, $selected]];
        }, $this->repo->loadFromLocation('activities')));

        $earnings = $this->repo->getAll('EARNINGS')[$this->currentEarnings->read()];
        return [
            'public' => [
                'active_player' => $ongoingActivity == '' ? $this->game->getActivePlayerId() : 0,
                'central' => [
                    'selection' => false,
                    'activities' => Helpers::orderedArrayValues($activities),
                    'earnings' => [$earnings, $ongoingActivity != 'ACTIVITY_DEPLOYMENT'],
                ],
                'players' => $publicPlayerGames,
                'debug' => $this->getDebugInfos($infos, $playerGames)
            ],
            '_private' => $playerGames,
        ];
    }

    private function getDebugInfos($infos, array $playerGames): array
    {
        return [
            'infos' => $infos,
            'act_type' => $this->repo->getActivities(),
            'activities' => $this->repo->loadFromLocation('activities'),
            'earnings' => $this->repo->loadFromLocation('earnings'),
            'teams' => $this->repo->loadFromLocation('teams'),
            'products' => $this->repo->loadFromLocation('products'),
            'potential' => $this->repo->loadAndSortByUsageFromLocation('potential',null),
            'discard' => $this->repo->loadFromLocation('discard'),
            'conference' => $this->repo->loadFromLocation('conference'),
            'deck' => $this->repo->loadFromLocation('deck'),
            'yolo' => $playerGames,
        ];
    }

    public function chooseActivity(string $activity): string
    {
        $activityIndex = $this->repo->getActivityIndex($activity);
        $selection = $this->repo->loadFromLocation('activities', index:$activityIndex);
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

        $this->cards->moveCard($activityCard->id, 'activities', index: $activityIndex, playerId: 1);

        $player_id = $this->game->getActivePlayerId();
        $this->ongoingActivity->write([$activity, $player_id]);

        $this->broadcast('${player_name} chooses activity ${activity}', [
            "player_id" => $player_id,
            "player_name" => $this->game->getActivePlayerName(),
            "activity" => $activity,
        ]);
        return $transition;
    }

    public function broadcast(string $message, array $args = []): void
    {
        $this->game->notifyAllPlayers("message", $message, $args);
    }

    public function prepareConference(): void
    {
        list($ongoingActivity, $activityInitiator) = $this->ongoingActivity->read();
        $infos = $this->game->loadInfos();
        foreach ($infos->players as $player_id => $player) {
            $n = $activityInitiator == $player_id ? 5 : 2;
            $this->cards->pickCardsForLocation($n, 'deck', 'conference', $player_id);
        }
    }

    public function doCoach(): string
    {
        $player_id = $this->game->getActivePlayerId();
        $this->cards->pickCardsForLocation(1, 'deck', 'potential', $player_id);

        $this->broadcast('Coach gives potential to ${player_name}', [
            "player_id" => $player_id,
            "player_name" => $this->game->getActivePlayerName(),
        ]);
        return "nextPlayer";
    }

    public function gotToNextPlayer(): string
    {
        $this->ongoingActivity->write(['', 0]);
        return "nextActivity";
    }

    public function completeConference($player_id, $cards): bool
    {
        $infos = $this->game->loadInfos();
        $selection = new CardSelection('discard', $player_id, [
            'potential' => SortForSelection::BY_USAGE,
            'conference' => SortForSelection::BY_USAGE
        ], $this->repo);
        foreach ($cards as $card) {
            $selected = $selection->take($card);
            $cardId = $selected->id;
            $this->cards->playCard($cardId);
            $this->broadcast('DEBUG: ${player_name} discards ${cardName} id ${cardId}', [
                "player_name" => $infos->getPlayerName($player_id),
                "cardId" => $cardId,
                "cardName" => $selected->fullName,
            ]);
        }
        $this->cards->moveAllCardsInLocation('conference', 'potential', playerId: $player_id);
        return true;
    }

    public function completeDevelopment($player_id, $teams, $products): bool
    {
        $infos = $this->game->loadInfos();
        $inputs = array_map(null, $teams, $products);
        $teamSelection = new CardSelection('development team', $player_id, ['teams' => SortForSelection::BY_INDEX], $this->repo);
        $productSelection = new CardSelection('development product', $player_id, ['potential' => SortForSelection::BY_USAGE], $this->repo);
        foreach ($inputs as $input) {
            $team = $teamSelection->take($input[0]);
            $product = $productSelection->take($input[1]);
            $this->cards->moveCard($product->id, 'products', $team->index, $player_id);
            $this->broadcast('DEBUG: ${player_name} develop in team ${teamCard} with potential ${productName}', [
                "player_name" => $infos->getPlayerName($player_id),
                "teamCard" => $team->fullName,
                "productName" => $product->fullName,
            ]);
        }
        return true;
    }

    public function prepareDeployment(): void
    {
        $index = rand(0, count($this->repo->getAll('EARNINGS')) - 1);
        $this->currentEarnings->write($index);
    }

    public function completeDeployment($player_id, $cards): bool
    {
        $infos = $this->game->loadInfos();
        $earningCard = $this->repo->getAll('EARNINGS')[$this->currentEarnings->read()];
        $earnings = CardsData::$details[$earningCard];
        $selection = new CardSelection('deployment', $player_id, ['products' => SortForSelection::BY_INDEX], $this->repo);
        foreach ($cards as $card) {
            $product = $selection->take($card);
            $cardId = $product->id;
            $team = $this->repo->getSingleCard('teams', $product->index, $player_id);
            $earning = $earnings[$team->name];
            $this->cards->playCard($cardId);
            $this->cards->pickCardsForLocation($earning, 'deck', 'potential', $player_id);
            $this->broadcast('DEBUG: ${player_name} deploys ${cardName} (${cardId}) from ${teamType} and earns ${earning}', [
                "player_name" => $infos->getPlayerName($player_id),
                "cardName" => $product->name,
                "cardId" => $cardId,
                "teamType" => $team->name,
                "earning" => $earning,
            ]);
        }
        return true;
    }

    public function chooseForRetrospective($player_id, $card): bool
    {
        $infos = $this->game->loadInfos();
        $selection = new CardSelection('retrospective choice', $player_id, ['potential' => SortForSelection::BY_USAGE], $this->repo);
        $selected = $selection->take($card);
        $cardId = $selected->id;
        $this->cards->moveCard($cardId, 'retrospective', playerId:$player_id);
        $this->broadcast('DEBUG: ${player_name} choose ${cardName} (${cardId}) during retrospective', [
            "player_name" => $infos->getPlayerName($player_id),
            "cardName" => $selected->fullName,
            "cardId" => $cardId,
        ]);
        return true;
    }

    public function payForRetrospective($player_id, $cards): bool
    {
        $infos = $this->game->loadInfos();
        $selection = new CardSelection('retrospective payment', $player_id, ['potential' => SortForSelection::BY_USAGE], $this->repo);
        foreach ($cards as $card) {
            $selected = $selection->take($card);
            $cardId = $selected->id;
            $this->cards->playCard($cardId);
            $this->broadcast('DEBUG: ${player_name} pays with ${cardName} id ${cardId}', [
                "player_name" => $infos->getPlayerName($player_id),
                "cardId" => $cardId,
                "cardName" => $selected->fullName,
            ]);
        }
        $this->cards->moveAllCardsInLocation('retrospective', 'company', playerId:$player_id);
        return true;
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
        $this->game->notifyPlayer($player_id, 'updateState', '', $this->getGamePrivateState($player_id));
    }

}