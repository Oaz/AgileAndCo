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
