import { mount } from 'svelte'
import DeckPrint from "./DeckPrint.svelte";
import {BGA} from "../lib/BGA";
import {frTranslate} from "../lib/texts-fr";

const params = new URLSearchParams(window.location.search);
const language = params.get('lang');
BGA.setTranslate(language === "fr" ? frTranslate : text => text);

const titles = {
    en: 'Printable Cards',
    fr: 'Cartes à imprimer'
};
document.title = 'Agile&Co. - ' + titles[language];

const app = mount(DeckPrint, {
    target: document.getElementById('app')!,
})

export default app