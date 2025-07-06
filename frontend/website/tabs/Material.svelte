<script lang="ts">
    import {initTranslations} from './translations';
    import enTranslations from './translations/en/material.ftl?raw';
    import frTranslations from './translations/fr/material.ftl?raw';
    import Scroll from "../../shared/Scroll.svelte";
    import Leader from "./Material_Leader.svelte";
    import Activities from "./Material_Activities.svelte";
    import Teams from "./Material_Teams.svelte";
    import ConferenceMaturity from "./Material_ConferenceMaturity.svelte";
    import DevelopmentMaturity from "./Material_DevelopmentMaturity.svelte";
    import DeploymentMaturity from "./Material_DeploymentMaturity.svelte";
    import Earnings from "./Material_Earnings.svelte";
    import RetrospectiveMaturity from "./Material_RetrospectiveMaturity.svelte";
    import LongTermMaturity from "./Material_LongTermGoals.svelte";
    import Smells from "./Material_Smells.svelte";
    import Values from "./Material_Values.svelte";
    import { getFluentContext } from '@nubolab-ffwd/svelte-fluent';
    import LocalizedList from "./LocalizedList.svelte";
    import SiteButton from "../../shared/SiteButton.svelte";

    export let language: string;
    let activeSubtabValue = 0;
    let fluentContext = undefined;

    function getItemsFromAttrs(attrs: Record<string, string>): string[] {
        return Object.entries(attrs)
            .filter(([key]) => key.startsWith('item'))
            .sort(([a], [b]) => a.localeCompare(b))
            .map(([_, value]) => value);
    }

    function openPrintPage() {
        window.open(`print.html?lang=${language}`, '_blank');
    }

    $: {
        initTranslations(language, {
            en: enTranslations,
            fr: frTranslations
        });
        fluentContext = getFluentContext();
    }

    $: items = Object.entries({
        leader: Leader,
        activities: Activities,
        teams: Teams,
        'conference-maturity': ConferenceMaturity,
        'development-maturity': DevelopmentMaturity,
        'deployment-maturity': DeploymentMaturity,
        'deployment-earnings': Earnings,
        'retrospective-maturity': RetrospectiveMaturity,
        'long-term-goals': LongTermMaturity,
        smells: Smells,
        values: Values,
    }).map(([key, component]) => ({
        key,
        label: fluentContext.localize(`${key}-section`),
        component,
        props: {language}
    }));

</script>
<div class="material-wrapper">
    <p class="summary">
        <LocalizedList id="intro" />
    </p>
    <SiteButton id="print" action={() => {
        window.open(`print.html?lang=${language}`, '_blank');
    }} />
    <SiteButton id="box" action={() => {
        window.open(`box_${language}.pdf`, '_blank');
    }} />
    <div class="subtabs-container">
        <Scroll items={items.map(item => ({ label: item.label, anchorId: item.key }))}>
            <div>
                {#each items as item}
                    <section id={item.key}>
                        <h2>{item.label}</h2>
                        <svelte:component this={item.component} {...item.props} />
                    </section>
                {/each}

            </div>
        </Scroll>
    </div>
</div>

<style>
    .material-wrapper {
        padding: 0.5rem;
    }
    .summary {
        font-size: 1.2rem;
        line-height: 1.6;
        color: var(--text-color, #666);
        margin-bottom: 2rem;
        text-align: left;
    }

    .subtabs-container {
        position: relative;
        width: 100%;
        margin-top: 20px;
    }
</style>