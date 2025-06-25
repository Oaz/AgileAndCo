<script lang="ts">
    import {Localized} from '@nubolab-ffwd/svelte-fluent';

    export let id: string;

</script>


<Localized id={id}>
    {#snippet children({ text, attrs })}
        <h3>{text}</h3>
        <p>{attrs['details']}</p>
    {/snippet}
</Localized>

