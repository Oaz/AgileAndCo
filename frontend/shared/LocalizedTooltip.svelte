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