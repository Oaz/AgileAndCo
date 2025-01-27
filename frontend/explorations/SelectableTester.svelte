<script lang="ts">
    import Selectable from "../lib/Selectable.svelte";

    export let hidden = false;
    export let selected = false;
    let borderSize = "5px";
    let borderColor = "red";
    let backColor = "gray";
    let backPattern = "";

    const interactions = [
        { value: 'NEUTRAL', label: "Neutral" },
        { value: 'ACTIVE', label: "Active" },
        { value: 'FROZEN', label: "Frozen" },
    ];
    export let selectedInteraction = 'ACTIVE';
</script>

<style>
    .tester {
        display: flex;
        flex-direction: row;
        gap: 16px;
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .controls {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    label {
        font-weight: bold;
        margin-bottom: 4px;
    }

    input,
    select {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
    }

    .preview {
        margin: 20px;
        text-align: center;
    }
</style>

<div class="tester">

    <div class="controls">
        <label for="interaction">Interaction:</label>
        <select id="interaction" bind:value={selectedInteraction}>
            {#each interactions as interaction}
                <option value={interaction.value}>{interaction.label}</option>
            {/each}
        </select>

        <label>
            <input type="checkbox" bind:checked={hidden} />
            Hidden
        </label>

        <label>
            <input type="checkbox" bind:checked={selected} />
            Selected
        </label>

        <label for="borderSize">Border Size:</label>
        <input id="borderSize" type="text" bind:value={borderSize} />

        <label for="borderColor">Border Color:</label>
        <input id="borderColor" type="text" bind:value={borderColor} />

        <label for="backColor">Back Color:</label>
        <input id="backColor" type="text" bind:value={backColor} />

        <label for="backPattern">Back Pattern (Image URL):</label>
        <input id="backPattern" type="text" bind:value={backPattern} />

    </div>

    <div class="preview">
        <h3>Preview</h3>
        <Selectable
                bind:selected
                bind:hidden
                bind:interaction={selectedInteraction}
                {borderSize}
                {borderColor}
                {backColor}
                {backPattern}
        >
            <slot></slot>
        </Selectable>
    </div>
</div>