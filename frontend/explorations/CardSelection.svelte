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
    import DeckCard from '../lib/Cards/DeckCard.svelte';
    import SelectableTester from './SelectableTester.svelte';
    let isPictureCardBack = false;

    function toggleCardBackClass(isChecked: boolean): void {
        const htmlElement = document.documentElement; // Gets the <html> element
        if (isChecked) {
            htmlElement.classList.add('card-back-picture');
            htmlElement.classList.remove('card-back-neutral');
        } else {
            htmlElement.classList.add('card-back-neutral');
            htmlElement.classList.remove('card-back-picture');
        }
    }

    $: toggleCardBackClass(isPictureCardBack);

</script>

<div>
    <div class="checkbox-container">
        <label>
            <input type="checkbox" bind:checked={isPictureCardBack} />
            picture card back
        </label>
    </div>

    <div id="content">
        <SelectableTester selectedInteraction = "ACTIVE">
            <DeckCard key="PRODUCT_TEAM_ADVERGAME" />
        </SelectableTester>
        <SelectableTester selectedInteraction = "FROZEN">
            <DeckCard key="AGILE_MATURITY_AGILE_PRACTITIONER" />
        </SelectableTester>
        <SelectableTester selectedState = "ACTIVE" hidden="1">
            <DeckCard key="ACTIVITY_RETROSPECTIVE" />
        </SelectableTester>
    </div>
</div>

<style>
    main {
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 20px;
    }

    #content {
        display: flex;
        justify-content: center;
    }

</style>