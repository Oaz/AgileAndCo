
<script lang="ts">
    import { Localized } from '@nubolab-ffwd/svelte-fluent';
    import { initTranslations } from './translations';
    import enTranslations from './translations/en/home.ftl?raw';
    import frTranslations from './translations/fr/home.ftl?raw';
    import gameCards from './game_cards.jpg';

    export let language: string;

    initTranslations(language, {
        en: enTranslations,
        fr: frTranslations
    });
</script>

<div class="home-wrapper">
    <p class="title"><Localized id="description"/></p>

    <div class="container">
        <div class="content">
            <p class="summary multiline"><Localized id="summary"/></p>
        </div>
        <div class="image">
            <img src={gameCards} alt="Game cards" class="hero-image">
        </div>
    </div>
</div>


<style>
    .home-wrapper {
        padding: 0.5rem;
    }

    .title {
        font-size: 2rem;
        margin-top: 0;
        margin-bottom: 2rem;
        color: var(--primary-color, #333);
        text-align: center;
    }

    .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .content {
        flex: 1;
        text-align: left;
    }

    .summary {
        font-size: 1.2rem;
        line-height: 1.6;
        color: var(--text-color, #666);
        margin-bottom: 2rem;
    }

    .multiline {
        white-space: pre-line;
    }

    .image {
        flex: 0 0 auto;
        max-width: 45%;
    }

    .hero-image {
        width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .hero-image:hover {
        transform: scale(1.02);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            text-align: center;
            padding: 1rem;
        }

        .image {
            max-width: 80%;
            margin: 2rem auto;
            order: -1; /* Move image to top on mobile */
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 1.8rem;
        }

        .summary {
            font-size: 1.1rem;
        }
    }
</style>