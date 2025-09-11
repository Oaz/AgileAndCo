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
    import OtherPlayerBoard from '../lib/OtherPlayerBoard.svelte';

    const players = [
        {
            'name': 'Player 1',
            'teams': [
                'PRODUCT_TEAM_ADVERGAME',
            ],
            'products': [
                false,
            ],
            'company': [],
        },
        {
            'name': 'Player 2',
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
        },
        {
            'name': 'Player 3',
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
        },
    ];
</script>

<div class="preview">
    <OtherPlayerBoard datas={players[0]}/>
    <OtherPlayerBoard datas={players[1]}/>
    <OtherPlayerBoard datas={players[2]}/>
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