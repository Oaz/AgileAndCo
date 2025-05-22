import { FluentBundle, FluentResource } from '@fluent/bundle';
import { createSvelteFluent, initFluentContext } from '@nubolab-ffwd/svelte-fluent';

interface TranslationModules {
    en: string;
    fr: string;
}

export function initTranslations(lang: string, translations: TranslationModules): void {
    const bundle = new FluentBundle(lang);
    bundle.addResource(
        new FluentResource(lang === "fr" ? translations.fr : translations.en)
    );
    initFluentContext(() => createSvelteFluent([bundle]));
}