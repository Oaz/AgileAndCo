<script lang="ts">
    import DeckCard from '../lib/Cards/DeckCard.svelte';

</script>

<div style="zoom: 0.7">
    <div id="content">
        <DeckCard key="LEADER_CARD" />
        <DeckCard key="ACTIVITY_DEVELOPMENT" />
        <DeckCard key="ACTIVITY_DEPLOYMENT" />
        <DeckCard key="ACTIVITY_RETROSPECTIVE" />
        <DeckCard key="ACTIVITY_CONFERENCE" />
        <DeckCard key="ACTIVITY_COACH" />
    </div>
    <div id="content">
        <DeckCard key="EARNINGS_CARD_1" />
        <DeckCard key="EARNINGS_CARD_2" />
        <DeckCard key="EARNINGS_CARD_3" />
        <DeckCard key="EARNINGS_CARD_4" />
        <DeckCard key="PRODUCT_TEAM_ADVERGAME" />
        <DeckCard key="PRODUCT_TEAM_EDUCATION" />
        <DeckCard key="PRODUCT_TEAM_SOCIAL" />
        <DeckCard key="PRODUCT_TEAM_MMOG" />
    </div>
    <div id="content">
        <DeckCard key="AGILE_VALUE_HUMOR" />
        <DeckCard key="AGILE_VALUE_FEEDBACK" />
        <DeckCard key="AGILE_VALUE_SIMPLICITY" />
        <DeckCard key="AGILE_VALUE_TRUST" />
        <DeckCard key="AGILE_VALUE_TRANSPARENCY" />
        <DeckCard key="AGILE_VALUE_COURAGE" />
        <DeckCard key="AGILE_VALUE_RESPECT" />
    </div>
    <div id="content">
        <DeckCard key="AGILE_MATURITY_PASSIONATE_DEVELOPER" />
        <DeckCard key="AGILE_MATURITY_AGILE_PRACTITIONER" />
        <DeckCard key="AGILE_MATURITY_USER_EXPERIENCE" />
        <DeckCard key="AGILE_MATURITY_PAIR_PROGRAMMING" />
        <DeckCard key="AGILE_MATURITY_AGILE_ORGANIZER" />
        <DeckCard key="AGILE_MATURITY_FEEDBACK_SESSIONS" />
        <DeckCard key="AGILE_MATURITY_CLEAN_CODE" />
        <DeckCard key="AGILE_MATURITY_CONTINUOUS_DELIVERY" />
        <DeckCard key="AGILE_MATURITY_DEVOPS" />
    </div>
    <div id="content">
        <DeckCard key="AGILE_MATURITY_AGILE_HR" />
        <DeckCard key="AGILE_MATURITY_ENGAGED_USERS" />
        <DeckCard key="AGILE_MATURITY_INTERNAL_COACH" />
        <DeckCard key="AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP" />
        <DeckCard key="AGILE_MATURITY_AGILE_SENSEI" />
        <DeckCard key="AGILE_MATURITY_PRODUCT_VISION" />
        <DeckCard key="AGILE_MATURITY_DETAILED_PLANNING" />
        <DeckCard key="AGILE_MATURITY_TEST_TEAM" />
        <DeckCard key="AGILE_MATURITY_APPLICATION_FRAMEWORK" />
        <DeckCard key="AGILE_MATURITY_AGILE_CERTIFICATION" />
    </div>
</div>

<style>
    #content {
        display: flex;
        justify-content: center;
    }
</style>