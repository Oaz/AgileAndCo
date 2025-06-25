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

