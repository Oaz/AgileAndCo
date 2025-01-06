<script>
    import Logo from "./Logo.svelte";
    import Card from "./Card.svelte";
    import CardHeader from "./CardHeader.svelte";
    import {_, Text} from './texts';

    export let title;
    export let description;
    export let logo;
    export let privilege;
</script>

<style>

    :global(.activity) {
        border-radius: 0;
        background-color: #987c29;
    }

    :global(.activity) :global(.container) {
        height: 67mm;
    }

    :global(.activity) .name {
        margin-top: 5mm;
        font-size: 16pt;
        transform: translateY(-50%);
    }

    :global(.activity) :global(.logo) {
        position: absolute;
        top: 12mm;
        left: 12mm;
        width: 20mm;
    }

    :global(.activity) .description {
        text-align: center;
        position: absolute;
        top: 39mm;
        font-family: "Arial",serif;
        font-size: 10pt;
        padding-left: 1mm;
        padding-right: 1mm;
        transform: translateY(-50%);
    }

    :global(.activity) :global(.sectionhead) {
        top: 49mm;
    }

    :global(.activity) .privilege {
        text-align: center;
        position: absolute;
        top: 60mm;
        left: 1mm;
        padding-right: 1mm;
        font-family: "Arial",serif;
        font-size: 9pt;
        transform: translateY(-50%);
    }
</style>

<Card cardType="activity">
    <CardHeader slot="header" cardTypeName={_(Text.ACTIVITY)} />
    <div class="name">{title}</div>
    <div class="description">{description}</div>
    <div class="sectionhead">{_(Text.BONUS)}</div>
    <Logo key={logo} />
    <div class="privilege">{privilege}</div>
</Card>



