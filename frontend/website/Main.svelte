<script lang="ts">
    import { push } from 'svelte-spa-router'
    import Banner from "../shared/Banner.svelte";
    import LanguageToggle from "../shared/LanguageToggle.svelte";
    import {BGA} from "../lib/BGA";
    import {frTranslate} from "../lib/texts-fr";
    import Tabs from "../shared/Tabs.svelte";
    import Home from "./tabs/Home.svelte";
    import Rules from "./tabs/Rules.svelte";
    import Tutorial from "./tabs/Tutorial.svelte";
    import PrintCards from "./tabs/PrintCards.svelte";
    import Material from "./tabs/Material.svelte";
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
    $: items = [
            {key:'home', label: "Home", component: Home, props: { language }},
            {key:'material', label: "Material", component: Material, props: { language }},
            {key:'print', label: "Print Cards", component: PrintCards, props: { language }},
            {key:'rules', label: "Rules", component: Rules, props: { language }},
            {key:'tutorial', label: "Tutorial", component: Tutorial, props: { language }},
        ];

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
        <Banner />
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

    .tabs-container {
        position: relative;
        width: 100%;
        margin-top: 20px;
    }
</style>