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
    import DeckCard from '../lib/Cards/DeckCard.svelte';

</script>

<div style="zoom: 0.7">
    <div id="content">
        <DeckCard key="LEADER_CARD" />
        <DeckCard key="ACTIVITY_DEVELOPMENT" />
        <DeckCard key="ACTIVITY_DEPLOYMENT" />
        <DeckCard key="ACTIVITY_RETROSPECTIVE" />
        <DeckCard key="ACTIVITY_CONFERENCE" />
        <DeckCard key="ACTIVITY_COACH" />
    </div>
    <div id="content">
        <DeckCard key="EARNINGS_CARD_1" />
        <DeckCard key="EARNINGS_CARD_2" />
        <DeckCard key="EARNINGS_CARD_3" />
        <DeckCard key="EARNINGS_CARD_4" />
        <DeckCard key="PRODUCT_TEAM_ADVERGAME" />
        <DeckCard key="PRODUCT_TEAM_EDUCATION" />
        <DeckCard key="PRODUCT_TEAM_SOCIAL" />
        <DeckCard key="PRODUCT_TEAM_MMOG" />
    </div>
    <div id="content">
        <DeckCard key="AGILE_VALUE_HUMOR" />
        <DeckCard key="AGILE_VALUE_FEEDBACK" />
        <DeckCard key="AGILE_VALUE_SIMPLICITY" />
        <DeckCard key="AGILE_VALUE_FOCUS" />
        <DeckCard key="AGILE_VALUE_OPENNESS" />
        <DeckCard key="AGILE_VALUE_COURAGE" />
        <DeckCard key="AGILE_VALUE_RESPECT" />
    </div>
    <div id="content">
        <DeckCard key="AGILE_MATURITY_PASSIONATE_DEVELOPER" />
        <DeckCard key="AGILE_MATURITY_AGILE_PRACTITIONER" />
        <DeckCard key="AGILE_MATURITY_USER_EXPERIENCE" />
        <DeckCard key="AGILE_MATURITY_PAIR_PROGRAMMING" />
        <DeckCard key="AGILE_MATURITY_AGILE_ORGANIZER" />
        <DeckCard key="AGILE_MATURITY_FEEDBACK_SESSIONS" />
        <DeckCard key="AGILE_MATURITY_CLEAN_CODE" />
        <DeckCard key="AGILE_MATURITY_CONTINUOUS_DELIVERY" />
        <DeckCard key="AGILE_MATURITY_DEVOPS" />
    </div>
    <div id="content">
        <DeckCard key="AGILE_MATURITY_AGILE_HR" />
        <DeckCard key="AGILE_MATURITY_ENGAGED_USERS" />
        <DeckCard key="AGILE_MATURITY_INTERNAL_COACH" />
        <DeckCard key="AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP" />
        <DeckCard key="AGILE_MATURITY_AGILE_SENSEI" />
        <DeckCard key="AGILE_MATURITY_PRODUCT_VISION" />
        <DeckCard key="AGILE_MATURITY_DETAILED_PLANNING" />
        <DeckCard key="AGILE_MATURITY_TEST_TEAM" />
        <DeckCard key="AGILE_MATURITY_APPLICATION_FRAMEWORK" />
        <DeckCard key="AGILE_MATURITY_AGILE_CERTIFICATION" />
    </div>
</div>

<style>
    #content {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }
</style>