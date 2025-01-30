<script lang="ts">
    import {_, Text} from "./texts";
    import {SelectionGroup} from "./SelectionGroup";
    import {BGA} from "./BGA";

    export let group: SelectionGroup;

    let selection_max: number = 0;
    $: if(group.datas.deployment_max) selection_max = group.datas.deployment_max;

    let active: boolean
    $: active = group.selected.length <= selection_max;

    let text: string;
    $: text = active
        ? _(Text.CONFIRM_DEPLOYMENT, group.selected.length.toString())
        : _(Text.CANNOT_DEPLOY, selection_max.toString());

    function action() {
        BGA.performAction('actDeploy', {selected: group.selected});
    }

</script>

{#if group.datas.activity}
    <button on:click={action} class="action {active ? '' : 'inactive'}">{text}</button>
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