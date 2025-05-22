<script lang="ts">
    import Router from 'svelte-spa-router'
    import Main from "./Main.svelte";

    const routes = {
        '/': Main,
        '/:language/:tab': Main,
    }
</script>

<main>
    <Router {routes} />
</main>

