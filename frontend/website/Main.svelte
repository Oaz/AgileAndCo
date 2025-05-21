<script lang="ts">
    import Banner from "../shared/Banner.svelte";
    import LanguageToggle from "../shared/LanguageToggle.svelte";
    let language = "";

    function openPrintPage() {
        window.open(`print.html?lang=${language}`, '_blank');
    }
</script>

<main>
    {#key language}
        <Banner />
    {/key}
    <div class="tabs-container">
        <LanguageToggle bind:language />
        <h1>Drive your software development business to success by mastering agile practices</h1>
        <button class="print-button" on:click={openPrintPage}>Printable Cards</button>
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