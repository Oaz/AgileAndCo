<script>
    import DeckCard from '../lib/Cards/DeckCard.svelte';

    export let cards = [
        "PRODUCT_TEAM_SOCIAL",
        "AGILE_VALUE_SIMPLICITY",
        "AGILE_MATURITY_PRODUCT_VISION",
        "AGILE_MATURITY_INTERNAL_COACH",
        "AGILE_MATURITY_DEVOPS",
        "ACTIVITY_DEVELOPMENT",
        "AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP",
        "EARNINGS_CARD_1",
        "PRODUCT_TEAM_MMOG",
        "AGILE_MATURITY_ENGAGED_USERS",
        "AGILE_VALUE_HUMOR",
        "ACTIVITY_CONFERENCE",
        "AGILE_MATURITY_PASSIONATE_DEVELOPER",
        "LEADER_CARD",
        "AGILE_MATURITY_TEST_TEAM",
        "PRODUCT_TEAM_ADVERGAME",
        "ACTIVITY_RETROSPECTIVE",
        "AGILE_MATURITY_USER_EXPERIENCE",
        "AGILE_MATURITY_AGILE_PRACTITIONER",
        "EARNINGS_CARD_2",
        "AGILE_MATURITY_AGILE_SENSEI",
        "PRODUCT_TEAM_EDUCATION",
        "AGILE_MATURITY_APPLICATION_FRAMEWORK",
        "AGILE_VALUE_FEEDBACK",
        "AGILE_VALUE_TRANSPARENCY",
        "AGILE_MATURITY_CLEAN_CODE",
        "AGILE_MATURITY_AGILE_ORGANIZER",
        "AGILE_MATURITY_AGILE_CERTIFICATION",
        "AGILE_VALUE_TRUST",
        "AGILE_VALUE_RESPECT",
        "AGILE_VALUE_COURAGE",
        "ACTIVITY_DEPLOYMENT",
        "AGILE_MATURITY_DETAILED_PLANNING",
        "EARNINGS_CARD_3",
        "AGILE_MATURITY_PAIR_PROGRAMMING",
        "AGILE_MATURITY_FEEDBACK_SESSIONS",
        "AGILE_MATURITY_AGILE_HR",
        "ACTIVITY_COACH",
        "EARNINGS_CARD_4",
        "AGILE_MATURITY_CONTINUOUS_DELIVERY"
    ];

    export let bannerHeight = "300px";
    export let bannerTitle = "AGILE & Co.";
    export let contourDepth = "6px";


    $: repeatedCards = [...cards, ...cards, ...cards, ...cards, ...cards];

</script>

<style>

    .banner {
        width: 100%;
        height: var(--banner-height);
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #bbbbbb, #dddddd);
        perspective: 4000px;
    }

    .cards-container {
        position: absolute;
        top: -100%;
        left: -20%;
        width: 100%;
        height: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, 33mm);
        transform: rotateX(50deg) rotateZ(-40deg);
        transform-style: preserve-3d;
        filter: grayscale(50%);
    }

    .card-wrapper {
        transform: scale(0.6);
        transform-origin: center center;
        margin: -15mm 0;
    }

    .banner-title {
        --contour-depth: var(--title-contour-depth, 8px);
        --half-depth: calc(var(--contour-depth) / 2);
        --double-depth: calc(var(--contour-depth) * 2);

        position: absolute;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: 'Playfair Display', 'Times New Roman', serif;
        font-size: 8rem;
        font-weight: 900;
        color: #2a2a2a;
        text-shadow:
            /* Main contours */
                var(--contour-depth) var(--contour-depth) 0 #fff,
                calc(var(--contour-depth) * -1) calc(var(--contour-depth) * -1) 0 #fff,
                var(--contour-depth) calc(var(--contour-depth) * -1) 0 #fff,
                calc(var(--contour-depth) * -1) var(--contour-depth) 0 #fff,
                    /* Medium contours */
                var(--half-depth) var(--half-depth) 0 #fff,
                calc(var(--half-depth) * -1) calc(var(--half-depth) * -1) 0 #fff,
                var(--half-depth) calc(var(--half-depth) * -1) 0 #fff,
                calc(var(--half-depth) * -1) var(--half-depth) 0 #fff,
                    /* Deep shadow */
                var(--double-depth) var(--double-depth) 0 rgba(0,0,0,0.2),
                    /* Inner shadow for depth */
                0 1px var(--double-depth) rgba(0,0,0,0.4),
                    /* Outer glow */
                0 0 var(--double-depth) rgba(255,255,255,0.8);
        letter-spacing: 0.05em;
        z-index: 10;
        text-align: center;
        white-space: nowrap;
        -webkit-text-stroke: calc(var(--contour-depth) / 4) #000;
    }




</style>

<div class="banner" style="--banner-height: {bannerHeight}; --title-contour-depth: {contourDepth}">
    <div class="cards-container">
        {#each repeatedCards as cardKey}
            <div class="card-wrapper">
                <DeckCard key={cardKey} />
            </div>
        {/each}
    </div>
    <h1 class="banner-title">{bannerTitle}</h1>
</div>