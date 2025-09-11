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
    import {Action} from "./Action";

    export let action: Action;

</script>

{#if action.enabled}
    <button on:click={function (){
        action.do_act();
    }} class="action {action.can_act ? '' : 'inactive'}">
        {action.action_text}
    </button>
{/if}

<style>
    .action {
        font-size: 18pt;
        margin: 5px;
    }

    .inactive {
        pointer-events: none;
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>