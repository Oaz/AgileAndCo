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
    import { getFluentContext } from '@nubolab-ffwd/svelte-fluent';

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
</style>