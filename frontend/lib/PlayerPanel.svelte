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
        debuglog(`PANEL init player=${player_id} read_only=${read_only}`, gamedatas);
    }

    let potentialSize: number = undefined;
    export async function update_public(playerData) {
        await mounted;
        debuglog('PANEL update_public', playerData);
        potentialSize = playerData.potentialSize;
    }
</script>

<div class="panel">
🎴{potentialSize}
</div>

<style>
</style>