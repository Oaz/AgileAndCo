<script>
    import Card from './Card.svelte';
    import Logo from "./Logo.svelte";
    import CardFooter from "./CardFooter.svelte";
    import CardHeader from "./CardHeader.svelte";
    import {_, Text} from './texts';

    export let title;
    export let description;
    export let activity;
    export let gain;
    export let cost;
    export let score;
</script>

<style>


    :global(.agile-maturity) {
        border-style: solid;
        border-width: 0.1mm;
        border-color: black;
        border-radius: 0;
        background-color: #28286a;
    }

    :global(.agile-maturity) .name {
        margin-top: 8mm;
        font-size: 16pt;
        transform: translateY(-50%);
    }

    :global(.agile-maturity) .description {
        text-align: center;
        position: absolute;
        top: 26mm;
        font-family: "Arial",serif;
        font-size: 9pt;
        padding-left: 1mm;
        padding-right: 1mm;
        transform: translateY(-50%);
    }

    :global(.agile-maturity) :global(.logo) {
        position: absolute;
        top: 43mm;
        left: 2mm;
        width: 10mm;
    }

    :global(.agile-maturity) .gain {
        text-align: center;
        position: absolute;
        top: 48mm;
        left: 14mm;
        font-family: "Arial",serif;
        font-size: 8pt;
        transform: translateY(-50%);
    }
</style>

<Card cardType="agile-maturity">
    <CardHeader slot="header" cardTypeName={_(Text.AGILE_MATURITY)} />
    <div class="name">{title}</div>
    <div class="description">{description}</div>
    <div class="sectionhead">{_(Text.BENEFIT)}</div>
    <Logo key={activity} />
    <div class="gain">{gain}</div>
    <CardFooter slot="footer" {cost} {score} />
</Card>


