<script lang="ts">

    import enTranslations from './translations/en/material_earnings.ftl?raw';
    import frTranslations from './translations/fr/material_earnings.ftl?raw';
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
        <WrappedDeckCard key="AGILE_MATURITY_USER_EXPERIENCE" />
        <WrappedDeckCard key="AGILE_MATURITY_CONTINUOUS_DELIVERY" />
        <WrappedDeckCard key="AGILE_MATURITY_ENGAGED_USERS" />
    </div>
    <p class="description-text"><Localized id="description"/></p>
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