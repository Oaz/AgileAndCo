<script lang="ts">
    import ActionButton from "./ActionButton.svelte";
    import ReadOnlyCard from "./ReadOnlyCard.svelte";
    import Selectable from "./Selectable.svelte";
    import DeckCard from "./Cards/DeckCard.svelte";
    import {ActivitySelection} from "./Activities/ActivitySelection";

    export let datas;

    let action: ActivitySelection;
    $: action = new ActivitySelection(datas);

</script>

{#key datas}
    <div class="panel">
        <div class="row">
            <div class="activities">
                {#each datas.activities as activity, index}
                    <Selectable
                            bind:interaction={action.interaction[index]}
                            bind:selected={action.selection[index]}
                            hidden={activity[1]}>
                        <DeckCard key={activity[0]}/>
                    </Selectable>
                {/each}
            </div>
            <div class="misc">
                <ReadOnlyCard key={datas.earnings[0]} hidden={datas.earnings[1]}/>
            </div>
        </div>
        <div class="footer">
            <ActionButton {action}/>
        </div>
    </div>
{/key}

<style>
    .row {
        border-style: solid;
        border-width: 2px;
        border-color: #565656;
        display: flex;
        flex-flow: row wrap;
        gap: 50px;
    }

    .panel {
        zoom: 0.8;
    }
</style>