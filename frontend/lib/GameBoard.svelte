<!--
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

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