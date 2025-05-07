<script lang="ts">
    import {onMount} from "svelte";

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
        console.log(`PANEL init player=${player_id} read_only=${read_only}`, gamedatas);
    }

    let potentialSize: number = undefined;
    export async function update_public(playerData) {
        await mounted;
        console.log('PANEL update_public', playerData);
        potentialSize = playerData.potentialSize;
    }
</script>

<div class="panel">
🎴{potentialSize}
</div>

<style>
</style>