<script lang="ts">
    import {_, Text} from "./texts";
    import ProductTeamCard from './ProductTeamCard.svelte';
    import AgileMaturityCard from './AgileMaturityCard.svelte';
    import AgileValueCard from './AgileValueCard.svelte';
    import ActivityCard from './ActivityCard.svelte';
    import EarningsCard from './EarningsCard.svelte';
    import LeaderCard from './LeaderCard.svelte';

    const componentData = {
        PRODUCT_TEAM_ADVERGAME: {
            component: ProductTeamCard,
            props: {title: "ADVERGAME", cost: 1, score: 1, description: _(Text.PRODUCT_TEAM_ADVERGAME)}
        },
        PRODUCT_TEAM_EDUCATION: {
            component: ProductTeamCard,
            props: {title: "EDUCATION", cost: 2, score: 1, description: _(Text.PRODUCT_TEAM_EDUCATION)}
        },
        PRODUCT_TEAM_SOCIAL: {
            component: ProductTeamCard,
            props: {title: "SOCIAL", cost: 3, score: 2, description: _(Text.PRODUCT_TEAM_SOCIAL)}
        },
        PRODUCT_TEAM_MMOG: {
            component: ProductTeamCard,
            props: {title: "MMOG", cost: 4, score: 2, description: _(Text.PRODUCT_TEAM_MMOG)}
        },
        AGILE_MATURITY_PASSIONATE_DEVELOPER: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_PASSIONATE_DEVELOPER_TITLE),
                cost: 1, score: 1, activity: "RETROSPECTIVE",
                description: _(Text.AGILE_MATURITY_PASSIONATE_DEVELOPER_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_PASSIONATE_DEVELOPER_GAIN)
            }
        },
        AGILE_MATURITY_AGILE_PRACTITIONER: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_PRACTITIONER_TITLE),
                cost: 1, score: 1, activity: "CONFERENCE",
                description: _(Text.AGILE_MATURITY_AGILE_PRACTITIONER_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_AGILE_PRACTITIONER_GAIN)
            }
        },
        AGILE_MATURITY_USER_EXPERIENCE: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_USER_EXPERIENCE_TITLE),
                cost: 2, score: 1, activity: "DEPLOYMENT",
                description: _(Text.AGILE_MATURITY_USER_EXPERIENCE_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_USER_EXPERIENCE_GAIN)
            }
        },
        AGILE_MATURITY_PAIR_PROGRAMMING: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_PAIR_PROGRAMMING_TITLE),
                cost: 2, score: 1, activity: "DEVELOPMENT",
                description: _(Text.AGILE_MATURITY_PAIR_PROGRAMMING_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_PAIR_PROGRAMMING_GAIN)
            }
        },
        AGILE_MATURITY_PRODUCT_VISION: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_PRODUCT_VISION_TITLE),
                cost: 5, score: "*", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_PRODUCT_VISION_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_PRODUCT_VISION_GAIN)
            }
        },
        AGILE_VALUE_SIMPLICITY: {
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_SIMPLICITY_TITLE),
                description: _(Text.AGILE_VALUE_SIMPLICITY_DESCRIPTION),
            }
        },
        AGILE_VALUE_TRANSPARENCY: {
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_TRANSPARENCY_TITLE),
                description: _(Text.AGILE_VALUE_TRANSPARENCY_DESCRIPTION),
            }
        },
        AGILE_VALUE_COURAGE: {
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_COURAGE_TITLE),
                description: _(Text.AGILE_VALUE_COURAGE_DESCRIPTION),
            }
        },
        ACTIVITY_DEVELOPMENT: {
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_DEVELOPMENT_TITLE),
                logo: "DEVELOPMENT",
                description: _(Text.ACTIVITY_DEVELOPMENT_DESCRIPTION),
                privilege: _(Text.ACTIVITY_DEVELOPMENT_PRIVILEGE),
            }
        },
        ACTIVITY_DEPLOYMENT: {
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_DEPLOYMENT_TITLE),
                logo: "DEPLOYMENT",
                description: _(Text.ACTIVITY_DEPLOYMENT_DESCRIPTION),
                privilege: _(Text.ACTIVITY_DEPLOYMENT_PRIVILEGE),
            }
        },
        ACTIVITY_RETROSPECTIVE: {
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_RETROSPECTIVE_TITLE),
                logo: "RETROSPECTIVE",
                description: _(Text.ACTIVITY_RETROSPECTIVE_DESCRIPTION),
                privilege: _(Text.ACTIVITY_RETROSPECTIVE_PRIVILEGE),
            }
        },
        ACTIVITY_CONFERENCE: {
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_CONFERENCE_TITLE),
                logo: "CONFERENCE",
                description: _(Text.ACTIVITY_CONFERENCE_DESCRIPTION),
                privilege: _(Text.ACTIVITY_CONFERENCE_PRIVILEGE),
            }
        },
        ACTIVITY_COACH: {
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_COACH_TITLE),
                logo: "COACH",
                description: _(Text.ACTIVITY_COACH_DESCRIPTION),
                privilege: _(Text.ACTIVITY_COACH_PRIVILEGE)
            }
        },
        EARNINGS_CARD_1: {
            component: EarningsCard,
            props: {advergame: 2, education: 2, social: 3, mmog: 3}
        },
        EARNINGS_CARD_2: {
            component: EarningsCard,
            props: {advergame: 2, education: 2, social: 3, mmog: 4}
        },
        EARNINGS_CARD_3: {
            component: EarningsCard,
            props: {advergame: 2, education: 3, social: 3, mmog: 4}
        },
        EARNINGS_CARD_4: {
            component: EarningsCard,
            props: {advergame: 2, education: 3, social: 4, mmog: 4}
        },
        LEADER_CARD: {
            component: LeaderCard,
            props: {}
        }
    };

    export let key;

    const {component, props} = componentData[key] || {};
</script>

{#if component}
    <svelte:component this={component} {...props}/>
{:else}
    <p>Component with key "{key}" not found.</p>
{/if}


