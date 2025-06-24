<script>
    export let items = [];
    export let activeTabValue = 0;
    export let vertical = false;

    const handleClick = tabValue => () => (activeTabValue = tabValue);
</script>

<div class="tabs-container" class:vertical>
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
</div>

<style>
    .tabs-container {
        display: flex;
        width: 100%;
        flex-direction: column;
    }

    .tabs-container.vertical {
        flex-direction: row;
    }

    .box {
        margin-bottom: 10px;
        padding: 20px;
        border: 1px solid #dee2e6;
        border-radius: 0 0 .5rem .5rem;
        flex: 1;
        overflow: hidden;

    }
    .vertical .box {
        border-radius: 0 .5rem .5rem 0;
        border-left: 0;
        border-top: 1px solid #dee2e6;
        margin-bottom: 0;
    }

    ul {
        display: flex;
        flex-wrap: wrap;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
        border-bottom: 1px solid #dee2e6;
    }

    .vertical ul {
        flex-direction: column;
        border-bottom: none;
        border-right: 1px solid #dee2e6;
        min-width: 150px;
    }

    li {
        margin-bottom: -1px;
    }

    .vertical li {
        margin-bottom: 0;
        margin-right: -1px;
    }


    span {
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
        display: block;
        padding: 0.5rem 1rem;
        cursor: pointer;
    }

    .vertical span {
        border-radius: 0.25rem 0 0 0.25rem;
    }

    span:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    .vertical span:hover {
        border-color: #e9ecef #dee2e6 #e9ecef #e9ecef;
    }

    li.active > span {
        color: #495057;
        background-color: #bbbbbb;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    .vertical li.active > span {
        border-color: #dee2e6 #fff #dee2e6 #dee2e6;
    }

</style>
