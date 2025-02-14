<script>
    import PlayerBoard from '../lib/PlayerBoard.svelte';

    const options = [
        {
            label: 'Choose improvement', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_CHOOSE',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                ],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_MATURITY_PASSIONATE_DEVELOPER',
                    'AGILE_MATURITY_USER_EXPERIENCE',
                ],
                'retrospective': [
                ],
            }
        },
        {
            label: 'Pay improvement', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_PAYMENT',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                ],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_MATURITY_PASSIONATE_DEVELOPER',
                ],
                'retrospective': [
                    'AGILE_MATURITY_USER_EXPERIENCE',
                ],
            }
        },
        {
            label: 'Some improvements are too expensive', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_CHOOSE',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                ],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_CONTINUOUS_DELIVERY',
                ],
                'retrospective': [
                ],
            }
        },
        {
            label: 'Initiator pays one less', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_CHOOSE',
                'initiate': true,
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                ],
                'potential': [
                    'AGILE_MATURITY_PRODUCT_VISION',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_CONTINUOUS_DELIVERY',
                ],
                'retrospective': [
                ],
            }
        },
        {
            label: 'Internal coach pays one less for improvements', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_CHOOSE',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                    'AGILE_MATURITY_INTERNAL_COACH',
                ],
                'potential': [
                    'AGILE_MATURITY_PRODUCT_VISION',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_CONTINUOUS_DELIVERY',
                ],
                'retrospective': [
                ],
            }
        },
        {
            label: 'Passionate developer pays one less for teams', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_CHOOSE',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    false,
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                    'AGILE_MATURITY_PASSIONATE_DEVELOPER',
                ],
                'potential': [
                    'AGILE_MATURITY_PRODUCT_VISION',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_CONTINUOUS_DELIVERY',
                ],
                'retrospective': [
                ],
            }
        },
        {
            label: 'Devops can use non deployed products', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_CHOOSE',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    'PRODUCT_TEAM_MMOG',
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                    'AGILE_MATURITY_DEVOPS',
                ],
                'potential': [
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_CONTINUOUS_DELIVERY',
                ],
                'retrospective': [
                ],
            }
        },
        {
            label: 'Devops can use all non deployed products', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_CHOOSE',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_CONTINUOUS_DELIVERY',
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                    'AGILE_MATURITY_DEVOPS',
                ],
                'potential': [
                    'AGILE_MATURITY_PRODUCT_VISION',
                    'PRODUCT_TEAM_MMOG',
                ],
                'retrospective': [
                ],
            }
        },
        {
            label: 'Pay improvement with undeployed products', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_PAYMENT',
                'teams': [
                    'PRODUCT_TEAM_ADVERGAME',
                    'PRODUCT_TEAM_ADVERGAME',
                ],
                'products': [
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_CONTINUOUS_DELIVERY',
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                    'AGILE_MATURITY_DEVOPS',
                    'AGILE_MATURITY_PASSIONATE_DEVELOPER',
                ],
                'potential': [
                    'AGILE_MATURITY_PRODUCT_VISION',
                ],
                'retrospective': [
                    'PRODUCT_TEAM_MMOG',
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