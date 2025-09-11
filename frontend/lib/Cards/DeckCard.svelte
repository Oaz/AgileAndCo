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


