<script>
    import PlayerBoard from '../lib/PlayerBoard.svelte';

    const options = [
        {
            label: 'Nothing to dismiss', data: {
                'activity': 'END_OF_TURN',
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
                ]
            }
        },
        {
            label: 'Must dismiss 1 to limit potential to 6 cards', data: {
                'activity': 'END_OF_TURN',
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
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_MATURITY_AGILE_CERTIFICATION'
                ]
            }
        },
        {
            label: 'Must dismiss 2 to limit potential to 6 cards', data: {
                'activity': 'END_OF_TURN',
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
                    'AGILE_MATURITY_INTERNAL_COACH',
                    'AGILE_MATURITY_CLEAN_CODE',
                    'AGILE_VALUE_OPENNESS',
                    'AGILE_MATURITY_AGILE_CERTIFICATION',
                    'AGILE_MATURITY_DEVOPS',
                ]
            }
        }
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