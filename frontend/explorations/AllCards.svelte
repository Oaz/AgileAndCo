<script lang="ts">
    import DeckCard from '../lib/DeckCard.svelte';

</script>

<div>
    <div id="content">
        <DeckCard key="PRODUCT_TEAM_ADVERGAME" />
        <DeckCard key="PRODUCT_TEAM_EDUCATION" />
        <DeckCard key="PRODUCT_TEAM_SOCIAL" />
        <DeckCard key="PRODUCT_TEAM_MMOG" />
    </div>
    <div id="content">
        <DeckCard key="AGILE_MATURITY_PASSIONATE_DEVELOPER" />
        <DeckCard key="AGILE_MATURITY_AGILE_PRACTITIONER" />
        <DeckCard key="AGILE_MATURITY_USER_EXPERIENCE" />
        <DeckCard key="AGILE_MATURITY_PAIR_PROGRAMMING" />
        <DeckCard key="AGILE_MATURITY_PRODUCT_VISION" />
    </div>
    <div id="content">
        <DeckCard key="AGILE_VALUE_SIMPLICITY" />
        <DeckCard key="AGILE_VALUE_TRANSPARENCY" />
        <DeckCard key="AGILE_VALUE_COURAGE" />
    </div>
    <div id="content">
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
    </div>
    <div id="content">
        <DeckCard key="LEADER_CARD" />
    </div>
</div>

<style>
    #content {
        display: flex;
        justify-content: center;
    }
</style>