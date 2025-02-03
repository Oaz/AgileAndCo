import {mount} from 'svelte';
import {BGA} from "./BGA";
import GameBoard from "./GameBoard.svelte";

export function initBgaIntegration(performAction, translate) {
    BGA.setPerformAction(performAction);
    BGA.setTranslate(translate);
}

export function createGameBoard(target, info) {
    try {
        return mount(GameBoard, {target: target, props: {},});
    } catch (error) {
        console.error('Error initializing GameBoard:', error);
    }
}