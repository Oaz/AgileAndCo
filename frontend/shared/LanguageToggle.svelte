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
    export let language = "";
</script>

<div class="language-toggle">
    <button class:active={language === "en"} on:click={() => language='en'}>EN</button>
    <button class:active={language === "fr"} on:click={() => language='fr'}>FR</button>
</div>

<style>
    .language-toggle {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 1;
    }

    .language-toggle button {
        padding: 5px 10px;
        margin: 0 2px;
        border: 1px solid #ccc;
        background: white;
        cursor: pointer;
        border-radius: 3px;
    }

    .language-toggle button.active {
        background: #007bff;
        color: white;
        border-color: #0056b3;
    }
</style>