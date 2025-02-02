<script lang="ts">
    import {cardsData} from "./CardsData";

    export let key;

    let component = undefined;
    let props = undefined;
    $: ({component, props} = cardsData()[key] || {});


</script>

{#if component}
    <svelte:component this={component} {...props}/>
{:else}
    <p>Component with key "{key}" not found.</p>
{/if}


