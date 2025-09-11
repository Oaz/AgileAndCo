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
            label: 'Nothing to discard', data: {
                'activity': 'END_OF_ROUND',
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
                ]
            }
        },
        {
            label: 'Must discard 1 to limit potential to 6 cards', data: {
                'activity': 'END_OF_ROUND',
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
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_MATURITY_AGILE_CERTIFICATION'
                ]
            }
        },
        {
            label: 'Must discard 2 to limit potential to 6 cards', data: {
                'activity': 'END_OF_ROUND',
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
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
                    'AGILE_MATURITY_DEVOPS',
                ]
            }
        },
        {
            label: 'Must discard 1 to limit potential to 7 cards', data: {
                'activity': 'END_OF_ROUND',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_VALUE_COURAGE'
                ],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
                    'AGILE_MATURITY_DEVOPS',
                ]
            }
        },
        {
            label: 'Must discard 2 to limit potential to 8 cards', data: {
                'activity': 'END_OF_ROUND',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_VALUE_COURAGE',
                    'AGILE_VALUE_SIMPLICITY'
                ],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
                    'AGILE_MATURITY_DEVOPS',
                    'AGILE_MATURITY_AGILE_HR',
                    'AGILE_MATURITY_PAIR_PROGRAMMING',
                ]
            }
        },
        {
            label: 'Must discard 1 to limit potential to 10 cards', data: {
                'activity': 'END_OF_ROUND',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_HR',
                ],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
                    'AGILE_MATURITY_DEVOPS',
                    'AGILE_MATURITY_AGILE_HR',
                    'AGILE_VALUE_SIMPLICITY',
                    'AGILE_MATURITY_PAIR_PROGRAMMING',
                ]
            }
        },
        {
            label: 'Must discard 1 to limit potential to 11 cards', data: {
                'activity': 'END_OF_ROUND',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_HR',
                    'AGILE_VALUE_SIMPLICITY'
                ],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
                    'AGILE_MATURITY_DEVOPS',
                    'AGILE_MATURITY_AGILE_HR',
                    'AGILE_VALUE_SIMPLICITY',
                    'AGILE_MATURITY_PAIR_PROGRAMMING',
                    'AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP'
                ]
            }
        }
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