<script>
    import {_, Text} from '../texts';
    import Logo from "./Logo.svelte";
    import Card from "./Card.svelte";
</script>

<style>
    :global(.leader) {
        border-radius: 0mm;
        background-color: #8a8062;
    }

    :global(.leader) :global(.container) {
        top: 5mm;
        height: 71mm;
    }

    :global(.leader) .name {
        margin-top: 5mm;
        font-size: 16pt;
    }

    :global(.leader) :global(.logo) {
        position: absolute;
        top: 13mm;
        left: 12mm;
        width: 20mm;
    }

    :global(.leader) .description {
        text-align: center;
        position: absolute;
        top: 33mm;
        font-family: "Arial", serif;
        font-size: 10pt;
        padding-left: 1mm;
        padding-right: 1mm;
    }

    :global(.leader) .description2 {
        text-align: center;
        position: absolute;
        top: 54mm;
        font-family: "Arial", serif;
        font-size: 10pt;
        padding-left: 1mm;
        padding-right: 1mm;
    }
</style>


<Card cardType="leader">
    <div class="name">{_(Text.GAME_LEADER)}</div>
    <Logo key="LEADER"/>
    <div class="description">{_(Text.LEADER_DESCRIPTION1)}</div>
    <div class="description2">{_(Text.LEADER_DESCRIPTION2)}</div>
</Card>
