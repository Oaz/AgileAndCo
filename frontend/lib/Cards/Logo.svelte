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
