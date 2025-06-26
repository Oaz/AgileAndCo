
<script lang="ts">
    import DeckCard from "../lib/Cards/DeckCard.svelte";

    export let key: string;
    export let scale: number = 0.9;
</script>

<div class="card-wrapper" style="--card-scale: {scale}">
    <DeckCard {key} />
</div>

<style>
    .card-wrapper {
        zoom: var(--card-scale);
    }
</style>