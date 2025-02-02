<script lang="ts">
    import ReadOnlyCard from "./ReadOnlyCard.svelte";

    export let datas;

</script>

{#key datas}
    <div class="board">
        <div class="title">{datas.name}</div>
        <div class="company">
            <div class="teams">
                {#each datas.teams as team, index}
                    <ReadOnlyCard key={team[0]}/>
                    {#if team[1]}
                        <ReadOnlyCard key={team[1]} hidden={true}/>
                    {/if}
                {/each}
            </div>
            <div class="other">
                {#each datas.company as key, index}
                    <ReadOnlyCard {key}/>
                {/each}
            </div>
        </div>
    </div>
{/key}

<style>
    .company {
        display: flex;
        flex-flow: row wrap;
        gap: 50px;
    }

    .title {
        text-align: left;
        font-family: "Arial", serif;
        font-size: 18pt;
        padding-left: 5px;
    }

    .board {
        zoom: 0.6;
        border-style: solid;
        border-width: 2px;
        border-color: #565656;
    }
</style>