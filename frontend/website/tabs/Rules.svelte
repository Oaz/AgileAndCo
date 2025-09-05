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