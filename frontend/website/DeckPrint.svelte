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
    import DeckCard from '../lib/Cards/DeckCard.svelte';
    import DeckPrintFooter from './DeckPrintFooter.svelte';

    const quantities = {
        'LEADER_CARD':1,
        'ACTIVITY_CONFERENCE':1,
        'ACTIVITY_DEVELOPMENT':1,
        'ACTIVITY_DEPLOYMENT':1,
        'ACTIVITY_RETROSPECTIVE':1,
        'ACTIVITY_COACH':1,
        'EARNINGS_CARD_1':1,
        'EARNINGS_CARD_2':1,
        'EARNINGS_CARD_3':1,
        'EARNINGS_CARD_4':1,
        'PRODUCT_TEAM_ADVERGAME': 10,
        'PRODUCT_TEAM_EDUCATION': 7,
        'PRODUCT_TEAM_SOCIAL': 7,
        'PRODUCT_TEAM_MMOG': 7,
        'AGILE_MATURITY_PASSIONATE_DEVELOPER':3,
        'AGILE_MATURITY_AGILE_PRACTITIONER':3,
        'AGILE_MATURITY_USER_EXPERIENCE':3,
        'AGILE_MATURITY_PAIR_PROGRAMMING':3,
        'AGILE_MATURITY_AGILE_ORGANIZER':3,
        'AGILE_MATURITY_FEEDBACK_SESSIONS':3,
        'AGILE_MATURITY_CLEAN_CODE':3,
        'AGILE_MATURITY_CONTINUOUS_DELIVERY':3,
        'AGILE_MATURITY_DEVOPS':3,
        'AGILE_MATURITY_AGILE_HR':3,
        'AGILE_MATURITY_ENGAGED_USERS':3,
        'AGILE_MATURITY_INTERNAL_COACH':3,
        'AGILE_MATURITY_DETAILED_PLANNING':3,
        'AGILE_MATURITY_TEST_TEAM':3,
        'AGILE_MATURITY_APPLICATION_FRAMEWORK':3,
        'AGILE_MATURITY_AGILE_CERTIFICATION':3,
        'AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP':2,
        'AGILE_MATURITY_AGILE_SENSEI':2,
        'AGILE_MATURITY_PRODUCT_VISION':2,
        'AGILE_VALUE_HUMOR':1,
        'AGILE_VALUE_FEEDBACK':2,
        'AGILE_VALUE_SIMPLICITY':2,
        'AGILE_VALUE_FOCUS':2,
        'AGILE_VALUE_OPENNESS':2,
        'AGILE_VALUE_COURAGE':2,
        'AGILE_VALUE_RESPECT':2,
    }

    const allCards
        = Object.entries(quantities).flatMap(([cardType, quantity]) =>
        Array(quantity).fill(cardType)
    );

    const cardsByPage = 10;

    const cardPages = [];
    for (let i = 0; i < allCards.length; i += cardsByPage) {
        cardPages.push(allCards.slice(i, i + cardsByPage));
    }

</script>

{#each cardPages as page, pageIndex}
    <div class="card-container">
        {#each page as card}
            <DeckCard key={card} />
        {/each}
    </div>
    <DeckPrintFooter />
{/each}


<style>
    .card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    :global(.deck-card) {
        flex: 0 0 20%; /* 5 cards per row */
        box-sizing: border-box;
        padding: 1mm;
    }


    @page {
        size: landscape;
        margin: 10mm;
    }

    @media print {
        :global(body) {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

</style>