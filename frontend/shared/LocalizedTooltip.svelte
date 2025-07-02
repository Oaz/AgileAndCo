<script lang="ts">
    import { Localized } from '@nubolab-ffwd/svelte-fluent';

    export let textId: string;
    export let tooltipId: string;
    export let width: string = "300px";
</script>

<div class="tooltip-container">
    <span
            class="tooltip-trigger"
            tabindex="0"
            role="button"
            aria-describedby="tooltip-{tooltipId}"
            on:keydown={(e) => e.key === 'Enter' && e.currentTarget.classList.toggle('tooltip-active')}
            on:click={(e) => e.currentTarget.classList.toggle('tooltip-active')}
    >
        <Localized id={textId}/>
    </span>
    <div class="tooltip-content" id="tooltip-{tooltipId}" role="tooltip" style="width: {width}">
        <Localized id={tooltipId}/>
    </div>
</div>

<style>
    .tooltip-container {
        position: relative;
        display: inline-block;
    }

    .tooltip-trigger {
        cursor: pointer;
        text-decoration: none;
    }

    .tooltip-trigger:hover,
    .tooltip-trigger:focus {
        text-decoration: underline;
    }

    .tooltip-content {
        visibility: hidden;
        position: absolute;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        background-color: white;
        color: #333;
        text-align: left;
        padding: 10px 15px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 1;
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 13px;
        line-height: 1.4;
        max-width: 90vw;
    }

    .tooltip-trigger:hover + .tooltip-content,
    .tooltip-trigger:focus + .tooltip-content,
    .tooltip-trigger.tooltip-active + .tooltip-content {
        visibility: visible;
        opacity: 1;
    }

    .tooltip-content::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: white transparent transparent transparent;
    }
</style>