import {mount} from 'svelte';
import PlayerBoard from "./PlayerBoard.svelte";

export function createPlayerBoard(target, key) {
    try {
        return mount(PlayerBoard, {target: target, props: {key: key},});
    } catch (error) {
        console.error('Error initializing PlayerBoard:', error);
    }
}