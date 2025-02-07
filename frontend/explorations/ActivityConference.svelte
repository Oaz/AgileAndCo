<script>
    import PlayerBoard from '../lib/PlayerBoard.svelte';

    const options = [
        {
            label: 'Nominal case', data: {
                'activity': 'ACTIVITY_CONFERENCE',
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
                'conference': [
                    'PRODUCT_TEAM_SOCIAL',
                    'AGILE_MATURITY_INTERNAL_COACH',
                ],
            }
        },
        {
            label: 'Activity initiator', data: {
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
                'conference': [
                    'PRODUCT_TEAM_SOCIAL',
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_TRANSPARENCY',
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
                ],
            }
        },
        {
            label: 'Practitioner can discard from potential', data: {
                'activity': 'ACTIVITY_CONFERENCE',
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', undefined]
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                ],
                'potential': [
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_DEVOPS',
                ],
                'conference': [
                    'PRODUCT_TEAM_SOCIAL',
                    'AGILE_MATURITY_INTERNAL_COACH',
                ],
            }
        },
        {
            label: 'Organizer keep one extra card', data: {
                'activity': 'ACTIVITY_CONFERENCE',
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', undefined]
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                ],
                'potential': [
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_DEVOPS',
                ],
                'conference': [
                    'PRODUCT_TEAM_SOCIAL',
                    'AGILE_MATURITY_INTERNAL_COACH',
                ],
            }
        },
        {
            label: 'Practitioner+Organizer+Activity initiator', data: {
                'activity': 'ACTIVITY_CONFERENCE',
                'initiate': true,
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', undefined]
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                ],
                'potential': [
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_DEVOPS',
                ],
                'conference': [
                    'PRODUCT_TEAM_SOCIAL',
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_VALUE_SIMPLICITY',
                    'AGILE_VALUE_COURAGE',
                    'AGILE_MATURITY_AGILE_HR',
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