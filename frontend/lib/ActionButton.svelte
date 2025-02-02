<script lang="ts">
    import {Action} from "./Action";

    export let action: Action;

</script>

{#if action.enabled}
    <button on:click={function (){
        action.do_act();
    }} class="action {action.can_act ? '' : 'inactive'}">
        {action.action_text}
    </button>
{/if}

<style>
    .action {
        font-size: 18pt;
    }

    .inactive {
        pointer-events: none;
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>