<script lang="ts">
    import CentralPanel from "./CentralPanel.svelte";
    import PlayerBoard from "./PlayerBoard.svelte";
    import OtherPlayerBoard from "./OtherPlayerBoard.svelte";
    import {onMount} from "svelte";
    import {debuglog} from './logger';

    let resolveMounted;
    const mounted = new Promise((resolve) => {
        resolveMounted = resolve;
    });
    onMount(() => {
        resolveMounted();
    });

    export let gamedatas: any = undefined;
    export let read_only: boolean = true;
    export let player_id: number = undefined;
    $: {
        debuglog(`GAMEBOARD init player=${player_id} read_only=${read_only}`, gamedatas);
    }

    let public_data: any = undefined;

    export async function update_public(data) {
        await mounted;
        debuglog('GAMEBOARD update_public', data);
        if (data.active_player && data.active_player == player_id)
            data.central.selection = true;
        public_data = data;
    }

    let private_data: any = undefined;

    export async function update_private(data) {
        await mounted;
        debuglog('GAMEBOARD update_private', data);
        private_data = data;
    }

</script>


<div class="board">
    {#if public_data}
        <CentralPanel datas={public_data.central}/>
    {/if}
    {#if private_data}
        <PlayerBoard datas={private_data}/>
    {/if}
    {#if public_data}
        <div class="others">
            {#each Object.entries(public_data.players) as [id,player]}
                {#if parseInt(id, 10) !== player_id}
                    <OtherPlayerBoard datas={player}/>
                {/if}
            {/each}
        </div>
    {/if}
</div>


<style>

    .board {
    }

    .others {
        margin-top: 30px;
    }
</style>