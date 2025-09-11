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
    import ReadOnlyCard from "./ReadOnlyCard.svelte";

    export let datas;

</script>

{#key datas}
    <div class="board">
        <div class="title">{datas.name}</div>
        <div class="company">
            <div class="teams">
                {#each datas.teams as team, index}
                    <ReadOnlyCard key={team}/>
                    {#if datas.products[index]}
                        <ReadOnlyCard key={datas.products[index]} hidden={true}/>
                    {/if}
                {/each}
            </div>
            <div class="other">
                {#each datas.company as key, index}
                    <ReadOnlyCard {key}/>
                {/each}
            </div>
        </div>
    </div>
{/key}

<style>
    .company {
        display: flex;
        flex-flow: row wrap;
        gap: 50px;
    }

    .title {
        text-align: left;
        font-family: "Arial", serif;
        font-size: 18pt;
        padding-left: 5px;
    }

    .board {
        zoom: 0.6;
        border-style: solid;
        border-width: 2px;
        border-color: #565656;
    }
</style>