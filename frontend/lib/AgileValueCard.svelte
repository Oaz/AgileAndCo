<script>
    import {_, Text} from './texts';
    import Card from './Card.svelte';
    import Logo from "./Logo.svelte";
    import CardFooter from "./CardFooter.svelte";
    import CardHeader from "./CardHeader.svelte";

    export let title;
    export let description;
</script>

<style>


    :global(.agile-value) {
        border-style: solid;
        border-width: 0.1mm;
        border-color: black;
        border-radius: 0;
        background-color: #1d6b41;
    }

    :global(.agile-value) .name {
        margin-top: 8mm;
        font-size: 16pt;
        transform: translateY(-50%);
    }

    :global(.agile-value) .description {
        text-align: center;
        position: absolute;
        top: 24mm;
        font-family: "Arial",serif;
        font-size: 9pt;
        padding-left: 4mm;
        padding-right: 4mm;
        transform: translateY(-50%);
    }

    :global(.agile-value) :global(.logo) {
        position: absolute;
        top: 43mm;
        left: 2mm;
        width: 10mm;
    }

    :global(.agile-value) .gain {
        position: absolute;
        top: 48mm;
        left: 14mm;
        padding-right: 1mm;
        font-family: "Arial",serif;
        font-size: 8pt;
        transform: translateY(-50%);
    }
</style>

<Card cardType="agile-value">
    <CardHeader slot="header" cardTypeName={_(Text.AGILE_VALUE)} />
    <div class="name">{title}</div>
    <div class="description">{description}</div>
    <div class="sectionhead">{_(Text.BENEFIT)}</div>
    <Logo key="LEADER" />
    <div class="gain">{_(Text.AGILE_VALUE_GAIN)}</div>
    <CardFooter slot="footer" cost="4" score="n" />
</Card>


