<!--
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

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
    import {EndOfRound} from "./Activities/EndOfRound";

    export let datas;

    const activities = {
        ACTIVITY_DEVELOPMENT: DevelopmentActivity,
        ACTIVITY_DEPLOYMENT: DeploymentActivity,
        ACTIVITY_CONFERENCE: ConferenceActivity,
        ACTIVITY_RETROSPECTIVE_CHOOSE: RetrospectiveActivityChoice,
        ACTIVITY_RETROSPECTIVE_PAYMENT: RetrospectiveActivityPayment,
        END_OF_ROUND: EndOfRound,
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