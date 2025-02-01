<script>
    import PlayerBoard from '../lib/PlayerBoard.svelte';

    const options = [
        {
            label: 'One team', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
                'select_max': 2,
                'select_zones': [0, 3],
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
            }
        },
        {
            label: 'Two teams but only one can develop', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
                'select_max': 2,
                'select_zones': [0, 3],
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', undefined],
                    ['PRODUCT_TEAM_EDUCATION', undefined],
                ],
                'company': [
                    'AGILE_MATURITY_DEVOPS',
                ],
                'potential': [
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_TRANSPARENCY',
                ],
            }
        },
        {
            label: 'Two teams & can develop 2 products', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
                'select_max': 4,
                'select_zones': [0, 3],
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', undefined],
                    ['PRODUCT_TEAM_EDUCATION', undefined],
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
                    'AGILE_VALUE_TRANSPARENCY',
                ],
            }
        },
        {
            label: 'Could develop 2 products but miss potential', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
                'select_max': 4,
                'select_zones': [0, 3],
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', undefined],
                    ['PRODUCT_TEAM_EDUCATION', undefined],
                ],
                'company': [
                    'AGILE_MATURITY_DEVOPS',
                    'AGILE_MATURITY_CLEAN_CODE',
                ],
                'potential': [
                    'AGILE_VALUE_FEEDBACK',
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