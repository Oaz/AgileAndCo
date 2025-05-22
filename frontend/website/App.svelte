<script lang="ts">
    import Router from 'svelte-spa-router'
    import Main from "./Main.svelte";

    const routes = {
        '/': Main,
        '/:language/:tab': Main,
    }
</script>

<div class="app-wrapper">
    <Router {routes} />
</div>

<style>
    .app-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    @media (max-width: 768px) {
        .app-wrapper {
            padding: 0 1rem;
        }
    }
</style>


