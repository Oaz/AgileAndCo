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
        AGILE_MATURITY_AGILE_ORGANIZER: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_ORGANIZER_TITLE),
                cost: 3, score: "2", activity: "CONFERENCE",
                description: _(Text.AGILE_MATURITY_AGILE_ORGANIZER_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_AGILE_ORGANIZER_GAIN)
            }
        },
        AGILE_MATURITY_FEEDBACK_SESSIONS: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_FEEDBACK_SESSIONS_TITLE),
                cost: 3, score: "2", activity: "RETROSPECTIVE",
                description: _(Text.AGILE_MATURITY_FEEDBACK_SESSIONS_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_FEEDBACK_SESSIONS_GAIN)
            }
        },
        AGILE_MATURITY_CLEAN_CODE: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_CLEAN_CODE_TITLE),
                cost: 3, score: "2", activity: "DEVELOPMENT",
                description: _(Text.AGILE_MATURITY_CLEAN_CODE_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_CLEAN_CODE_GAIN)
            }
        },
        AGILE_MATURITY_CONTINUOUS_DELIVERY: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_CONTINUOUS_DELIVERY_TITLE),
                cost: 3, score: "2", activity: "DEPLOYMENT",
                description: _(Text.AGILE_MATURITY_CONTINUOUS_DELIVERY_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_CONTINUOUS_DELIVERY_GAIN)
            }
        },
        AGILE_MATURITY_DEVOPS: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_DEVOPS_TITLE),
                cost: 3, score: "2", activity: "RETROSPECTIVE",
                description: _(Text.AGILE_MATURITY_DEVOPS_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_DEVOPS_GAIN)
            }
        },
        AGILE_MATURITY_AGILE_HR: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_HR_TITLE),
                cost: 4, score: "2", activity: "LEADER",
                description: _(Text.AGILE_MATURITY_AGILE_HR_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_AGILE_HR_GAIN)
            }
        },
        AGILE_MATURITY_ENGAGED_USERS: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_ENGAGED_USERS_TITLE),
                cost: 4, score: "2", activity: "DEPLOYMENT",
                description: _(Text.AGILE_MATURITY_ENGAGED_USERS_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_ENGAGED_USERS_GAIN)
            }
        },
        AGILE_MATURITY_INTERNAL_COACH: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_INTERNAL_COACH_TITLE),
                cost: 4, score: "2", activity: "RETROSPECTIVE",
                description: _(Text.AGILE_MATURITY_INTERNAL_COACH_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_INTERNAL_COACH_GAIN)
            }
        },
        AGILE_MATURITY_DETAILED_PLANNING: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_DETAILED_PLANNING_TITLE),
                cost: 1, score: "-4", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_DETAILED_PLANNING_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_NEGATIVE_GAIN)
            }
        },
        AGILE_MATURITY_TEST_TEAM: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_TEST_TEAM_TITLE),
                cost: 1, score: "-4", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_TEST_TEAM_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_NEGATIVE_GAIN)
            }
        },
        AGILE_MATURITY_APPLICATION_FRAMEWORK: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_APPLICATION_FRAMEWORK_TITLE),
                cost: 1, score: "-5", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_APPLICATION_FRAMEWORK_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_NEGATIVE_GAIN)
            }
        },
        AGILE_MATURITY_AGILE_CERTIFICATION: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_CERTIFICATION_TITLE),
                cost: 1, score: "-5", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_AGILE_CERTIFICATION_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_NEGATIVE_GAIN)
            }
        },
        AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP_TITLE),
                cost: 5, score: "*", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP_GAIN)
            }
        },
        AGILE_MATURITY_AGILE_SENSEI: {
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_SENSEI_TITLE),
                cost: 5, score: "*", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_AGILE_SENSEI_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_AGILE_SENSEI_GAIN)
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
        AGILE_VALUE_HUMOR: {
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_HUMOR_TITLE),
                description: _(Text.AGILE_VALUE_HUMOR_DESCRIPTION),
            }
        },
        AGILE_VALUE_FEEDBACK: {
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_FEEDBACK_TITLE),
                description: _(Text.AGILE_VALUE_FEEDBACK_DESCRIPTION),
            }
        },
        AGILE_VALUE_SIMPLICITY: {
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_SIMPLICITY_TITLE),
                description: _(Text.AGILE_VALUE_SIMPLICITY_DESCRIPTION),
            }
        },
        AGILE_VALUE_TRUST: {
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_TRUST_TITLE),
                description: _(Text.AGILE_VALUE_TRUST_DESCRIPTION),
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
        AGILE_VALUE_RESPECT: {
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_RESPECT_TITLE),
                description: _(Text.AGILE_VALUE_RESPECT_DESCRIPTION),
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


