<script>
    export let items = [];
    export let activeTabValue = 0;

    const handleClick = tabValue => () => (activeTabValue = tabValue);
</script>

<ul>
    {#each items as item, index}
        <li class={activeTabValue === index ? 'active' : ''}>
            <span on:click={handleClick(index)}>{item.label}</span>
        </li>
    {/each}
</ul>
{#each items as item, index}
    {#if activeTabValue == index}
        <div class="box">
            <svelte:component this={item.component} {...(item.props || {})}/>
        </div>
    {/if}
{/each}
<style>
    .box {
        margin-bottom: 10px;
        padding: 20px;
        border: 1px solid #dee2e6;
        border-radius: 0 0 .5rem .5rem;
        border-top: 0;
    }
    ul {
        display: flex;
        flex-wrap: wrap;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
        border-bottom: 1px solid #dee2e6;
    }
    li {
        margin-bottom: -1px;
    }

    span {
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
        display: block;
        padding: 0.5rem 1rem;
        cursor: pointer;
    }

    span:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    li.active > span {
        color: #495057;
        background-color: #bbbbbb;
        border-color: #dee2e6 #dee2e6 #fff;
    }
</style>
