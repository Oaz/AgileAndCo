<script>
    import CentralPanel from '../lib/CentralPanel.svelte';

    const options = [
        {
            label: 'Start of turn: select activity', data: {
                'selection': true,
                'activities': [
                    ['ACTIVITY_CONFERENCE', false, false],
                    ['ACTIVITY_DEVELOPMENT', false, false],
                    ['ACTIVITY_DEPLOYMENT', false, false],
                    ['ACTIVITY_RETROSPECTIVE', false, false],
                    ['ACTIVITY_COACH', false, false],
                ],
                'earnings': ['EARNINGS_CARD_1', true],
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
        <CentralPanel datas={selectedOption.data}/>
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