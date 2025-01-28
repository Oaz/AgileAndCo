<script lang="ts">
    import {_, Text} from "./texts";
    import {SelectionGroup} from "./SelectionGroup";
    import SelectableCardInActivity from "./SelectableCardInActivity.svelte";
    import ActivityAction from "./ActivityAction.svelte";

    export let datas;

    let group: SelectionGroup;
    $: group = new SelectionGroup(datas);
</script>

{#key datas}
    <div class="board">
        <ActivityAction bind:group={group} />
        <div class="row">
            <div class="row-title">{_(Text.MY_COMPANY)}</div>
            <div class="company">
                <div class="teams">
                    {#each datas.teams as team, index}
                        <SelectableCardInActivity bind:group={group} zone_id={0} {index} card_key={team[0]}/>
                        {#if team[1]}
                            <SelectableCardInActivity bind:group={group} zone_id={1} {index} card_key={team[1]} hidden={true}/>
                        {/if}
                    {/each}
                </div>
                <div class="other">
                    {#each datas.company as card_key, index}
                        <SelectableCardInActivity bind:group={group} zone_id={2} {index} {card_key}/>
                    {/each}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-title">{_(Text.MY_POTENTIAL)}</div>
            <div class="potential">
                {#each datas.potential as card_key, index}
                    <SelectableCardInActivity bind:group={group} zone_id={3} {index} {card_key}/>
                {/each}
            </div>
        </div>
    </div>
{/key}

<style>
    .row {
        border-style: solid;
        border-width: 2px;
        border-color: #565656;
    }

    .company {
        display: flex;
        flex-flow: row wrap;
        gap: 50px;
    }

    .potential {
        display: flex;
        flex-flow: row wrap;
    }

    .row-title {
        text-align: left;
        font-family: "Arial", serif;
        font-size: 18pt;
        padding-left: 5px;
    }

    .board {
        zoom: 0.8;
    }
</style>