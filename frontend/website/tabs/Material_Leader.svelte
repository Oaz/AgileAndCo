<!--
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

<script lang="ts">

    import { initTranslations } from './translations';
    import enTranslations from './translations/en/material.ftl?raw';
    import frTranslations from './translations/fr/material.ftl?raw';
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
        <p class="description-text"><Localized id="leader-section.description"/></p>
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