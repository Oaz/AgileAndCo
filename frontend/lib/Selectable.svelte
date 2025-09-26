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
    import {addImageinRootCss} from './images';

    import cardBack from '../images/back.svg';
    import {onMount} from "svelte";
    export let interaction: 'NEUTRAL' | 'ACTIVE' | 'FROZEN' = 'NEUTRAL';
    export let hidden : boolean = false;
    export let selected : boolean = false;
    export let borderSize = "5px";
    export let borderColor = "red";
    export let backColor = "gray";
    export let backPattern = null;
    let backPatternUrl = backPattern ? `url(${backPattern})` : null;


    onMount(() => {
        addImageinRootCss('--image-card-back', cardBack);
    });

    $: borderStyle = selected ? borderColor : "transparent";

    function toggleSelection() {
        if (isActive()) {
            selected = !selected;
        }
    }

    function isActive() {
        return interaction === 'ACTIVE';
    }

    function isFrozen() {
        return interaction === 'FROZEN';
    }

    function isHidden() {
        return hidden;
    }

    $ :{
        if (interaction === 'NEUTRAL')
            selected = false;
    }
</script>

<style>
    .selectable {
        display: inline-block;
        cursor: not-allowed;
        user-select: none;
        position: relative;
        padding: 2px;
        border-style: solid;
        border-width: var(--border-size);
        border-color: var(--border-color);
        transition: border-color 0.2s ease;
    }

    .selectable.frozen {
        opacity: 0.65;
    }

    .selectable.active {
        cursor: pointer;
    }

    .back {
        position: absolute;
        top: 50%;
        left: 50%;
        width: calc(100% - 4px);
        height: calc(100% - 4px);
        transform: translate(-50%, -50%);
        background-color: var(--back-color);
        background-image: var(--back-style);
        background-size: cover;
    }

    :global(html.card-back-neutral) .back {
        --back-style: var(--back-color);
    }

    :global(html.card-back-picture) .back {
        --back-style: var(--custom-back-pattern, var(--image-card-back));
    }
</style>

<div
        class="selectable {isFrozen() ? 'frozen' : ''} {isActive() ? 'active' : ''}"
        on:click={toggleSelection}
        on:keydown={undefined}
        role="button"
        tabindex="0"
        style="
    --border-size: {borderSize};
    --border-color: {borderStyle};
    --back-color: {backColor};
    {backPatternUrl ? `--custom-back-pattern: ${backPatternUrl};` : ''}
  "
>
    <div class="content">
        <slot></slot>
        {#if isHidden()}
            <div class="back"></div>
        {/if}
    </div>
</div>