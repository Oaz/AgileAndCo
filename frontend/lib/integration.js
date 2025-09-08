import {mount} from 'svelte';
import {BGA} from "./BGA";
import GameBoard from "./GameBoard.svelte";
import PlayerPanel from "./PlayerPanel.svelte";
import {debuglog} from './logger';

export function initBgaIntegration(performAction, translate) {
    BGA.setPerformAction(performAction);
    BGA.setTranslate(translate);
}

export function createGameBoard(target, info) {
    try {
        return mount(GameBoard, {target: target, props: info,});
    } catch (error) {
        console.error('Error initializing GameBoard:', error);
    }
}

export function createPlayerPanel(target, info) {
    try {
        return mount(PlayerPanel, {target: target, props: info,});
    } catch (error) {
        console.error('Error initializing PlayerPanel:', error);
    }
}

export function debug(msg) {
    debuglog(msg);
}