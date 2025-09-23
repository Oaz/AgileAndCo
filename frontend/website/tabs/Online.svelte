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

    import enTranslations from './translations/en/online.ftl?raw';
    import frTranslations from './translations/fr/online.ftl?raw';
    import {Localized, Overlay} from "@nubolab-ffwd/svelte-fluent";
    import {initTranslations} from "./translations";
    import SiteButton from "../../shared/SiteButton.svelte";
    import enImage from './450px-PLAYONLINE_ENGLISH_2025.png';
    import frImage from './450px-PLAYONLINE_FRENCH_2025.png';
    import ImageButton from "../../shared/ImageButton.svelte";

    export let language: string;
    initTranslations(language, {
        en: enTranslations,
        fr: frTranslations
    });
    let playButton = language === "fr" ? frImage : enImage;
</script>

<div class="content">
    <p>
        <Localized id="summary"/>
    </p>
    <p>
        <SiteButton id="players-group" action={() => {
        window.open(`https://boardgamearena.com/group?id=19001842`, '_blank');
    }}/>
    </p>
    <ImageButton id="prod-version" image={playButton} action={() => {
        window.open(`https://boardgamearena.com/gamepanel?game=agileandco`, '_blank');
    }}/>
</div>

<style>

    .content {
        white-space: pre-line;
        line-height: 1.6;
        color: var(--text-color, #666);
        text-align: left;
    }

</style>