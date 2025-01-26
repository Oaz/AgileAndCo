/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * AgileAndCo implementation : © Olivier Azeau
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * agileandco.js
 *
 * AgileAndCo user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo", "dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    g_gamethemeurl + "modules/frontend.js",
],
function (dojo, declare, gamegui, counter, frontend) {
    return declare("bgagame.agileandco", ebg.core.gamegui, {
        constructor: function(){
            console.log('agileandco constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */

        setup: async function (gamedatas) {
            console.log("Starting game setup");

            const gamePlayAreaContainer = document.createElement('div');
            document
                .getElementById('game_play_area')
                .insertAdjacentElement('beforeend', gamePlayAreaContainer);
            this.gamePlayAreaComponent = frontend.initGamePlayArea(gamePlayAreaContainer, {
                gamedatas,
                bgaPerformAction: (action, args) => this.bgaPerformAction(action, args)
            });

            /*
            this.playerPanels = {}
            Object.values(gamedatas.players).forEach(player => {
                const playerPanelContainer = document.createElement('div');
                this.getPlayerPanelElement(player.id)
                    .insertAdjacentElement('beforeend', playerPanelContainer);
                this.playerPanels[player.id] = frontend.initPlayerPanel(playerPanelContainer, {
                    id:player.id, gamedatas
                });
            });
            */

            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            console.log("Ending game setup");
        },
       

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: async function (stateName, args) {
            console.log('Entering state: ' + stateName, args);
            await this.gamePlayAreaComponent.enterState(stateName, args);
            for (const player_id in this.playerPanels) {
                await this.playerPanels[player_id].enterState(stateName, args);
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function (stateName) {
            console.log('Leaving state: ' + stateName);
        },

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function (stateName, args) {
            console.log('onUpdateActionButtons: ' + stateName, args);
        },

        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your agileandco.game.php file.
        
        */
        setupNotifications: function () {
            console.log('notifications subscriptions setup');
            this.bgaSetupPromiseNotifications();
        },

        // TODO: from this point and below, you can write your game notifications handling methods

        notif_activityChoice: async function (args) {
            await this.gamePlayAreaComponent.activityChoice(args);
        },

        notif_coachGivesPotential: async function (args) {
            await this.gamePlayAreaComponent.coachGivesPotential(args, this.bgaAnimationsActive());
        },

        notif_newScores: async function (args) {
            await this.gamePlayAreaComponent.newScores(args);
            for (const player_id in args.scores) {
                this.scoreCtrl[player_id].toValue(args.scores[player_id]);
            }
        },
   });             
});
