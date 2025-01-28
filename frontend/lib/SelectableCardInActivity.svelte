<script lang="ts">
    import {SelectionGroup} from "./SelectionGroup";
    import Selectable from "./Selectable.svelte";
    import DeckCard from "./DeckCard.svelte";

    export let group: SelectionGroup;
    export let zone_id: number;
    export let index: number;

    let card_id: number;
    $: card_id = group.id(zone_id, index);

    export let hidden: boolean = false;
    export let card_key: string;

</script>

<Selectable {hidden}
            bind:interaction={group.interaction[card_id]}
            bind:selected={group.selection[card_id]}>
    <DeckCard key={card_key}/>
</Selectable>