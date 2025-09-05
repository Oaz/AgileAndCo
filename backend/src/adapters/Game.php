<?php
/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * AgileAndCo implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * Game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 */
declare(strict_types=1);

namespace Bga\Games\AgileAndCo;

require_once(APP_GAMEMODULE_PATH . "module/table/table.game.php");

class Game extends \Table
{
    private mixed $rules;

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
            "my_first_global_variable" => 10,
            "my_second_global_variable" => 11,
            "my_first_game_variant" => 100,
            "my_second_game_variant" => 101,
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
     * Returns the game name.
     *
     * IMPORTANT: Please do not modify.
     */
    protected function getGameName()
    {
        return "agileandco";
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

        // Init global values with their initial values.

        // Dummy content.
        $this->setGameStateInitialValue("my_first_global_variable", 0);

        // Init game statistics.
        //
        // NOTE: statistics used in this file must be defined in your `stats.inc.php` file.

        // Dummy content.
        // $this->initStat("table", "table_teststat1", 0);
        // $this->initStat("player", "player_teststat1", 0);

        // TODO: Setup the initial game situation here.
        $this->rules->initGame($players);

        // Activate first player once everything has been initialized and ready.
        $this->activeNextPlayer();
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
        $player_id = $this->getCurrentPlayerId();
        if ($this->rules->completeConference($player_id, json_decode($cards, true))) {
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
        $player_id = $this->getCurrentPlayerId();
        if ($this->rules->completeDevelopment($player_id, json_decode($teams, true), json_decode($products, true))) {
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
        $player_id = $this->getCurrentPlayerId();
        if ($this->rules->completeDeployment($player_id, json_decode($cards, true))) {
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
        $player_id = $this->getCurrentPlayerId();
        if ($this->rules->chooseForRetrospective($player_id, json_decode($card, true))) {
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
        $this->gamestate->setPlayersMultiactive($overLimitPlayers,$transition);
        $this->gamestate->nextState($transition);
    }


    public function actAdjustPotential(string $cards): void
    {
        $player_id = $this->getCurrentPlayerId();
        $this->gamestate->setPlayerNonMultiactive($player_id, "nextRound");
        $this->rules->updateState($player_id);
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
    protected function zombieTurn(array $state, int $active_player): void
    {
        $state_name = $state["name"];

        if ($state["type"] === "activeplayer") {
            switch ($state_name) {
                default:
                {
                    $this->gamestate->nextState("zombiePass");
                    break;
                }
            }

            return;
        }

        // Make sure player is in a non-blocking status for role turn.
        if ($state["type"] === "multipleactiveplayer") {
            $this->gamestate->setPlayerNonMultiactive($active_player, '');
            return;
        }

        throw new \feException("Zombie mode not supported at this game state: \"{$state_name}\".");
    }

}
