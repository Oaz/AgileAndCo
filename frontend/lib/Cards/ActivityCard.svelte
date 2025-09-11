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
    import Logo from "./Logo.svelte";
    import Card from "./Card.svelte";
    import CardHeader from "./CardHeader.svelte";
    import {_, Text} from '../texts';

    export let title;
    export let description;
    export let logo;
    export let privilege;
</script>

<style>

    :global(.activity) {
        border-radius: 0;
        background-color: #987c29;
    }

    :global(.activity) :global(.container) {
        height: 67mm;
    }

    :global(.activity) .name {
        margin-top: 5mm;
        font-size: 16pt;
        transform: translateY(-50%);
    }

    :global(.activity) :global(.logo) {
        position: absolute;
        top: 12mm;
        left: 12mm;
        width: 20mm;
    }

    :global(.activity) .description {
        text-align: center;
        position: absolute;
        top: 39mm;
        font-family: "Arial",serif;
        font-size: 10pt;
        padding-left: 1mm;
        padding-right: 1mm;
        transform: translateY(-50%);
    }

    :global(.activity) :global(.sectionhead) {
        top: 49mm;
    }

    :global(.activity) .privilege {
        text-align: center;
        position: absolute;
        top: 60mm;
        left: 1mm;
        padding-right: 1mm;
        font-family: "Arial",serif;
        font-size: 9pt;
        transform: translateY(-50%);
    }
</style>

<Card cardType="activity">
    <CardHeader slot="header" cardTypeName={_(Text.ACTIVITY)} />
    <div class="name">{title}</div>
    <div class="description">{description}</div>
    <div class="sectionhead">{_(Text.BONUS)}</div>
    <Logo key={logo} />
    <div class="privilege">{privilege}</div>
</Card>



