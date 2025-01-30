<script lang="ts">
    import {Activity} from "./Activities/Activity";

    export let activity: Activity;

</script>

{#if activity.datas.activity}
    <button on:click={function (){
        activity.do_act();
    }} class="action {activity.can_act ? '' : 'inactive'}">
        {activity.action_text}
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