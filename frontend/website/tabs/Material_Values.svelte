<script lang="ts">

    import enTranslations from './translations/en/material.ftl?raw';
    import frTranslations from './translations/fr/material.ftl?raw';
    import WrappedDeckCard from "../../shared/WrappedDeckCard.svelte";
    import {Localized} from "@nubolab-ffwd/svelte-fluent";
    import {initTranslations} from "./translations";

    export let language: string;
    initTranslations(language, {
        en: enTranslations,
        fr: frTranslations
    });
    let scale = 0.64
</script>
<div class="card-container">
    <div class="card-row">
        <WrappedDeckCard key="AGILE_VALUE_HUMOR" scale={scale} />
        <WrappedDeckCard key="AGILE_VALUE_FEEDBACK" scale={scale} />
        <WrappedDeckCard key="AGILE_VALUE_SIMPLICITY" scale={scale} />
        <WrappedDeckCard key="AGILE_VALUE_TRUST" scale={scale} />
        <WrappedDeckCard key="AGILE_VALUE_TRANSPARENCY" scale={scale} />
        <WrappedDeckCard key="AGILE_VALUE_COURAGE" scale={scale} />
        <WrappedDeckCard key="AGILE_VALUE_RESPECT" scale={scale} />
    </div>
    <p class="description-text"><Localized id="values-section.description"/></p>
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