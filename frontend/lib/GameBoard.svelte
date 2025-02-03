<script lang="ts">
    import CentralPanel from "./CentralPanel.svelte";
    import PlayerBoard from "./PlayerBoard.svelte";
    import OtherPlayerBoard from "./OtherPlayerBoard.svelte";

    export let datas;

    function update(new_data) {
        console.log(new_data);
    }

</script>


<div class="board">
    <CentralPanel datas={datas.central}/>
    <PlayerBoard datas={datas.myself}/>
    <div class="others">
        {#each datas.others as player, index}
            <OtherPlayerBoard datas={player}/>
        {/each}
    </div>
</div>


<style>

    .board {
    }

    .others {
        margin-top: 30px;
    }
</style>