<script>
    import PlayerBoard from '../lib/PlayerBoard.svelte';

    const options = [
        {
            label: 'One team', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
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
            label: 'Two teams but only one can develop', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_EDUCATION',
                ],
                'products': [
                    false,
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_DEVOPS',
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
            label: 'Initiator can develop additional product', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
                'initiate': true,
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_EDUCATION',
                ],
                'products': [
                    false,
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_DEVOPS',
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
            label: 'Clean code can develop additional product', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_EDUCATION',
                ],
                'products': [
                    false,
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
            label: 'Could develop 2 products but miss potential', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_EDUCATION',
                ],
                'products': [
                    false,
                    false,
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
        {
            label: 'Cannot develop before previous deployment', data: {
                'activity': 'ACTIVITY_DEVELOPMENT',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_EDUCATION',
                ],
                'products': [
                    false,
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
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