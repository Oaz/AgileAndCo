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
    import {onMount} from 'svelte';
    import {addImageinRootCss} from '../images';

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
        ADVERGAME: '--image-advergame-logo',
        EDUCATION: '--image-education-logo',
        SOCIAL: '--image-social-logo',
        MMOG: '--image-mmog-logo',
        RETROSPECTIVE: '--image-retrospective-logo',
        CONFERENCE: '--image-conference-logo',
        DEPLOYMENT: '--image-deployment-logo',
        DEVELOPMENT: '--image-development-logo',
        COACH: '--image-coach-logo',
        THEEND: '--image-theend-logo',
        LEADER: '--image-leader-logo',
    };

    onMount(() => {
        addImageinRootCss('--image-advergame-logo', advergameLogo);
        addImageinRootCss('--image-education-logo', educationLogo);
        addImageinRootCss('--image-social-logo', socialLogo);
        addImageinRootCss('--image-mmog-logo', mmogLogo);
        addImageinRootCss('--image-retrospective-logo', retrospectiveLogo);
        addImageinRootCss('--image-conference-logo', conferenceLogo);
        addImageinRootCss('--image-deployment-logo', deploymentLogo);
        addImageinRootCss('--image-development-logo', developmentLogo);
        addImageinRootCss('--image-coach-logo', coachLogo);
        addImageinRootCss('--image-theend-logo', theendLogo);
        addImageinRootCss('--image-leader-logo', leaderLogo);
    });

    export let key;
    const logoVariable = logos[key];

</script>

<style>
    .logo {
        background-image: var(--logo-placeholder);
        background-repeat: no-repeat;
        background-size: contain;
        width: 100%;
        height: 100%;
    }
</style>

<div class="logo" style="--logo-placeholder: var({logoVariable})">
</div>
