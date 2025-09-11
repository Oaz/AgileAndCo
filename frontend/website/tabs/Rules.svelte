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

    import enTranslations from './translations/en/rules.ftl?raw';
    import frTranslations from './translations/fr/rules.ftl?raw';
    import {Localized, Overlay} from "@nubolab-ffwd/svelte-fluent";
    import {initTranslations} from "./translations";
    import LocalizedSection from "./LocalizedSection.svelte";
    import LocalizedList from "./LocalizedList.svelte";

    export let language: string;
    initTranslations(language, {
        en: enTranslations,
        fr: frTranslations
    });
</script>

<div class="content">
    <LocalizedSection id="setup" />
    <LocalizedSection id="start-round" />
    <LocalizedSection id="activities" />
    <LocalizedList id="activities-detail" />
    <LocalizedSection id="end-round" />
    <LocalizedSection id="end-game" />
    <LocalizedSection id="addendum1" />
    <LocalizedSection id="addendum2" />
</div>

<style>

    .content {
        white-space: pre-line;
        line-height: 1.6;
        color: var(--text-color, #666);
        text-align: left;
    }

</style>