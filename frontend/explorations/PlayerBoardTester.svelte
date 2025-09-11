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
    import PlayerBoard from '../lib/PlayerBoard.svelte';

    const options = [
        {
            label: 'Initial state', data: {
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_DEVOPS',
                ],
            }
        },
        {
            label: 'With developed product', data: {
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_EDUCATION',
                ],
                'products': [
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_DEVOPS',
                    'AGILE_MATURITY_CLEAN_CODE',
                ],
                'potential': [
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_OPENNESS',
                ],
            }
        },
        {
            label: 'With other developed product', data: {
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_EDUCATION',
                ],
                'products': [
                    false,
                    'AGILE_MATURITY_AGILE_CERTIFICATION'
                ],
                'company': [
                    'AGILE_MATURITY_DEVOPS',
                    'AGILE_MATURITY_CLEAN_CODE',
                ],
                'potential': [
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_OPENNESS',
                ],
            }
        },
        {
            label: 'With a lot of cards', data: {
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_EDUCATION',
                    'PRODUCT_TEAM_MMOG',
                ],
                'products': [
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
                    false,
                    'AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP',
                ],
                'company': [
                    'AGILE_MATURITY_DEVOPS',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_MATURITY_CONTINUOUS_DELIVERY',
                    'AGILE_VALUE_SIMPLICITY',
                    'AGILE_VALUE_COURAGE',
                    'AGILE_MATURITY_AGILE_HR',
                ],
                'potential': [
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_MATURITY_ENGAGED_USERS',
                    'PRODUCT_TEAM_ADVERGAME',
                    'AGILE_MATURITY_ENGAGED_USERS',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_VALUE_RESPECT',
                    'AGILE_MATURITY_TEST_TEAM',
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_APPLICATION_FRAMEWORK',
                    'PRODUCT_TEAM_ADVERGAME',
                ],
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
        <PlayerBoard datas={selectedOption.data}/>
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