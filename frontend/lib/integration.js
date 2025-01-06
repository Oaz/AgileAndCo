import {mount} from 'svelte';
import DeckCard from "./DeckCard.svelte";

export function createDeckCard(target, key) {
    try {
        return mount(DeckCard, {target: target, props: {key: key},});
    } catch (error) {
        console.error('Error initializing DeckCard:', error);
    }
}