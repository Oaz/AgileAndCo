<script lang="ts">
    import Tabs from "./Tabs.svelte";
    import AllCards from "./AllCards.svelte";
    import CardSelection from "./CardSelection.svelte";
    import PlayerBoardTester from "./PlayerBoardTester.svelte";

    let items = [
        { label: "Player Board",
            value: 1,
            component: PlayerBoardTester
        },
        { label: "Card Selection",
            value: 2,
            component: CardSelection
        },
        { label: "All Cards",
            value: 3,
            component: AllCards
        }
    ];
</script>

<main>
    <h1>Agile&Co mock-ups</h1>
    <Tabs activeTabValue="1" {items} />
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