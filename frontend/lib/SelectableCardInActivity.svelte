<script lang="ts">
    import {Activity} from "./Activities/Activity";
    import Selectable from "./Selectable.svelte";
    import DeckCard from "./Cards/DeckCard.svelte";

    export let activity: Activity;
    export let zone_id: number;
    export let index: number;

    let card_id: number;
    $: card_id = activity.id(zone_id, index);

    export let hidden: boolean = false;
    export let card_key: string;

</script>

<Selectable {hidden}
            bind:interaction={activity.interaction[card_id]}
            bind:selected={activity.selection[card_id]}>
    <DeckCard key={card_key}/>
</Selectable>