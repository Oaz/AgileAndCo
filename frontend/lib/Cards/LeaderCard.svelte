<script>
    import {_, Text} from '../texts';
    import Logo from "./Logo.svelte";
    import Card from "./Card.svelte";
    import CardHeader from "./CardHeader.svelte";
</script>

<style>
    :global(.leader) {
        border-radius: 0;
        background-color: #8a8062;
    }

    :global(.leader) :global(.container) {
        height: 67mm;
    }

    :global(.leader) :global(.logo) {
        position: absolute;
        top: 6mm;
        left: 12mm;
        width: 20mm;
    }

    :global(.leader) .description {
        text-align: center;
        position: absolute;
        top: 30mm;
        font-family: "Arial", serif;
        font-size: 10pt;
        padding-left: 1mm;
        padding-right: 1mm;
    }

    :global(.leader) .description2 {
        text-align: center;
        position: absolute;
        top: 52mm;
        font-family: "Arial", serif;
        font-size: 10pt;
        padding-left: 1mm;
        padding-right: 1mm;
    }
</style>


<Card cardType="leader">
    <CardHeader slot="header" cardTypeName={_(Text.GAME_LEADER)} />
    <Logo key="LEADER"/>
    <div class="description">{_(Text.LEADER_DESCRIPTION1)}</div>
    <div class="description2">{_(Text.LEADER_DESCRIPTION2)}</div>
</Card>
