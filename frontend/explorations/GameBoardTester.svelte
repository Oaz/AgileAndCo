<script>
    import GameBoard from '../lib/GameBoard.svelte';

    const options = [
            {
                label: 'Start of game', data: {
                    'central': {
                        'selection': true,
                        'activities': [
                            ['ACTIVITY_CONFERENCE', false, false],
                            ['ACTIVITY_DEVELOPMENT', false, false],
                            ['ACTIVITY_DEPLOYMENT', false, false],
                            ['ACTIVITY_RETROSPECTIVE', false, false],
                            ['ACTIVITY_COACH', false, false],
                        ],
                        'earnings': ['EARNINGS_CARD_1', true],
                    },
                    'myself': {
                        'teams': [
                            ['PRODUCT_TEAM_ADVERGAME', undefined]
                        ],
                        'company': [],
                        'potential': [
                            'AGILE_MATURITY_AGILE_PRACTITIONER',
                            'AGILE_VALUE_FEEDBACK',
                            'PRODUCT_TEAM_MMOG',
                            'AGILE_MATURITY_DEVOPS',
                        ],
                    },
                    'others': [
                        {
                            'name': 'Player 1',
                            'teams': [
                                ['PRODUCT_TEAM_ADVERGAME', undefined]
                            ],
                            'company': [],
                        },
                        {
                            'name': 'Player 2',
                            'teams': [
                                ['PRODUCT_TEAM_ADVERGAME', undefined]
                            ],
                            'company': [],
                        },
                        {
                            'name': 'Player 3',
                            'teams': [
                                ['PRODUCT_TEAM_ADVERGAME', undefined]
                            ],
                            'company': [],
                        },
                    ]
                }
            },
            {
                label: 'You selected conference', data: {
                    'central': {
                        'selection': false,
                        'activities': [
                            ['ACTIVITY_CONFERENCE', false, true],
                            ['ACTIVITY_DEVELOPMENT', false, false],
                            ['ACTIVITY_DEPLOYMENT', false, false],
                            ['ACTIVITY_RETROSPECTIVE', false, false],
                            ['ACTIVITY_COACH', false, false],
                        ],
                        'earnings': ['EARNINGS_CARD_1', true],
                    },
                    'myself': {
                        'activity': 'ACTIVITY_CONFERENCE',
                        'initiate': true,
                        'teams': [
                            ['PRODUCT_TEAM_ADVERGAME', undefined]
                        ],
                        'company': [],
                        'potential': [
                            'AGILE_MATURITY_AGILE_PRACTITIONER',
                            'AGILE_VALUE_FEEDBACK',
                            'PRODUCT_TEAM_MMOG',
                            'AGILE_MATURITY_DEVOPS',
                        ],
                        'drawn': [
                            'PRODUCT_TEAM_SOCIAL',
                            'AGILE_MATURITY_INTERNAL_COACH',
                            'AGILE_MATURITY_CLEAN_CODE',
                            'AGILE_VALUE_TRANSPARENCY',
                            'AGILE_MATURITY_AGILE_CERTIFICATION',
                        ],
                    },
                    'others': [
                        {
                            'name': 'Player 1',
                            'teams': [
                                ['PRODUCT_TEAM_ADVERGAME', undefined]
                            ],
                            'company': [],
                        },
                        {
                            'name': 'Player 2',
                            'teams': [
                                ['PRODUCT_TEAM_ADVERGAME', undefined]
                            ],
                            'company': [],
                        },
                        {
                            'name': 'Player 3',
                            'teams': [
                                ['PRODUCT_TEAM_ADVERGAME', undefined]
                            ],
                            'company': [],
                        },
                    ]
                }
            },
        ]
    ;

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
        <GameBoard datas={selectedOption.data}/>
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