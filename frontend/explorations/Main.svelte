<script lang="ts">
    import Tabs from "./Tabs.svelte";
    import AllCards from "./AllCards.svelte";
    import CardSelection from "./CardSelection.svelte";

    let items = [
        { label: "Card Selection",
            value: 1000,
            component: CardSelection
        },
        { label: "All Cards",
            value: 2000,
            component: AllCards
        }
    ];
</script>

<main style="zoom: 1">
    <h1>Agile&Co mock-ups</h1>
    <Tabs activeTabValue="1000" {items} />
</main>

<style>
    main {
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 20px;
    }

    h1 {
        margin-bottom: 20px;
    }
</style>