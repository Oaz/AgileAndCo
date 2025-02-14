<script lang="ts">
    import {_, Text} from "./texts";
    import {Activity, NoActivity} from "./Activities/Activity";
    import {DevelopmentActivity} from "./Activities/DevelopmentActivity";
    import {DeploymentActivity} from "./Activities/DeploymentActivity";
    import {ConferenceActivity} from "./Activities/ConferenceActivity";
    import {RetrospectiveActivityChoice} from "./Activities/RetrospectiveActivityChoice";
    import {RetrospectiveActivityPayment} from "./Activities/RetrospectiveActivityPayment";
    import SelectableCardInActivity from "./SelectableCardInActivity.svelte";
    import ActionButton from "./ActionButton.svelte";

    export let datas;

    const activities = {
        ACTIVITY_DEVELOPMENT: DevelopmentActivity,
        ACTIVITY_DEPLOYMENT: DeploymentActivity,
        ACTIVITY_CONFERENCE: ConferenceActivity,
        ACTIVITY_RETROSPECTIVE_CHOOSE: RetrospectiveActivityChoice,
        ACTIVITY_RETROSPECTIVE_PAYMENT: RetrospectiveActivityPayment,
    }

    let activity: Activity;
    $: {
        const activityClass = activities[datas.activity];
        activity = activityClass === undefined ? new NoActivity(datas) : new activityClass(datas);
    }
</script>

{#key datas}
    <div class="board">
        <div class="header">
            <ActionButton bind:action={activity}/>
        </div>
        <div class="row">
            <div class="row-title">{_(Text.MY_COMPANY)}</div>
            <div class="company">
                <div class="teams">
                    {#each datas.teams as team, index}
                        <SelectableCardInActivity bind:activity={activity} zone_id={0} {index} card_key={team}/>
                        {#if datas.products[index]}
                            <SelectableCardInActivity bind:activity={activity} zone_id={1} {index}
                                                      card_key={datas.products[index]}
                                                      hidden={true}/>
                        {/if}
                    {/each}
                </div>
                <div class="other">
                    {#each datas.company as card_key, index}
                        <SelectableCardInActivity bind:activity={activity} zone_id={2} {index} {card_key}/>
                    {/each}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-title">{_(Text.MY_POTENTIAL)}</div>
            <div class="potential">
                {#each datas.potential as card_key, index}
                    <SelectableCardInActivity bind:activity={activity} zone_id={3} {index} {card_key}/>
                {/each}
                {#each datas.retrospective as card_key, index}
                    <SelectableCardInActivity bind:activity={activity} zone_id={5} {index} {card_key}/>
                {/each}
            </div>
        </div>
        {#if datas.conference.length > 0}
            <div class="row">
                <div class="row-title">{_(Text.ACTIVITY_CONFERENCE_TITLE)}</div>
                <div class="conference">
                    {#each datas.conference as card_key, index}
                        <SelectableCardInActivity bind:activity={activity} zone_id={4} {index} {card_key}/>
                    {/each}
                </div>
            </div>
        {/if}
    </div>
{/key}

<style>
    .row {
        border-style: solid;
        border-width: 2px;
        border-color: #565656;
    }

    .company {
        display: flex;
        flex-flow: row wrap;
        gap: 50px;
    }

    .potential {
        display: flex;
        flex-flow: row wrap;
    }

    .conference {
        display: flex;
        flex-flow: row wrap;
    }

    .row-title {
        text-align: left;
        font-family: "Arial", serif;
        font-size: 18pt;
        padding-left: 5px;
    }

    .board {
        zoom: 0.8;
    }
</style>