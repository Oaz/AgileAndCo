<script lang="ts">
    import {_, Text} from "./texts";
    import Card from './Card.svelte';
    import Logo from "./Logo.svelte";
    import CardFooter from "./CardFooter.svelte";
    import CardHeader from "./CardHeader.svelte";

    export let title;
    export let description;
    export let cost;
    export let score;
</script>

<style>

    :global(.product-team) {
        border-style: solid;
        border-width: 0.1mm;
        border-color: black;
        border-radius: 0;
        background-color: #6b1d5f;
    }

    :global(.product-team) .name {
        margin-top: 5mm;
        font-size: 18pt;
        height: 10mm;
    }

    :global(.product-team)  :global(.logo) {
        position: absolute;
        top: 15mm;
        left: 12mm;
        width: 20mm;
    }

    :global(.product-team) .description {
        text-align: center;
        margin-top: 31mm;
        font-family: "Arial",serif;
        font-size: 16pt;
        transform: translateY(-50%);
        padding-left: 1mm;
        padding-right: 1mm;
    }
</style>

<Card cardType="product-team">
    <CardHeader slot="header" cardTypeName={_(Text.PRODUCT_TEAM)} />
    <div class="name">{title}</div>
    <Logo key="{title}" />
    <div class="description">{description}</div>
    <CardFooter slot="footer" {cost} {score} />
</Card>


