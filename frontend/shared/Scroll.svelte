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
    export let items = []; // Each item should have: { label, anchorId }
    export let activeTabValue = 0;
    export let vertical = true;
    export let contentMaxHeight = "500px"; // Adding a prop for customizable max height

    let contentContainer;

    function scrollToAnchor(anchorId) {
        if (contentContainer) {
            const anchorEl = contentContainer.querySelector(`#${anchorId}`);
            if (anchorEl) {
                // Instead of scrollIntoView (which scrolls the whole document),
                // we'll scroll within the content container
                contentContainer.scrollTop = anchorEl.offsetTop - contentContainer.offsetTop;
            }
        }
    }

    function handleClick(index) {
        activeTabValue = index;
        scrollToAnchor(items[index].anchorId);
    }
</script>

<div class="scroll-container" class:vertical>
    <!-- Tabs header; hidden on small viewports -->
    <ul class="tabs">
        {#each items as item, index}
            <li class={activeTabValue === index ? 'active' : ''}>
        <span on:click={() => handleClick(index)}>
          {item.label}
        </span>
            </li>
        {/each}
    </ul>

    <!-- Content container wraps the single subcomponent containing anchors -->
    <div class="content" bind:this={contentContainer}>
        <slot/>
    </div>
</div>

<style>
    .scroll-container {
        display: flex;
        width: 100%;
        flex-direction: column;
    }

    /* Vertical arrangement when vertical prop is true */
    .scroll-container.vertical {
        flex-direction: row;
    }

    /* Tabs header styling */
    .tabs {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        border-bottom: 1px solid #dee2e6;
    }

    /* Vertical tabs : layout the header as a column, border adjustment */
    .scroll-container.vertical .tabs {
        flex-direction: column;
        border-bottom: none;
        border-right: 1px solid #dee2e6;
        min-width: 150px;
    }

    .tabs li {
        margin-bottom: -1px;
    }

    /* Vertical tabs li margins */
    .scroll-container.vertical .tabs li {
        margin-bottom: 0;
        margin-right: -1px;
    }

    .tabs span {
        display: block;
        padding: 0.5rem 1rem;
        cursor: pointer;
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
    }

    /* Adjust border radius for vertical layout */
    .scroll-container.vertical .tabs span {
        border-radius: 0.25rem 0 0 0.25rem;
    }

    .tabs span:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    .tabs li.active > span {
        color: #495057;
        background-color: #bbbbbb;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    /* Active state for vertical tabs */
    .scroll-container.vertical li.active > span {
        border-color: #dee2e6 #fff #dee2e6 #dee2e6;
    }

    /* Content container styling */
    .content {
        padding: 5px;
        border: 1px solid #dee2e6;
        border-top: 0;
        flex: 1;
        overflow-y: auto; /* Changed from 'hidden' to 'auto' to enable scrolling */
        max-height: var(--content-max-height, 500px); /* Default max height */
    }

    /* Adjust content border for vertical layout */
    .scroll-container.vertical .content {
        border-left: 0;
        border-top: 1px solid #dee2e6;
    }

    /* Media query for responsive behavior */
    @media (min-width: 601px) {
        .content {
            max-height: var(--content-max-height, 500px);
            overflow-y: auto;
            overflow-x: hidden;
        }
    }

    @media (max-width: 600px), print {
        .tabs {
            display: none;
        }

        .content {
            max-height: none;
        }
    }


</style>