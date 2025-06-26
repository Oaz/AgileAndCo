<script lang="ts">

    import { initTranslations } from './translations';
    import enTranslations from './translations/en/material_leader.ftl?raw';
    import frTranslations from './translations/fr/material_leader.ftl?raw';
    import {Localized} from "@nubolab-ffwd/svelte-fluent";
    import WrappedDeckCard from "../../shared/WrappedDeckCard.svelte";
    export let language: string;

    initTranslations(language, {
        en: enTranslations,
        fr: frTranslations
    });
</script>

<div class="deck-container">
    <div class="deck-card">
        <WrappedDeckCard key="LEADER_CARD" />
    </div>
    <div class="description">
        <p class="description-text"><Localized id="description"/></p>
    </div>
</div>

<style>
    .deck-container {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
        padding: 1rem;
        width: 100%;
    }

    .deck-card {
        flex: 0 0 auto;
    }

    .description {
        flex: 1 1 auto; /* Allow growing and shrinking */
        min-width: 0; /* Allows text to shrink below its natural width if needed */
    }

    .description-text {
        white-space: pre-line;
        line-height: 1.6;
        color: var(--text-color, #666);
        text-align: left;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .deck-container {
            flex-direction: column;
            gap: 0.5rem;
        }

        .description {
            width: 100%;
        }
    }



</style>