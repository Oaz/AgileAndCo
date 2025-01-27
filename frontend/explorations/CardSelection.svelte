<script lang="ts">
    import DeckCard from '../lib/DeckCard.svelte';
    import SelectableTester from './SelectableTester.svelte';

</script>

<div>
    <div id="content">
        <SelectableTester selectedInteraction = "ACTIVE">
            <DeckCard key="PRODUCT_TEAM_ADVERGAME" />
        </SelectableTester>
        <SelectableTester selectedInteraction = "FROZEN">
            <DeckCard key="AGILE_MATURITY_AGILE_PRACTITIONER" />
        </SelectableTester>
        <SelectableTester selectedState = "ACTIVE" hidden="1">
            <DeckCard key="ACTIVITY_RETROSPECTIVE" />
        </SelectableTester>
    </div>
</div>

<style>
    main {
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 20px;
    }

    #content {
        display: flex;
        justify-content: center;
    }

</style>