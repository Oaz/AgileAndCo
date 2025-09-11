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

<script>
    import advergameLogo from '../../images/ADVERGAME.svg';
    import educationLogo from '../../images/EDUCATION.svg';
    import socialLogo from '../../images/SOCIAL.svg';
    import mmogLogo from '../../images/MMOG.svg';
    import retrospectiveLogo from '../../images/RETROSPECTIVE.svg';
    import conferenceLogo from '../../images/CONFERENCE.svg';
    import deploymentLogo from '../../images/DEPLOYMENT.svg';
    import developmentLogo from '../../images/DEVELOPMENT.svg';
    import coachLogo from '../../images/COACH.svg';
    import theendLogo from '../../images/THEEND.svg';
    import leaderLogo from '../../images/LEADER.svg';

    const logos = {
        ADVERGAME: advergameLogo,
        EDUCATION: educationLogo,
        SOCIAL: socialLogo,
        MMOG: mmogLogo,
        RETROSPECTIVE: retrospectiveLogo,
        CONFERENCE: conferenceLogo,
        DEPLOYMENT: deploymentLogo,
        DEVELOPMENT: developmentLogo,
        COACH: coachLogo,
        THEEND: theendLogo,
        LEADER: leaderLogo,
    };

    export let key;
    const logoUrl = logos[key];

</script>

<img class="logo" src={logoUrl} alt="Logo" />
