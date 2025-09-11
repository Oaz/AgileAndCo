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
    import { push } from 'svelte-spa-router'
    import LanguageToggle from "../shared/LanguageToggle.svelte";
    import {BGA} from "../lib/BGA";
    import {frTranslate} from "../lib/texts-fr";
    import Tabs from "../shared/Tabs.svelte";
    import Home from "./tabs/Home.svelte";
    import Rules from "./tabs/Rules.svelte";
    import Tutorial from "./tabs/Tutorial.svelte";
    import Material from "./tabs/Material.svelte";
    import enTranslations from './tabs/translations/en/tabs.ftl?raw';
    import frTranslations from './tabs/translations/fr/tabs.ftl?raw';
    import {initTranslations} from "./tabs/translations";
    import {getFluentContext} from '@nubolab-ffwd/svelte-fluent';
    import LocalizedTooltip from "../shared/LocalizedTooltip.svelte";


    export let params: { language?: string, tab?: string };

    const supportedLanguages = ["en", "fr"];
    const selectedLanguage = () => {
        if(params?.language)
            return params?.language;
        for (const lang of navigator.languages) {
            const primaryLang = lang.split('-')[0];
            if (supportedLanguages.includes(primaryLang)) {
                return primaryLang;
            }
        }
        return 'en';
    };

    let language = selectedLanguage();
    let activeTabValue = 0;
    let fluentContext = undefined;
    $: {
        initTranslations(language, {
            en: enTranslations,
            fr: frTranslations
        });
        fluentContext = getFluentContext();
    }

    $: items = Object.entries({
        home: Home,
        material: Material,
        rules: Rules,
        tutorial: Tutorial
    }).map(([key, component]) => ({
        key,
        label: fluentContext.localize(`${key}-tab`),
        component,
        props: {language}
    }));

    $: if (params?.tab && items) {
        const tabIndex = items.findIndex(item => item.key === params.tab);
        if (tabIndex !== -1) {
            activeTabValue = tabIndex;
        }
    }

    $: {
        const currentTab = items[activeTabValue].key;
        push(`/${language}/${currentTab}`);
        BGA.setTranslate(language === "fr" ? frTranslate : text => text);
    }

</script>

<main>
    {#key language}
        <div class="banner">
            <img src={`/banner_${language}.jpg`} alt="Banner" />
        </div>
    {/key}
    <div class="tabs-container">
        <LanguageToggle bind:language />
        {#key language}
            <Tabs bind:activeTabValue {items}/>
        {/key}
    </div>
</main>

<footer>
    <div class="footer-content">
        {#key language}
            <LocalizedTooltip textId="legal" tooltipId="legal-host-info" />
        {/key}
    </div>
</footer>


<style>
    main {
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 20px;
    }
    .banner {
        width: 100%;
        max-width: 100%;
    }
    .banner img {
        width: 100%;
        height: auto;
        max-width: 100%;
        display: block;
    }
    .tabs-container {
        position: relative;
        width: 100%;
        margin-top: 20px;
    }
    footer {
        width: 100%;
        background-color: #f5f5f5;
        padding: 15px 0;
        text-align: center;
        border-top: 1px solid #e0e0e0;
        margin-top: 20px;
    }
    footer a {
        color: #666;
        text-decoration: none;
        font-size: 14px;
    }
    footer a:hover {
        text-decoration: underline;
    }


</style>