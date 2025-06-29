<script lang="ts">

    import enTranslations from './translations/en/material.ftl?raw';
    import frTranslations from './translations/fr/material.ftl?raw';
    import {Localized} from "@nubolab-ffwd/svelte-fluent";
    import {initTranslations} from "./translations";
    import WrappedDeckCard from "../../shared/WrappedDeckCard.svelte";

    export let language: string;
    initTranslations(language, {
        en: enTranslations,
        fr: frTranslations
    });
</script>
<div class="card-container">
    <div class="card-row">
        <WrappedDeckCard key="EARNINGS_CARD_1" />
        <WrappedDeckCard key="EARNINGS_CARD_2" />
        <WrappedDeckCard key="EARNINGS_CARD_3" />
        <WrappedDeckCard key="EARNINGS_CARD_4" />
    </div>
    <p class="description-text"><Localized id="deployment-earnings-section.description"/></p>
</div>


<style>
    .card-container {
        width: 100%;
    }

    .card-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        width: 100%;
    }

    .description-text {
        white-space: pre-line;
        line-height: 1.6;
        color: var(--text-color, #666);
        text-align: left;
    }



</style>