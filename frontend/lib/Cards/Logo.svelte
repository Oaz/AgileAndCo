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

    function getAsBase64(content) {
        if (content.startsWith('data:image/svg+xml;base64,'))
            return content;
        if (!content.startsWith('data:image/svg+xml,'))
            return content;
        const prefix = 'data:image/svg+xml,';
        const svgContent = decodeURIComponent(content.substring(prefix.length));
        const base64Content = btoa(svgContent);
        return 'data:image/svg+xml;base64,' + base64Content;
    }
    function add(name, content) {
        const root = document.documentElement;
        if (root.style.getPropertyValue(name))
            return;
        let url = getAsBase64(content);
        root.style.setProperty(name, `url(${url})`);
    }

    onMount(() => {
        add('--image-advergame-logo', advergameLogo);
        add('--image-education-logo', educationLogo);
        add('--image-social-logo', socialLogo);
        add('--image-mmog-logo', mmogLogo);
        add('--image-retrospective-logo', retrospectiveLogo);
        add('--image-conference-logo', conferenceLogo);
        add('--image-deployment-logo', deploymentLogo);
        add('--image-development-logo', developmentLogo);
        add('--image-coach-logo', coachLogo);
        add('--image-theend-logo', theendLogo);
        add('--image-leader-logo', leaderLogo);
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
