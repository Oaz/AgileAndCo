<script lang="ts">
    import GameBoard from '../lib/GameBoard.svelte';
    import {onMount} from "svelte";

    const options = [
            {
                label: 'Start of game', data: {
                    'public': {
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
                        'players': {
                            1: {
                                'name': 'Player 1',
                                'teams': [
                                    'PRODUCT_TEAM_ADVERGAME',
                                ],
                                'products': [
                                    false,
                                ],
                                'company': [],
                            },
                            2: {
                                'name': 'Player 2',
                                'teams': [
                                    'PRODUCT_TEAM_ADVERGAME',
                                ],
                                'products': [
                                    false,
                                ],
                                'company': [],
                            },
                            3: {
                                'name': 'Player 3',
                                'teams': [
                                    'PRODUCT_TEAM_ADVERGAME',
                                ],
                                'products': [
                                    false,
                                ],
                                'company': [],
                            },
                        }
                    },
                    '_private': {
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
                    },

                }
            },
            {
                label: 'You selected conference', data: {
                    'public': {
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
                        'players': {
                            1: {
                                'name': 'Player 1',
                                'teams': [
                                    'PRODUCT_TEAM_ADVERGAME',
                                ],
                                'products': [
                                    false,
                                ],
                                'company': [],
                            },
                            2: {
                                'name': 'Player 2',
                                'teams': [
                                    'PRODUCT_TEAM_ADVERGAME',
                                ],
                                'products': [
                                    false,
                                ],
                                'company': [],
                            },
                            3: {
                                'name': 'Player 3',
                                'teams': [
                                    'PRODUCT_TEAM_ADVERGAME',
                                ],
                                'products': [
                                    false,
                                ],
                                'company': [],
                            },
                        }
                    },
                    '_private': {
                        'activity': 'ACTIVITY_CONFERENCE',
                        'initiate': true,
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
                        'conference': [
                            'PRODUCT_TEAM_SOCIAL',
                            'AGILE_MATURITY_INTERNAL_COACH',
                            'AGILE_MATURITY_CLEAN_CODE',
                            'AGILE_VALUE_TRANSPARENCY',
                            'AGILE_MATURITY_AGILE_CERTIFICATION',
                        ],
                    },

                }
            },
        ]
    ;

    let selectedOption = options[0];
    let board: GameBoard;

    $ : {
        if (board !== undefined) {
            board.update_public(selectedOption.data.public);
            board.update_private(selectedOption.data._private);
        }
    }
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
        <GameBoard player_id={2} bind:this={board}/>
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