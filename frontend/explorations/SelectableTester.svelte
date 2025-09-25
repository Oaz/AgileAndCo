<!--
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

<script lang="ts">
    import Selectable from "../lib/Selectable.svelte";
    import cardBack from '../images/back.svg';

    export let hidden = false;
    export let selected = false;
    let borderSize = "5px";
    let borderColor = "red";
    let backColor = "gray";
    let backPattern = cardBack;

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