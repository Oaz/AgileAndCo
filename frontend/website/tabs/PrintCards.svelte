
<script lang="ts">

    export let language: string;


    function openPrintPage() {
        window.open(`print.html?lang=${language}`, '_blank');
    }
</script>
<div>
    <button class="print-button" on:click={openPrintPage}>Printable Cards</button>
</div>

<style>
    .print-button {
        padding: 8px 16px;
        margin-top: 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .print-button:hover {
        background-color: #45a049;
    }
</style>