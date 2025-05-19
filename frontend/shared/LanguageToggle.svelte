<script lang="ts">
    import {BGA} from "../lib/BGA";
    import {frTranslate} from "../lib/texts-fr";
    import {onMount} from "svelte";

    let supportedLanguages = ["en", "fr"];
    const detectBrowserLanguage = () => {
        for (const lang of navigator.languages) {
            const primaryLang = lang.split('-')[0];
            if (supportedLanguages.includes(primaryLang)) {
                return primaryLang;
            }
        }
        return 'en';
    };

    export let language = "";

    function setLanguage(newLanguage: string) {
        language = newLanguage;
        BGA.setTranslate(language === "fr" ? frTranslate : text => text);
    }

    onMount(() => {
        setLanguage(detectBrowserLanguage());
    })
</script>

<div class="language-toggle">
    <button class:active={language === "en"} on:click={() => setLanguage('en')}>EN</button>
    <button class:active={language === "fr"} on:click={() => setLanguage('fr')}>FR</button>
</div>

<style>
    .language-toggle {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 1;
    }

    .language-toggle button {
        padding: 5px 10px;
        margin: 0 2px;
        border: 1px solid #ccc;
        background: white;
        cursor: pointer;
        border-radius: 3px;
    }

    .language-toggle button.active {
        background: #007bff;
        color: white;
        border-color: #0056b3;
    }
</style>