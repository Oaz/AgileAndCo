<script lang="ts">
    import {cardsData} from "./CardsData";
    import ProductTeamCard from './ProductTeamCard.svelte';
    import AgileMaturityCard from './AgileMaturityCard.svelte';
    import AgileValueCard from './AgileValueCard.svelte';
    import ActivityCard from './ActivityCard.svelte';
    import EarningsCard from './EarningsCard.svelte';
    import LeaderCard from './LeaderCard.svelte';

    export let key;

    const builder = {
        PRODUCT_TEAM: ProductTeamCard,
        AGILE_MATURITY: AgileMaturityCard,
        AGILE_VALUE: AgileValueCard,
        ACTIVITY: ActivityCard,
        EARNINGS: EarningsCard,
        LEADER: LeaderCard,
    };

    let kind = undefined;
    let props = undefined;
    $: ({kind, props} = cardsData()[key] || {});


</script>

{#if builder[kind]}
    <svelte:component this={builder[kind]} {...props}/>
{:else}
    <p>Component with key "{key}" not found.</p>
{/if}


