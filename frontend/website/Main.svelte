<script lang="ts">
    import Banner from "../shared/Banner.svelte";
    import LanguageToggle from "../shared/LanguageToggle.svelte";
    let language = "";
</script>

<main>
    {#key language}
        <Banner />
    {/key}
    <div class="tabs-container">
        <LanguageToggle bind:language />
        <h1>Drive your software development business to success by mastering agile practices</h1>
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