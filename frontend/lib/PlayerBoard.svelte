<script lang="ts">
    import {_, Text} from "./texts";
    import {Activity, NoActivity} from "./Activities/Activity";
    import {DeploymentActivity} from "./Activities/DeploymentActivity";
    import {ConferenceActivity} from "./Activities/ConferenceActivity";
    import SelectableCardInActivity from "./SelectableCardInActivity.svelte";
    import ActivityAction from "./ActivityAction.svelte";

    export let datas;

    const activities = {
        ACTIVITY_DEPLOYMENT: DeploymentActivity,
        ACTIVITY_CONFERENCE: ConferenceActivity,
    }

    let activity: Activity;
    $: {
        const activityClass = activities[datas.activity];
        activity = activityClass === undefined ? new NoActivity(datas) : new activityClass(datas);
    }
</script>

{#key datas}
    <div class="board">
        <ActivityAction bind:activity={activity}/>
        <div class="row">
            <div class="row-title">{_(Text.MY_COMPANY)}</div>
            <div class="company">
                <div class="teams">
                    {#each datas.teams as team, index}
                        <SelectableCardInActivity bind:activity={activity} zone_id={0} {index} card_key={team[0]}/>
                        {#if team[1]}
                            <SelectableCardInActivity bind:activity={activity} zone_id={1} {index} card_key={team[1]}
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
            </div>
        </div>
        {#if datas.drawn}
            <div class="row">
                <div class="row-title">{activity.drawn_card_zone_title}</div>
                <div class="drawn">
                    {#each datas.drawn as card_key, index}
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

    .drawn {
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