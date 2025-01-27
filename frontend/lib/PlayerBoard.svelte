<script>
    import Selectable from "./Selectable.svelte";
    import DeckCard from "./DeckCard.svelte";

    export let datas;
</script>

<div class="board">
    <div class="row">
        <div class="row-title">My Company</div>
        <div class="company">
            <div class="teams">
                {#each datas.teams as team}
                    <Selectable>
                        <DeckCard key={team[0]}/>
                    </Selectable>
                    {#if team[1]}
                        <Selectable hidden=true>
                            <DeckCard key={team[1]}/>
                        </Selectable>
                    {/if}
                {/each}
            </div>
            <div class="other">
                {#each datas.company as card_key}
                    <Selectable>
                        <DeckCard key={card_key}/>
                    </Selectable>
                {/each}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="row-title">My Potential</div>
        <div class="potential">
            {#each datas.potential as card_key}
                <Selectable>
                    <DeckCard key={card_key}/>
                </Selectable>
            {/each}
        </div>
    </div>
</div>

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