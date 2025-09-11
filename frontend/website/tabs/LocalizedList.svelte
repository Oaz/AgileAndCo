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
    import {Localized} from '@nubolab-ffwd/svelte-fluent';

    export let id: string;

    function getItemsFromAttrs(attrs: Record<string, string>): string[] {
        return Object.entries(attrs)
            .filter(([key]) => key.startsWith('item'))
            .sort(([a], [b]) => a.localeCompare(b))
            .map(([_, value]) => value);
    }

</script>


<Localized id={id}>
    {#snippet children({ text, attrs })}
        <div class="header">{text}</div>
        <ul>
            {#each getItemsFromAttrs(attrs) as item}
                <li>{item}</li>
            {/each}
        </ul>

    {/snippet}
</Localized>

