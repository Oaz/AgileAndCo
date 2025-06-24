<script lang="ts">

    import enTranslations from './translations/en/material_earnings.ftl?raw';
    import frTranslations from './translations/fr/material_earnings.ftl?raw';
    import DeckCard from "../../lib/Cards/DeckCard.svelte";
    import {Localized} from "@nubolab-ffwd/svelte-fluent";
    import {initTranslations} from "./translations";

    export let language: string;
    initTranslations(language, {
        en: enTranslations,
        fr: frTranslations
    });
</script>
<div class="card-container">
    <div class="card-row">
        <DeckCard key="AGILE_MATURITY_AGILE_PRACTITIONER" />
        <DeckCard key="AGILE_MATURITY_AGILE_ORGANIZER" />
    </div>
    <p class="description-text"><Localized id="description"/></p>
</div>


<style>
    .card-container {
        width: 100%;
    }

    .card-row {
        display: flex;
        gap: 0.5rem;
        transform: scale(0.9);
        transform-origin: left top;
        width: 200%; /* Give more room for the cards */
    }

    .description-text {
        white-space: pre-line;
        line-height: 1.6;
        color: var(--text-color, #666);
        text-align: left;
    }



</style>