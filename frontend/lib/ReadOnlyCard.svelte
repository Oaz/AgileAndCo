<script lang="ts">
    import Selectable from "./Selectable.svelte";
    import DeckCard from "./Cards/DeckCard.svelte";

    export let hidden: boolean = false;
    export let key: string;

</script>

<Selectable {hidden}>
    <DeckCard {key} />
</Selectable>
