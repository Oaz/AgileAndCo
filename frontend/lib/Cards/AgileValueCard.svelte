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
    import {_, Text} from '../texts';
    import Card from './Card.svelte';
    import Logo from "./Logo.svelte";
    import CardFooter from "./CardFooter.svelte";
    import CardHeader from "./CardHeader.svelte";

    export let title;
    export let description;
</script>

<style>


    :global(.agile-value) {
        border-style: solid;
        border-width: 0.1mm;
        border-color: black;
        border-radius: 0;
        background-color: #1d6b41;
    }

    :global(.agile-value) .name {
        margin-top: 8mm;
        font-size: 16pt;
        transform: translateY(-50%);
    }

    :global(.agile-value) .description {
        text-align: center;
        position: absolute;
        top: 24mm;
        font-family: "Arial",serif;
        font-size: 9pt;
        padding-left: 4mm;
        padding-right: 4mm;
        transform: translateY(-50%);
    }

    :global(.agile-value) :global(.logo) {
        position: absolute;
        top: 43mm;
        left: 2mm;
        width: 10mm;
    }

    :global(.agile-value) .gain {
        position: absolute;
        top: 48mm;
        left: 14mm;
        padding-right: 1mm;
        font-family: "Arial",serif;
        font-size: 8pt;
        transform: translateY(-50%);
    }
</style>

<Card cardType="agile-value">
    <CardHeader slot="header" cardTypeName={_(Text.AGILE_VALUE)} />
    <div class="name">{title}</div>
    <div class="description">{description}</div>
    <div class="sectionhead">{_(Text.BENEFIT)}</div>
    <Logo key="LEADER" />
    <div class="gain">{_(Text.AGILE_VALUE_GAIN)}</div>
    <CardFooter slot="footer" cost="4" score="n" />
</Card>


