<script lang="ts">
    import Tabs from "./Tabs.svelte";
    import AllCards from "./AllCards.svelte";
    import CardSelection from "./CardSelection.svelte";
    import PlayerBoardTester from "./PlayerBoardTester.svelte";
    import ActivityDeployment from "./ActivityDeployment.svelte";

    let items = [
        // {label: "Game Board", component: GameBoardTester},
        // {label: "Activity: Retrospective", component: ActivityRetrospective},
        // {label: "Activity: Development", component: ActivityDevelopment},
        // {label: "Activity: Conference", component: ActivityConference},
        {label: "Activity: Deployment", component: ActivityDeployment},
        {label: "Player Board", component: PlayerBoardTester},
        {label: "Card Selection", component: CardSelection},
        {label: "All Cards", component: AllCards}
    ];
</script>

<main>
    <h1>Agile&Co mock-ups</h1>
    <Tabs activeTabValue="0" {items}/>
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