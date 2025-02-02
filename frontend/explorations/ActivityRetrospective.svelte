<script>
    import PlayerBoard from '../lib/PlayerBoard.svelte';

    const options = [
        {
            label: 'Choose improvement', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_CHOOSE',
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', undefined]
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                ],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_MATURITY_PASSIONATE_DEVELOPER',
                    'AGILE_MATURITY_USER_EXPERIENCE',
                ],
            }
        },
        {
            label: 'Some improvements are too expensive', data: {
                'activity': 'ACTIVITY_RETROSPECTIVE_CHOOSE',
                'teams': [
                    ['PRODUCT_TEAM_ADVERGAME', undefined]
                ],
                'company': [
                    'AGILE_MATURITY_AGILE_ORGANIZER',
                ],
                'potential': [
                    'AGILE_MATURITY_AGILE_PRACTITIONER',
                    'AGILE_VALUE_FEEDBACK',
                    'PRODUCT_TEAM_MMOG',
                    'AGILE_MATURITY_DEVOPS',
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