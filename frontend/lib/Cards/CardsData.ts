import {_, Text} from "../texts";
import ProductTeamCard from './ProductTeamCard.svelte';
import AgileMaturityCard from './AgileMaturityCard.svelte';
import AgileValueCard from './AgileValueCard.svelte';
import ActivityCard from './ActivityCard.svelte';
import EarningsCard from './EarningsCard.svelte';
import LeaderCard from './LeaderCard.svelte';

export type CardKind = 'PRODUCT_TEAM' | 'AGILE_MATURITY' | 'AGILE_VALUE' | 'ACTIVITY' | 'EARNINGS' | 'LEADER';

export const cardsData = () => {
    return {
        PRODUCT_TEAM_ADVERGAME: {
            kind: 'PRODUCT_TEAM',
            component: ProductTeamCard,
            props: {title: "ADVERGAME", cost: 1, score: 1, description: _(Text.PRODUCT_TEAM_ADVERGAME)}
        },
        PRODUCT_TEAM_EDUCATION: {
            kind: 'PRODUCT_TEAM',
            component: ProductTeamCard,
            props: {title: "EDUCATION", cost: 2, score: 1, description: _(Text.PRODUCT_TEAM_EDUCATION)}
        },
        PRODUCT_TEAM_SOCIAL: {
            kind: 'PRODUCT_TEAM',
            component: ProductTeamCard,
            props: {title: "SOCIAL", cost: 3, score: 2, description: _(Text.PRODUCT_TEAM_SOCIAL)}
        },
        PRODUCT_TEAM_MMOG: {
            kind: 'PRODUCT_TEAM',
            component: ProductTeamCard,
            props: {title: "MMOG", cost: 4, score: 2, description: _(Text.PRODUCT_TEAM_MMOG)}
        },
        AGILE_MATURITY_PASSIONATE_DEVELOPER: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_PASSIONATE_DEVELOPER_TITLE),
                cost: 1, score: 1, activity: "RETROSPECTIVE",
                description: _(Text.AGILE_MATURITY_PASSIONATE_DEVELOPER_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_PASSIONATE_DEVELOPER_GAIN)
            }
        },
        AGILE_MATURITY_AGILE_PRACTITIONER: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_PRACTITIONER_TITLE),
                cost: 1, score: 1, activity: "CONFERENCE",
                description: _(Text.AGILE_MATURITY_AGILE_PRACTITIONER_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_AGILE_PRACTITIONER_GAIN)
            }
        },
        AGILE_MATURITY_USER_EXPERIENCE: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_USER_EXPERIENCE_TITLE),
                cost: 2, score: 1, activity: "DEPLOYMENT",
                description: _(Text.AGILE_MATURITY_USER_EXPERIENCE_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_USER_EXPERIENCE_GAIN)
            }
        },
        AGILE_MATURITY_PAIR_PROGRAMMING: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_PAIR_PROGRAMMING_TITLE),
                cost: 2, score: 1, activity: "DEVELOPMENT",
                description: _(Text.AGILE_MATURITY_PAIR_PROGRAMMING_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_PAIR_PROGRAMMING_GAIN)
            }
        },
        AGILE_MATURITY_AGILE_ORGANIZER: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_ORGANIZER_TITLE),
                cost: 3, score: "2", activity: "CONFERENCE",
                description: _(Text.AGILE_MATURITY_AGILE_ORGANIZER_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_AGILE_ORGANIZER_GAIN)
            }
        },
        AGILE_MATURITY_FEEDBACK_SESSIONS: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_FEEDBACK_SESSIONS_TITLE),
                cost: 3, score: "2", activity: "RETROSPECTIVE",
                description: _(Text.AGILE_MATURITY_FEEDBACK_SESSIONS_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_FEEDBACK_SESSIONS_GAIN)
            }
        },
        AGILE_MATURITY_CLEAN_CODE: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_CLEAN_CODE_TITLE),
                cost: 3, score: "2", activity: "DEVELOPMENT",
                description: _(Text.AGILE_MATURITY_CLEAN_CODE_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_CLEAN_CODE_GAIN)
            }
        },
        AGILE_MATURITY_CONTINUOUS_DELIVERY: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_CONTINUOUS_DELIVERY_TITLE),
                cost: 3, score: "2", activity: "DEPLOYMENT",
                description: _(Text.AGILE_MATURITY_CONTINUOUS_DELIVERY_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_CONTINUOUS_DELIVERY_GAIN)
            }
        },
        AGILE_MATURITY_DEVOPS: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_DEVOPS_TITLE),
                cost: 3, score: "2", activity: "RETROSPECTIVE",
                description: _(Text.AGILE_MATURITY_DEVOPS_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_DEVOPS_GAIN)
            }
        },
        AGILE_MATURITY_AGILE_HR: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_HR_TITLE),
                cost: 4, score: "2", activity: "LEADER",
                description: _(Text.AGILE_MATURITY_AGILE_HR_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_AGILE_HR_GAIN)
            }
        },
        AGILE_MATURITY_ENGAGED_USERS: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_ENGAGED_USERS_TITLE),
                cost: 4, score: "2", activity: "DEPLOYMENT",
                description: _(Text.AGILE_MATURITY_ENGAGED_USERS_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_ENGAGED_USERS_GAIN)
            }
        },
        AGILE_MATURITY_INTERNAL_COACH: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_INTERNAL_COACH_TITLE),
                cost: 4, score: "2", activity: "RETROSPECTIVE",
                description: _(Text.AGILE_MATURITY_INTERNAL_COACH_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_INTERNAL_COACH_GAIN)
            }
        },
        AGILE_MATURITY_DETAILED_PLANNING: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_DETAILED_PLANNING_TITLE),
                cost: 1, score: "-4", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_DETAILED_PLANNING_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_NEGATIVE_GAIN)
            }
        },
        AGILE_MATURITY_TEST_TEAM: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_TEST_TEAM_TITLE),
                cost: 1, score: "-4", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_TEST_TEAM_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_NEGATIVE_GAIN)
            }
        },
        AGILE_MATURITY_APPLICATION_FRAMEWORK: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_APPLICATION_FRAMEWORK_TITLE),
                cost: 1, score: "-5", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_APPLICATION_FRAMEWORK_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_NEGATIVE_GAIN)
            }
        },
        AGILE_MATURITY_AGILE_CERTIFICATION: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_CERTIFICATION_TITLE),
                cost: 1, score: "-5", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_AGILE_CERTIFICATION_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_NEGATIVE_GAIN)
            }
        },
        AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP_TITLE),
                cost: 5, score: "*", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP_GAIN)
            }
        },
        AGILE_MATURITY_AGILE_SENSEI: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_AGILE_SENSEI_TITLE),
                cost: 5, score: "*", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_AGILE_SENSEI_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_AGILE_SENSEI_GAIN)
            }
        },
        AGILE_MATURITY_PRODUCT_VISION: {
            kind: 'AGILE_MATURITY',
            component: AgileMaturityCard,
            props: {
                title: _(Text.AGILE_MATURITY_PRODUCT_VISION_TITLE),
                cost: 5, score: "*", activity: "THEEND",
                description: _(Text.AGILE_MATURITY_PRODUCT_VISION_DESCRIPTION),
                gain: _(Text.AGILE_MATURITY_PRODUCT_VISION_GAIN)
            }
        },
        AGILE_VALUE_HUMOR: {
            kind: 'AGILE_VALUE',
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_HUMOR_TITLE),
                cost: 4,
                description: _(Text.AGILE_VALUE_HUMOR_DESCRIPTION),
            }
        },
        AGILE_VALUE_FEEDBACK: {
            kind: 'AGILE_VALUE',
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_FEEDBACK_TITLE),
                cost: 4,
                description: _(Text.AGILE_VALUE_FEEDBACK_DESCRIPTION),
            }
        },
        AGILE_VALUE_SIMPLICITY: {
            kind: 'AGILE_VALUE',
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_SIMPLICITY_TITLE),
                cost: 4,
                description: _(Text.AGILE_VALUE_SIMPLICITY_DESCRIPTION),
            }
        },
        AGILE_VALUE_TRUST: {
            kind: 'AGILE_VALUE',
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_TRUST_TITLE),
                cost: 4,
                description: _(Text.AGILE_VALUE_TRUST_DESCRIPTION),
            }
        },
        AGILE_VALUE_TRANSPARENCY: {
            kind: 'AGILE_VALUE',
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_TRANSPARENCY_TITLE),
                cost: 4,
                description: _(Text.AGILE_VALUE_TRANSPARENCY_DESCRIPTION),
            }
        },
        AGILE_VALUE_COURAGE: {
            kind: 'AGILE_VALUE',
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_COURAGE_TITLE),
                cost: 4,
                description: _(Text.AGILE_VALUE_COURAGE_DESCRIPTION),
            }
        },
        AGILE_VALUE_RESPECT: {
            kind: 'AGILE_VALUE',
            component: AgileValueCard,
            props: {
                title: _(Text.AGILE_VALUE_RESPECT_TITLE),
                cost: 4,
                description: _(Text.AGILE_VALUE_RESPECT_DESCRIPTION),
            }
        },
        ACTIVITY_DEVELOPMENT: {
            kind: 'ACTIVITY',
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_DEVELOPMENT_TITLE),
                logo: "DEVELOPMENT",
                description: _(Text.ACTIVITY_DEVELOPMENT_DESCRIPTION),
                privilege: _(Text.ACTIVITY_DEVELOPMENT_PRIVILEGE),
            }
        },
        ACTIVITY_DEPLOYMENT: {
            kind: 'ACTIVITY',
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_DEPLOYMENT_TITLE),
                logo: "DEPLOYMENT",
                description: _(Text.ACTIVITY_DEPLOYMENT_DESCRIPTION),
                privilege: _(Text.ACTIVITY_DEPLOYMENT_PRIVILEGE),
            }
        },
        ACTIVITY_RETROSPECTIVE: {
            kind: 'ACTIVITY',
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_RETROSPECTIVE_TITLE),
                logo: "RETROSPECTIVE",
                description: _(Text.ACTIVITY_RETROSPECTIVE_DESCRIPTION),
                privilege: _(Text.ACTIVITY_RETROSPECTIVE_PRIVILEGE),
            }
        },
        ACTIVITY_CONFERENCE: {
            kind: 'ACTIVITY',
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_CONFERENCE_TITLE),
                logo: "CONFERENCE",
                description: _(Text.ACTIVITY_CONFERENCE_DESCRIPTION),
                privilege: _(Text.ACTIVITY_CONFERENCE_PRIVILEGE),
            }
        },
        ACTIVITY_COACH: {
            kind: 'ACTIVITY',
            component: ActivityCard,
            props: {
                title: _(Text.ACTIVITY_COACH_TITLE),
                logo: "COACH",
                description: _(Text.ACTIVITY_COACH_DESCRIPTION),
                privilege: _(Text.ACTIVITY_COACH_PRIVILEGE)
            }
        },
        EARNINGS_CARD_1: {
            kind: 'EARNINGS',
            component: EarningsCard,
            props: {advergame: 2, education: 2, social: 3, mmog: 3}
        },
        EARNINGS_CARD_2: {
            kind: 'EARNINGS',
            component: EarningsCard,
            props: {advergame: 2, education: 2, social: 3, mmog: 4}
        },
        EARNINGS_CARD_3: {
            kind: 'EARNINGS',
            component: EarningsCard,
            props: {advergame: 2, education: 3, social: 3, mmog: 4}
        },
        EARNINGS_CARD_4: {
            kind: 'EARNINGS',
            component: EarningsCard,
            props: {advergame: 2, education: 3, social: 4, mmog: 4}
        },
        LEADER_CARD: {
            kind: 'LEADER',
            component: LeaderCard,
            props: {}
        }
    }
};