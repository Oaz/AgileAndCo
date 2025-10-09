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

declare(strict_types=1);

namespace Bga\Games\AgileAndCo;

require_once(APP_GAMEMODULE_PATH . "module/table/table.game.php");

class Game extends \Table
{
    private Rules $rules;

    /**
     * Your global variables labels:
     *
     * Here, you can assign labels to global variables you are using for this game. You can use any number of global
     * variables with IDs between 10 and 99. If your game has options (variants), you also have to associate here a
     * label to the corresponding ID in `gameoptions.inc.php`.
     *
     * NOTE: afterward, you can get/set the global variables with `getGameStateValue`, `setGameStateInitialValue` or
     * `setGameStateValue` functions.
     */
    public function __construct()
    {
        parent::__construct();

        $this->initGameStateLabels([
            "game_length" => 101
        ]);
        $deckAdapter = new DeckAdapter($this->getNew("module.common.deck"));
        $gameAdapter = new GameAdapter($this);
        $this->rules = new Rules($deckAdapter, $gameAdapter);
    }

    public function getGameProgression()
    {
        return $this->rules->getGameProgression();
    }

    /**
     * This method is called only once, when a new game is launched. In this method, you must setup the game
     *  according to the game rules, so that the game is ready to be played.
     */
    protected function setupNewGame($players, $options = [])
    {
        // Set the colors of the players with HTML color code. The default below is red/green/blue/orange/brown. The
        // number of colors defined here must correspond to the maximum number of players allowed for the gams.
        $gameinfos = $this->getGameinfos();
        $default_colors = $gameinfos['player_colors'];

        foreach ($players as $player_id => $player) {
            // Now you can access both $player_id and $player array
            $query_values[] = vsprintf("('%s', '%s', '%s', '%s', '%s')", [
                $player_id,
                array_shift($default_colors),
                $player["player_canal"],
                addslashes($player["player_name"]),
                addslashes($player["player_avatar"]),
            ]);
        }

        // Create players based on generic information.
        //
        // NOTE: You can add extra field on player table in the database (see dbmodel.sql) and initialize
        // additional fields directly here.
        static::DbQuery(
            sprintf(
                "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES %s",
                implode(",", $query_values)
            )
        );

        $this->reattributeColorsBasedOnPreferences($players, $gameinfos["player_colors"]);
        $this->reloadPlayersBasicInfos();

        $this->rules->initGame($players);
    }

    public function argGameState(): array
    {
        return $this->rules->getGameState();
    }

    public function argGamePrivateState($player_id): array
    {
        return $this->rules->getGamePrivateState($player_id);
    }

    public function stStartRound(): void
    {
        $this->activeNextPlayer();
        $this->gamestate->nextState($this->rules->startRound());
    }

    public function stStartActivity(): void
    {
        $this->gamestate->nextState($this->rules->startActivity());
    }

    public function stNextPlayer(): void
    {
        $this->activeNextPlayer();
        $this->gamestate->nextState($this->rules->goToNextPlayer());
    }

    public function actChooseActivity(string $activity_id): void
    {
        $transitionName = $this->rules->chooseActivity($activity_id);
        $this->gamestate->nextState($transitionName);
    }

    public function stConference(): void
    {
        $this->rules->prepareConference();
        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->nextState();
    }

    public function actConference(string $cards): void
    {
        $this->actConferencePlayer((int)$this->getCurrentPlayerId(), json_decode($cards, true));
    }

    public function actConferencePlayer(int $player_id, array $cards): void
    {
        if ($this->rules->completeConference($player_id, $cards)) {
            $this->gamestate->setPlayerNonMultiactive($player_id, "nextPlayer");
            $this->rules->updateState($player_id);
        }
    }

    public function stDevelopment(): void
    {
        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->nextState();
    }

    public function actDevelop(string $teams, string $products): void
    {
        $this->actDevelopPlayer(
            (int)$this->getCurrentPlayerId(),
            json_decode($teams, true),
            json_decode($products, true)
        );
    }

    public function actDevelopPlayer(int $player_id, array $teams, array $products): void
    {
        if ($this->rules->completeDevelopment($player_id, $teams, $products)) {
            $this->gamestate->setPlayerNonMultiactive($player_id, "nextPlayer");
            $this->rules->updateState($player_id);
        }
    }

    public function stDeployment(): void
    {
        $this->rules->prepareDeployment();
        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->nextState();
    }

    public function actDeploy(string $cards): void
    {
        $this->actDeployPlayer((int)$this->getCurrentPlayerId(), json_decode($cards, true));
    }
    public function actDeployPlayer(int $player_id, array $cards): void
    {
        if ($this->rules->completeDeployment($player_id, $cards)) {
            $this->gamestate->setPlayerNonMultiactive($player_id, "nextPlayer");
            $this->rules->updateState($player_id);
        }
    }

    public function stRetrospective(): void
    {
        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->initializePrivateStateForAllActivePlayers();
    }

    public function actRetrospectiveChoice(string $card): void
    {
        $this->actRetrospectiveChoicePlayer((int)$this->getCurrentPlayerId(), json_decode($card, true));
    }

    public function actRetrospectiveChoicePlayer(int $player_id, array $card): void
    {
        if ($this->rules->chooseForRetrospective($player_id, $card)) {
            $this->gamestate->nextPrivateState($player_id, "payment");
        } else {
            $this->gamestate->unsetPrivateState($player_id);
            $this->gamestate->setPlayerNonMultiactive($player_id, "nextPlayer");
        }
        $this->rules->updateState($player_id);
    }

    public function actRetrospectivePayment(string $cards): void
    {
        $player_id = $this->getCurrentPlayerId();
        if ($this->rules->payForRetrospective($player_id, json_decode($cards, true))) {
            $this->gamestate->unsetPrivateState($player_id);
            $this->gamestate->setPlayerNonMultiactive($player_id, "nextPlayer");
            $this->rules->updateState($player_id);
        }
    }

    public function stCoachGivesPotential(): void
    {
        $this->gamestate->nextState($this->rules->doCoach());
    }

    public function stEndRound(): void
    {
        list($transition, $overLimitPlayers) = $this->rules->endRound();
        if (count($overLimitPlayers) == 0) {
            $this->gamestate->nextState($transition);
            return;
        }
        $this->gamestate->setPlayersMultiactive($overLimitPlayers, $transition);
        $this->gamestate->nextState($transition);
    }


    public function actAdjustPotential(string $cards): void
    {
        $this->actAdjustPotentialPlayer((int)$this->getCurrentPlayerId(), json_decode($cards, true));
    }

    public function actAdjustPotentialPlayer(int $player_id, array $cards): void
    {
        if ($this->rules->adjustPotential($player_id, $cards)) {
            $this->gamestate->setPlayerNonMultiactive($player_id, "nextRound");
            $this->rules->updateState($player_id);
        }
    }

    /**
     * Migrate database.
     *
     * You don't have to care about this until your game has been published on BGA. Once your game is on BGA, this
     * method is called everytime the system detects a game running with your old database scheme. In this case, if you
     * change your database scheme, you just have to apply the needed changes in order to update the game database and
     * allow the game to continue to run with your new version.
     *
     * @param int $from_version
     * @return void
     */
    public function upgradeTableDb($from_version)
    {
//       if ($from_version <= 1404301345)
//       {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            $this->applyDbUpgradeToAllDB( $sql );
//       }
//
//       if ($from_version <= 1405061421)
//       {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            $this->applyDbUpgradeToAllDB( $sql );
//       }
    }

    /*
     * Gather all information about current game situation (visible by the current player).
     *
     * The method is called each time the game interface is displayed to a player, i.e.:
     *
     * - when the game starts
     * - when a player refreshes the game page (F5)
     */
    protected function getAllDatas(): array
    {
        $result = [];

        // WARNING: We must only return information visible by the current player.
        $current_player_id = (int)$this->getCurrentPlayerId();

        // Get information about players.
        // NOTE: you can retrieve some extra field you added for "player" table in `dbmodel.sql` if you need it.
        $result["players"] = $this->getCollectionFromDb(
            "SELECT `player_id` `id`, `player_score` `score` FROM `player`"
        );

        // TODO: Gather all information about current game situation (visible by player $current_player_id).

        return $result;
    }


    /**
     * This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
     * You can do whatever you want in order to make sure the turn of this player ends appropriately
     * (ex: pass).
     *
     * Important: your zombie code will be called when the player leaves the game. This action is triggered
     * from the main site and propagated to the gameserver from a server, not from a browser.
     * As a consequence, there is no current player associated to this action. In your zombieTurn function,
     * you must _never_ use `getCurrentPlayerId()` or `getCurrentPlayerName()`, otherwise it will fail with a
     * "Not logged" error message.
     *
     * @param array{ type: string, name: string } $state
     * @param int $active_player
     * @return void
     * @throws feException if the zombie mode is not supported at this game state.
     */
    public function zombieTurn(array $state, int $active_player): void
    {
        $state_name = $state["name"];
        $zombie = new Zombie($this->rules);
        switch ($state_name) {
            case 'playerChooseActivity':
                $this->actChooseActivity($zombie->chooseActivity());
                break;
            case 'conferenceActivity':
                $this->actConferencePlayer($active_player, $zombie->discardConference($active_player));
                break;
            case 'developmentActivity':
                $this->actDevelopPlayer($active_player, [], []);
                break;
            case 'deploymentActivity':
                $this->actDeployPlayer($active_player, []);
                break;
            case 'retrospectiveActivityChoice':
                $this->actRetrospectiveChoicePlayer($active_player, []);
                break;
            case 'adjustPotential':
                $this->actAdjustPotentialPlayer($active_player, $zombie->discardPotential($active_player));
                break;
            default:
                throw new \feException("Zombie mode not supported at this game state: \"{$state_name}\".");
        }


    }

    public function getText(string $messageId): string
    {
        switch ($messageId) {
            case 'ACTIVITY_WAS_CHOSEN' :
                return clienttranslate('${player_name} chooses activity "${activity}"');
            case 'POTENTIAL_ADJUSTMENT' :
                return clienttranslate('${player_name} loses ${potential_loss} potential');
            case 'ACTIVITY_DEVELOPMENT_TITLE' :
                return clienttranslate('Development');
            case 'ACTIVITY_DEVELOPMENT_IMPACT' :
                return clienttranslate('${player_name} develops ${product_count} product(s)');
            case 'ACTIVITY_DEVELOPMENT_IMPACT_PLUS' :
                return clienttranslate('${player_name} develops ${product_count} product(s) and increases potential by 1');
            case 'ACTIVITY_DEPLOYMENT_TITLE' :
                return clienttranslate('Deployment');
            case 'ACTIVITY_DEPLOYMENT_IMPACT' :
                return clienttranslate('${player_name} deploys ${product_count} product(s) and increases potential by ${earnings}');
            case 'ACTIVITY_CONFERENCE_TITLE' :
                return clienttranslate('Conference');
            case 'ACTIVITY_CONFERENCE_IMPACT' :
                return clienttranslate('Conference increases ${player_name} potential by ${potential_gain}');
            case 'ACTIVITY_COACH_TITLE' :
                return clienttranslate('Coach');
            case 'ACTIVITY_COACH_IMPACT' :
                return clienttranslate('Coach increases ${player_name} potential by 1');
            case 'ACTIVITY_RETROSPECTIVE_TITLE' :
                return clienttranslate('Retrospective');
            case 'ACTIVITY_RETROSPECTIVE_IMPACT_MATURITY' :
                return clienttranslate('${player_name} pays ${payment} and gets ${improvement}');
            case 'ACTIVITY_RETROSPECTIVE_IMPACT_VALUE' :
                return clienttranslate('${player_name} pays ${payment} and adopts ${improvement} value');
            case 'ACTIVITY_RETROSPECTIVE_IMPACT_TEAM' :
                return clienttranslate('${player_name} pays ${payment} and hires a new ${improvement} team');
            case "PRODUCT_TEAM_ADVERGAME" :
                return clienttranslate('Advertising games');
            case "PRODUCT_TEAM_EDUCATION" :
                return clienttranslate('Educational games');
            case "PRODUCT_TEAM_SOCIAL" :
                return clienttranslate('Social games');
            case "PRODUCT_TEAM_MMOG" :
                return clienttranslate('Massively multiplayer online games');
            case "AGILE_MATURITY_PASSIONATE_DEVELOPER_TITLE" :
                return clienttranslate('Passionate Developer');
            case "AGILE_MATURITY_AGILE_PRACTITIONER_TITLE" :
                return clienttranslate('Agile Practitioner');
            case "AGILE_MATURITY_USER_EXPERIENCE_TITLE" :
                return clienttranslate('User Experience');
            case "AGILE_MATURITY_PAIR_PROGRAMMING_TITLE" :
                return clienttranslate('Pair Programming');
            case "AGILE_MATURITY_AGILE_ORGANIZER_TITLE" :
                return clienttranslate('Agile Organizer');
            case "AGILE_MATURITY_FEEDBACK_SESSIONS_TITLE" :
                return clienttranslate('Feedback Sessions');
            case "AGILE_MATURITY_CLEAN_CODE_TITLE" :
                return clienttranslate('Clean Code');
            case "AGILE_MATURITY_CONTINUOUS_DELIVERY_TITLE" :
                return clienttranslate('Continuous Delivery');
            case "AGILE_MATURITY_DEVOPS_TITLE" :
                return clienttranslate('DevOps');
            case "AGILE_MATURITY_AGILE_HR_TITLE" :
                return clienttranslate('Agile HR');
            case "AGILE_MATURITY_ENGAGED_USERS_TITLE" :
                return clienttranslate('Engaged Users');
            case "AGILE_MATURITY_INTERNAL_COACH_TITLE" :
                return clienttranslate('Internal Coach');
            case "AGILE_MATURITY_DETAILED_PLANNING_TITLE" :
                return clienttranslate('Detailed Planning');
            case "AGILE_MATURITY_TEST_TEAM_TITLE" :
                return clienttranslate('Test Team');
            case "AGILE_MATURITY_APPLICATION_FRAMEWORK_TITLE" :
                return clienttranslate('Application Framework');
            case "AGILE_MATURITY_AGILE_CERTIFICATION_TITLE" :
                return clienttranslate('Agile Certification');
            case "AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP_TITLE" :
                return clienttranslate('Software Craftsmanship');
            case "AGILE_MATURITY_AGILE_SENSEI_TITLE" :
                return clienttranslate('Agile Sensei');
            case "AGILE_MATURITY_PRODUCT_VISION_TITLE" :
                return clienttranslate('Product Vision');
            case "AGILE_VALUE_HUMOR_TITLE" :
                return clienttranslate('Humor');
            case "AGILE_VALUE_FEEDBACK_TITLE" :
                return clienttranslate('Feedback');
            case "AGILE_VALUE_SIMPLICITY_TITLE" :
                return clienttranslate('Simplicity');
            case "AGILE_VALUE_FOCUS_TITLE" :
                return clienttranslate('Focus');
            case "AGILE_VALUE_OPENNESS_TITLE" :
                return clienttranslate('Openness');
            case "AGILE_VALUE_COURAGE_TITLE" :
                return clienttranslate('Courage');
            case "AGILE_VALUE_RESPECT_TITLE" :
                return clienttranslate('Respect');
            default:
                return $messageId;
        }
    }

    public function otherText(): void
    {
        // This function is a hack to declare translation strings that are only used in the frontend
        // because the technology used in the frontend makes it difficult for the BGA system to detect
        // the translation strings
        $x = clienttranslate('My Company');
        $x = clienttranslate('My Potential');
        $x = clienttranslate('Confirm activity selection');
        $x = clienttranslate('You must select one activity');
        $x = clienttranslate('Confirm discarding %s card(s)');
        $x = clienttranslate('You must discard %s card(s)');
        $x = clienttranslate('Confirm development of %s product(s)');
        $x = clienttranslate('You cannot develop more than %s product(s)');
        $x = clienttranslate('Numbers of selected teams and discarded potential must match');
        $x = clienttranslate('Confirm deployment of %s product(s)');
        $x = clienttranslate('Cannot deploy more than %s product(s)');
        $x = clienttranslate('Confirm selection of %s improvement');
        $x = clienttranslate('Cannot select more than one improvement');
        $x = clienttranslate('Confirm payment of improvement');
        $x = clienttranslate('You must select cards corresponding to the cost');
        $x = clienttranslate('Cost');
        $x = clienttranslate('Score');
        $x = clienttranslate('When this card changes hands, players must have a maximum of six cards in hand.');
        $x = clienttranslate('Any additional cards are discarded.');
        $x = clienttranslate('Benefit');
        $x = clienttranslate('Bonus');
        $x = clienttranslate('Earnings');
        $x = clienttranslate('Activity');
        $x = clienttranslate('Each player can develop a product');
        $x = clienttranslate('The activity initiator can develop one additional product');
        $x = clienttranslate('Each player can deploy a product');
        $x = clienttranslate('The activity initiator can deploy one additional product');
        $x = clienttranslate('Each player can set up a team or an improvement action');
        $x = clienttranslate('The activity initiator pays one less card');
        $x = clienttranslate('Each player draws two cards and keeps one');
        $x = clienttranslate('The activity initiator draws five cards instead of two');
        $x = clienttranslate('The coach\'s intervention is limited to the activity initiator');
        $x = clienttranslate('The activity initiator draws one card and keeps it');
        $x = clienttranslate('Product Team');
        $x = clienttranslate('Agile Maturity');
        $x = clienttranslate('Your involvement in the developer community facilitates recruitment.');
        $x = clienttranslate('You pay one less card to form a product team.');
        $x = clienttranslate('Your involvement in the agile community allows you to make the most of peer-to-peer meetings.');
        $x = clienttranslate('You can discard cards from your hand instead of drawn cards.');
        $x = clienttranslate('You pay particular attention to user business needs.');
        $x = clienttranslate('You receive an extra card when deploying at least two products.');
        $x = clienttranslate('Systematic pair programming improves communication among your developers.');
        $x = clienttranslate('You draw a card when developing at least two products.');
        $x = clienttranslate('You organize meetings among agile practitioners to enhance exchanges.');
        $x = clienttranslate('You keep one extra card among the drawn cards.');
        $x = clienttranslate('You learn more about yourself by analyzing the impact of your decisions.');
        $x = clienttranslate('You draw a card after implementing an improvement action.');
        $x = clienttranslate('Attention to code quality allows you to code faster and better.');
        $x = clienttranslate('You develop an additional product.');
        $x = clienttranslate('Your product is always ready to be delivered.');
        $x = clienttranslate('You deploy an additional product.');
        $x = clienttranslate('Removing barriers between teams streamlines the product lifecycle.');
        $x = clienttranslate('You can pay with yet-to-be-deployed products, each valued at 2 cards.');
        $x = clienttranslate('The agility of your internal organization increases your capacity for action.');
        $x = clienttranslate('You can hold up to 10 cards when changing the game leader.');
        $x = clienttranslate('You involve your users in product development.');
        $x = clienttranslate('You receive an extra card during deployment.');
        $x = clienttranslate('A coach is always available to improve your teams.');
        $x = clienttranslate('You pay one less card to carry out an improvement action.');
        $x = clienttranslate('Detailed planning allows you to predict the future with precision.');
        $x = clienttranslate('An independent test team ensures products always meet expectations.');
        $x = clienttranslate('Using an application framework boosts your development productivity.');
        $x = clienttranslate('Certifying your teams ensures adoption of agile values, principles, and practices.');
        $x = clienttranslate('This card is automatically added to your company if it\'s in your hand at the end of the game.');
        $x = clienttranslate('Your developers are driven by unyielding professionalism.');
        $x = clienttranslate('Each product team earns you two additional points at the end of the game.');
        $x = clienttranslate('You have mastered the art of advancing your teams.');
        $x = clienttranslate('Each value or improvement action earns you one additional point at the end of the game.');
        $x = clienttranslate('Your strategy is underpinned by a clear vision of your products.');
        $x = clienttranslate('Your final score is increased by 30%.');
        $x = clienttranslate('Agile Value');
        $x = clienttranslate('Each adopted value grants the right to keep an additional card at the beginning of a turn.');
        $x = clienttranslate('Is humor an agile value? Some think so.');
        $x = clienttranslate('Feedback is one of the values emphasized by Extreme Programming.');
        $x = clienttranslate('Simplicity is one of the values emphasized by Extreme Programming.');
        $x = clienttranslate('Focus is one of the values emphasized by Scrum.');
        $x = clienttranslate('Openness is one of the values emphasized by Scrum.');
        $x = clienttranslate('Courage is a value shared by Scrum and Extreme Programming.');
        $x = clienttranslate('Respect is a value shared by Scrum and Extreme Programming.');
    }

}
