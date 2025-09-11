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
        <WrappedDeckCard key="AGILE_VALUE_FOCUS" scale={scale} />
        <WrappedDeckCard key="AGILE_VALUE_OPENNESS" scale={scale} />
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