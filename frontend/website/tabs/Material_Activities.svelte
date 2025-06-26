<script lang="ts">

    import enTranslations from './translations/en/material_activities.ftl?raw';
    import frTranslations from './translations/fr/material_activities.ftl?raw';
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
        <WrappedDeckCard key="ACTIVITY_CONFERENCE" />
        <WrappedDeckCard key="ACTIVITY_DEVELOPMENT" />
        <WrappedDeckCard key="ACTIVITY_DEPLOYMENT" />
        <WrappedDeckCard key="ACTIVITY_RETROSPECTIVE" />
        <WrappedDeckCard key="ACTIVITY_COACH" />
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