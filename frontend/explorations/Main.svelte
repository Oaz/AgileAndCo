<script lang="ts">
    import Banner from "../shared/Banner.svelte";
    import LanguageToggle from "../shared/LanguageToggle.svelte";
    import Tabs from "./Tabs.svelte";
    import AllCards from "./AllCards.svelte";
    import CardSelection from "./CardSelection.svelte";
    import OtherPlayerBoardTester from "./OtherPlayerBoardTester.svelte";
    import PlayerBoardTester from "./PlayerBoardTester.svelte";
    import ActivityDevelopment from "./ActivityDevelopment.svelte";
    import ActivityDeployment from "./ActivityDeployment.svelte";
    import ActivityConference from "./ActivityConference.svelte";
    import ActivityRetrospective from "./ActivityRetrospective.svelte";
    import CentralPanelTester from "./CentralPanelTester.svelte";
    import GameBoardTester from "./GameBoardTester.svelte";
    import {BGA} from "../lib/BGA";

    let items = [
        {label: "Game Board", component: GameBoardTester},
        {label: "Central Panel", component: CentralPanelTester},
        {label: "Activity: Retrospective", component: ActivityRetrospective},
        {label: "Activity: Development", component: ActivityDevelopment},
        {label: "Activity: Conference", component: ActivityConference},
        {label: "Activity: Deployment", component: ActivityDeployment},
        {label: "Player Board", component: PlayerBoardTester},
        {label: "Other Player Boards", component: OtherPlayerBoardTester},
        {label: "Card Selection", component: CardSelection},
        {label: "All Cards", component: AllCards}
    ];


    BGA.setPerformAction((action, args) => {
        if (action === 'actChooseActivity')
            alert('ACTION ' + action + '\n' + JSON.stringify(args));
        else
            alert('ACTION ' + action + '\n' + JSON.stringify(Object.fromEntries(
                Object.entries(args).map(([key, value]) => [key, JSON.parse(value.toString())])
            ), null, 2));
    });

    let language = "";
</script>

<main>
    {#key language}
        <Banner />
    {/key}
    <div class="tabs-container">
        <LanguageToggle bind:language />
        {#key language}
            <Tabs activeTabValue="0" {items}/>
        {/key}
    </div>
</main>

<style>
    main {
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 20px;
    }

    .tabs-container {
        position: relative;
        width: 100%;
        margin-top: 20px;
    }
</style>