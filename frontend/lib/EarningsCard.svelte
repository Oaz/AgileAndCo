
<script>
    import {_, Text} from "./texts";
    import Logo from "./Logo.svelte";
    import Card from "./Card.svelte";
    import CardHeader from "./CardHeader.svelte";

    export let advergame;
    export let education;
    export let social;
    export let mmog;
</script>

<style>
    :global(.earnings) {
        border-radius: 0;
        background-color: #565656;
    }

    :global(.earnings) :global(.container) {
        height: 67mm;
    }

    :global(.earnings) .earning {
        position: absolute;
        left: 5mm;
        width: 40mm;
    }

    :global(.earnings) .advergame {
        top: 1mm;
    }

    :global(.earnings) .education {
        top: 17mm;
    }

    :global(.earnings) .social {
        top: 33mm;
    }

    :global(.earnings) .mmog {
        top: 49mm;
    }

    :global(.earnings) :global(.logo) {
        position: absolute;
        top: 2mm;
        left: 2mm;
        width: 15mm;
    }

    :global(.earnings) .value {
        position: absolute;
        font-family: "Arial", serif;
        font-size: 26pt;
        top: 4mm;
        left: 25mm;
        color: black;
    }
</style>


<Card cardType="earnings">
    <CardHeader slot="header" cardTypeName={_(Text.EARNINGS)} />
    <div class="earning advergame">
        <Logo key="ADVERGAME" />
        <div class="value">{advergame}</div>
    </div>
    <div class="earning education">
        <Logo key="EDUCATION" />
        <div class="value">{education}</div>
    </div>
    <div class="earning social">
        <Logo key="SOCIAL" />
        <div class="value">{social}</div>
    </div>
    <div class="earning mmog">
        <Logo key="MMOG" />
        <div class="value">{mmog}</div>
    </div>
</Card>
