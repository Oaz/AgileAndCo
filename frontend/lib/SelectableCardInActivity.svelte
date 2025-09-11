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
    import {Activity} from "./Activities/Activity";
    import Selectable from "./Selectable.svelte";
    import DeckCard from "./Cards/DeckCard.svelte";

    export let activity: Activity;
    export let zone_id: number;
    export let index: number;

    let card_id: number;
    $: card_id = activity.id(zone_id, index);

    export let hidden: boolean = false;
    export let card_key: string;

</script>

<Selectable {hidden}
            bind:interaction={activity.interaction[card_id]}
            bind:selected={activity.selection[card_id]}>
    <DeckCard key={card_key} />
</Selectable>