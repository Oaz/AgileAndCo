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

    import enTranslations from './translations/en/tutorial.ftl?raw';
    import frTranslations from './translations/fr/tutorial.ftl?raw';
    import {Localized, Overlay} from "@nubolab-ffwd/svelte-fluent";
    import {initTranslations} from "./translations";
    import LocalizedSection from "./LocalizedSection.svelte";
    import LocalizedList from "./LocalizedList.svelte";
    import SiteButton from "../../shared/SiteButton.svelte";

    export let language: string;
    initTranslations(language, {
        en: enTranslations,
        fr: frTranslations
    });
</script>

<div class="content">
    <LocalizedSection id="tutorial-purpose" />
    <LocalizedSection id="tutorial-setup" />
    <SiteButton id="download-gameboards" action={() => {
        window.open(`board_${language}.pdf`, '_blank');
    }} />
    <LocalizedSection id="tutorial-flow" />
    <LocalizedSection id="conference-activity" />
    <LocalizedSection id="development-activity" />
    <LocalizedSection id="deployment-activity" />
    <LocalizedSection id="retrospective-activity" />
    <LocalizedSection id="end-tutorial" />
    <LocalizedSection id="tips" />
</div>


<style>

    .content {
        white-space: pre-line;
        line-height: 1.6;
        color: var(--text-color, #666);
        text-align: left;
    }

</style>