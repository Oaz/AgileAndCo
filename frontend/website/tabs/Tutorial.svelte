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