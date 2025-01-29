<script>
    import PlayerBoard from '../lib/PlayerBoard.svelte';

    const options = [
        {
            label: 'Nothing to deploy', data: {
                'activity': 'ACTIVITY_DEPLOYMENT',
                'deployment_max': 1,
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
            label: 'With developed product', data: {
                'activity': 'ACTIVITY_DEPLOYMENT',
                'deployment_max': 1,
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', 'AGILE_MATURITY_AGILE_CERTIFICATION'],
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
            label: 'With limit', data: {
                'activity': 'ACTIVITY_DEPLOYMENT',
                'deployment_max': 1,
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', 'AGILE_MATURITY_AGILE_CERTIFICATION'],
                    ['PRODUCT_TEAM_EDUCATION', undefined],
                    ['PRODUCT_TEAM_MMOG', 'AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP'],
                ],
                'company': [
                    'AGILE_MATURITY_DEVOPS',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_SIMPLICITY',
                    'AGILE_VALUE_COURAGE',
                    'AGILE_MATURITY_AGILE_HR',
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
            label: 'With higher limit', data: {
                'activity': 'ACTIVITY_DEPLOYMENT',
                'deployment_max': 2,
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', 'AGILE_MATURITY_AGILE_CERTIFICATION'],
                    ['PRODUCT_TEAM_EDUCATION', 'AGILE_MATURITY_AGILE_CERTIFICATION'],
                    ['PRODUCT_TEAM_MMOG', 'AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP'],
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
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_TRANSPARENCY',
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