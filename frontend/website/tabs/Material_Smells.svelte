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
        <WrappedDeckCard key="AGILE_MATURITY_DETAILED_PLANNING" />
        <WrappedDeckCard key="AGILE_MATURITY_TEST_TEAM" />
        <WrappedDeckCard key="AGILE_MATURITY_APPLICATION_FRAMEWORK" />
        <WrappedDeckCard key="AGILE_MATURITY_AGILE_CERTIFICATION" />
    </div>
    <p class="description-text"><Localized id="smells-section.description"/></p>
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