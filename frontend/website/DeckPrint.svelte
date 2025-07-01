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

    const cards = Object.entries(quantities).flatMap(([cardType, quantity]) =>
        Array(quantity).fill(cardType)
    );

    const cardsByPage = 10;

</script>

{#each cards as card, index}
    <DeckCard key={card} />
    {#if (index + 1) % cardsByPage === 0 && index < cards.length}
        <DeckPrintFooter />
    {/if}
{/each}
<DeckPrintFooter />

<style>
    :global(.card) {
        margin: 1mm;
        display: inline-block;
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