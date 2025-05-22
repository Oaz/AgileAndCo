<script lang="ts">
    export let language = "";
</script>

<div class="language-toggle">
    <button class:active={language === "en"} on:click={() => language='en'}>EN</button>
    <button class:active={language === "fr"} on:click={() => language='fr'}>FR</button>
</div>

<style>
    .language-toggle {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 1;
    }

    .language-toggle button {
        padding: 5px 10px;
        margin: 0 2px;
        border: 1px solid #ccc;
        background: white;
        cursor: pointer;
        border-radius: 3px;
    }

    .language-toggle button.active {
        background: #007bff;
        color: white;
        border-color: #0056b3;
    }
</style>