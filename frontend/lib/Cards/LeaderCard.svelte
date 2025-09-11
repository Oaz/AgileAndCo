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
    import Logo from "./Logo.svelte";
    import Card from "./Card.svelte";
    import CardHeader from "./CardHeader.svelte";
</script>

<style>
    :global(.leader) {
        border-radius: 0;
        background-color: #8a8062;
    }

    :global(.leader) :global(.container) {
        height: 67mm;
    }

    :global(.leader) :global(.logo) {
        position: absolute;
        top: 6mm;
        left: 12mm;
        width: 20mm;
    }

    :global(.leader) .description {
        text-align: center;
        position: absolute;
        top: 30mm;
        font-family: "Arial", serif;
        font-size: 10pt;
        padding-left: 1mm;
        padding-right: 1mm;
    }

    :global(.leader) .description2 {
        text-align: center;
        position: absolute;
        top: 52mm;
        font-family: "Arial", serif;
        font-size: 10pt;
        padding-left: 1mm;
        padding-right: 1mm;
    }
</style>


<Card cardType="leader">
    <CardHeader slot="header" cardTypeName={_(Text.GAME_LEADER)} />
    <Logo key="LEADER"/>
    <div class="description">{_(Text.LEADER_DESCRIPTION1)}</div>
    <div class="description2">{_(Text.LEADER_DESCRIPTION2)}</div>
</Card>
