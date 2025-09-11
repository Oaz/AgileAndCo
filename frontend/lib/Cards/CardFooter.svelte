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
    export let cost;
    export let score;
</script>

<style>
    .cost {
        width: 20mm;
        height: 11mm;
        position: absolute;
        top: 68mm;
        left: 5mm;
    }

    .score {
        width: 20mm;
        height: 11mm;
        position: absolute;
        top: 68mm;
        left: 28mm;
    }

    .label {
        font-family: "Arial", serif;
        font-size: 7pt;
        position: absolute;
        top: 11mm;
        left: 3mm;
        color: white;
        transform-origin: 0 0;
        transform: rotate(-90deg);
    }

    .value {
        font-family: "Arial", serif;
        font-size: 26pt;
        position: absolute;
        top: 2.5mm;
        left: 7mm;
        color: white;
    }
</style>

<div>
    <div class="cost">
        <div class="label">{_(Text.COST)}</div>
        <div class="value">{cost}</div>
    </div>
    <div class="score">
        <div class="label">{_(Text.SCORE)}</div>
        <div class="value">{score}</div>
    </div>
</div>