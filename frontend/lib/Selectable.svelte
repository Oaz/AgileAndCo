<script lang="ts">
    export let interaction: 'NEUTRAL' | 'ACTIVE' | 'FROZEN' = 'NEUTRAL';
    export let hidden = false;
    export let selected = false;
    export let borderSize = "5px";
    export let borderColor = "red";
    export let backColor = "gray";
    export let backPattern = "";

    $: borderStyle = selected ? borderColor : "transparent";

    $: backStyle = backPattern ? `url(${backPattern})` : backColor;

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
        position: relative;
        padding: 2px;
        border-style: solid;
        border-width: var(--border-size);
        border-color: var(--border-color);
        transition: border-color 0.2s ease;
    }

    .selectable.frozen {
        opacity: 0.5;
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
        background-image: var(--back-pattern);
        background-size: cover;
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
    --back-pattern: {backStyle};
  "
>
    <div class="content">
        <slot></slot>
        {#if isHidden()}
            <div class="back"></div>
        {/if}
    </div>
</div>