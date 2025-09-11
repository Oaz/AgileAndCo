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
    import DeckCard from "../lib/Cards/DeckCard.svelte";

    export let key: string;
    export let scale: number = 0.9;
</script>

<div class="card-wrapper" style="--card-scale: {scale}">
    <DeckCard {key} />
</div>

<style>
    .card-wrapper {
        zoom: var(--card-scale);
    }
</style>