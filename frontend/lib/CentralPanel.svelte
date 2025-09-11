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
    import ActionButton from "./ActionButton.svelte";
    import ReadOnlyCard from "./ReadOnlyCard.svelte";
    import Selectable from "./Selectable.svelte";
    import DeckCard from "./Cards/DeckCard.svelte";
    import {ActivitySelection} from "./Activities/ActivitySelection";

    export let datas;

    let action: ActivitySelection;
    $: action = new ActivitySelection(datas);

</script>

{#key datas}
    <div class="panel">
        <div class="row">
            <div class="activities">
                {#each datas.activities as activity, index}
                    <Selectable
                            bind:interaction={action.interaction[index]}
                            bind:selected={action.selection[index]}
                            hidden={activity[1]}>
                        <DeckCard key={activity[0]}/>
                    </Selectable>
                {/each}
            </div>
            <div class="misc">
                <ReadOnlyCard key={datas.earnings[0]} hidden={datas.earnings[1]}/>
            </div>
        </div>
        <div class="footer">
            <ActionButton {action}/>
        </div>
    </div>
{/key}

<style>
    .row {
        border-style: solid;
        border-width: 2px;
        border-color: #565656;
        display: flex;
        flex-flow: row wrap;
        gap: 50px;
    }

    .panel {
        zoom: 0.8;
    }
</style>