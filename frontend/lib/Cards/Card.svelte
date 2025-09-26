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

<script>
    export let cardType;

</script>

<style>
    .card {
        width: 50mm;
        height: 81mm;
        position: relative;
        border: none !important;
    }

    .card-container {
        border-radius: 2mm;
        background-color: white;
        position: absolute;
        top: 10mm;
        left: 3mm;
        width: 44mm;
        height: 60mm;
    }

    :global(.name) {
        text-align: center;
        font-family: "Arial", serif;
        font-weight: bold;
        font-variant: small-caps;
    }

    :global(.sectionhead) {
        text-align: left;
        font-family: "Arial", serif;
        font-size: 8pt;
        position: absolute;
        top: 38mm;
        width: 40mm;
        left: 2mm;
        border-top: 1px solid;
        font-variant: small-caps;
    }

</style>

<div class="card {cardType}">
    <slot name="header"></slot>
    <div class="card-container">
        <slot/>
    </div>
    <slot name="footer"></slot>
</div>
