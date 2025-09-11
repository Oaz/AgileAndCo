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
    import CentralPanel from '../lib/CentralPanel.svelte';

    const options = [
        {
            label: 'Start of turn: select activity', data: {
                'selection': true,
                'activities': [
                    ['ACTIVITY_CONFERENCE', false, false],
                    ['ACTIVITY_DEVELOPMENT', false, false],
                    ['ACTIVITY_DEPLOYMENT', false, false],
                    ['ACTIVITY_RETROSPECTIVE', false, false],
                    ['ACTIVITY_COACH', false, false],
                ],
                'earnings': ['EARNINGS_CARD_1', true],
            }
        },
        {
            label: 'While the activity is played', data: {
                'selection': false,
                'activities': [
                    ['ACTIVITY_CONFERENCE', false, false],
                    ['ACTIVITY_DEVELOPMENT', false, true],
                    ['ACTIVITY_DEPLOYMENT', false, false],
                    ['ACTIVITY_RETROSPECTIVE', false, false],
                    ['ACTIVITY_COACH', false, false],
                ],
                'earnings': ['EARNINGS_CARD_1', true],
            }
        },
        {
            label: 'Select next activity', data: {
                'selection': true,
                'activities': [
                    ['ACTIVITY_CONFERENCE', false, false],
                    ['ACTIVITY_DEVELOPMENT', true, false],
                    ['ACTIVITY_DEPLOYMENT', false, false],
                    ['ACTIVITY_RETROSPECTIVE', false, false],
                    ['ACTIVITY_COACH', false, false],
                ],
                'earnings': ['EARNINGS_CARD_1', true],
            }
        },
        {
            label: 'Deployment activity is played with earnings', data: {
                'selection': false,
                'activities': [
                    ['ACTIVITY_CONFERENCE', false, false],
                    ['ACTIVITY_DEVELOPMENT', true, false],
                    ['ACTIVITY_DEPLOYMENT', false, true],
                    ['ACTIVITY_RETROSPECTIVE', false, false],
                    ['ACTIVITY_COACH', false, false],
                ],
                'earnings': ['EARNINGS_CARD_2', false],
            }
        },
        {
            label: 'Other player selects next activity', data: {
                'selection': false,
                'activities': [
                    ['ACTIVITY_CONFERENCE', false, false],
                    ['ACTIVITY_DEVELOPMENT', true, false],
                    ['ACTIVITY_DEPLOYMENT', true, false],
                    ['ACTIVITY_RETROSPECTIVE', false, false],
                    ['ACTIVITY_COACH', false, false],
                ],
                'earnings': ['EARNINGS_CARD_1', true],
            }
        },
    ];

    let selectedOption = options[0];
</script>

<div>
    <div>
        Backend data example:
        <select id="combo" bind:value={selectedOption}>
            {#each options as option}
                <option value={option}>{option.label}</option>
            {/each}
        </select>
    </div>
    <div class="preview">
        <CentralPanel datas={selectedOption.data}/>
    </div>
</div>

<style>
    .preview {
        margin-top: 10px;
        padding: 40px;
        border-style: solid;
        border-width: 2px;
        border-color: #dddddd;
    }
</style>