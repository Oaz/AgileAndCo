<script>
    export let cardType;

</script>

<style>
    .card {
        width: 50mm;
        height: 81mm;
        position: relative;
        border: none !important;
    }

    .container {
        border-radius: 2mm;
        background-color: white;
        position: absolute;
        top: 10mm;
        left: 3mm;
        width: 44mm;
        height: 60mm;
    }

    :global(.name) {
        text-align: center;
        font-family: "Arial", serif;
        font-weight: bold;
        font-variant: small-caps;
    }

    :global(.sectionhead) {
        text-align: left;
        font-family: "Arial", serif;
        font-size: 8pt;
        position: absolute;
        top: 38mm;
        width: 40mm;
        left: 2mm;
        border-top: 1px solid;
        font-variant: small-caps;
    }

</style>

<div class="card {cardType}">
    <slot name="header"></slot>
    <div class="container">
        <slot/>
    </div>
    <slot name="footer"></slot>
</div>
