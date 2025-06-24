<script lang="ts">
    import {Localized} from '@nubolab-ffwd/svelte-fluent';
    import {initTranslations} from './translations';
    import enTranslations from './translations/en/material.ftl?raw';
    import frTranslations from './translations/fr/material.ftl?raw';
    import Tabs from "../../shared/Tabs.svelte";
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
        label: fluentContext.localize(`${key}-subtab`),
        component,
        props: {language}
    }));

</script>
<div class="material-wrapper">
    <p class="summary">
        <Localized id="intro">
            {#snippet children({ text, attrs })}
                <div class="intro-header">{text}</div>
                <ul>
                    {#each getItemsFromAttrs(attrs) as item}
                        <li>{item}</li>
                    {/each}
                </ul>

            {/snippet}
        </Localized>
    </p>
    <button class="print-button" on:click={openPrintPage}><Localized id="print" /></button>
    <div class="subtabs-container">
        <Tabs bind:activeSubtabValue vertical=true {items}/>
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
    .intro-header {
        text-align: left;
        margin-bottom: 0.5rem;
    }
    ul {
        margin: 0.5rem 0 0 1.5rem;
        padding: 0;
        text-align: left;
    }
    li {
        margin-bottom: 0.5rem;
        text-align: left;
    }
    .print-button {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .print-button:hover {
        background-color: #45a049;
    }

    .subtabs-container {
        position: relative;
        width: 100%;
        margin-top: 20px;
    }
</style>