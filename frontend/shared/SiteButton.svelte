<script lang="ts">
    import {Localized} from '@nubolab-ffwd/svelte-fluent';

    export let id: string;
    export let action: () => void;
    export let backgroundColor: string = "#4CAF50";
    export let hoverColor: string = "#45a049";
</script>

<button
        class="action-button"
        on:click={action}
        style="--background-color: {backgroundColor}; --hover-color: {hoverColor};"
>
    <Localized id={id} />
</button>

<style>
    .action-button {
        padding: 8px 16px;
        background-color: var(--background-color);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin: 15px 0;
    }

    .action-button:hover {
        background-color: var(--hover-color);
    }
</style>