<script lang="ts">
    import {SelectionGroup} from "./SelectionGroup";

    export let group: SelectionGroup;

</script>

{#if group.datas.activity}
    <button>Confirm deployment of {group.selection_count} products</button>
{/if}