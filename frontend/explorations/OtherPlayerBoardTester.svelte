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