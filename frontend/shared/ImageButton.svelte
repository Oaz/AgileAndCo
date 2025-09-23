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
    import {getFluentContext} from '@nubolab-ffwd/svelte-fluent';

    export let id: string;
    export let image : any;
    export let action: () => void;
    export let hoverColor: string = "#00B0FF";

    let alt = getFluentContext().localize(id);
</script>

<button
        class="action-button"
        on:click={action}
        style="--hover-color: {hoverColor};"
>
    <img src={image} alt={alt} class="hero-image">
</button>

<style>
    .action-button {
        padding: 4px 6px;
        background-color: var(--background-color);
        color: white;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        font-size: 16px;
        margin: 15px 0;
    }

    .action-button:hover {
        background-color: var(--hover-color);
    }

    .hero-image {
        width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
</style>