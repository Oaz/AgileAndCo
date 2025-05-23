import { mount } from 'svelte'
import Banner from './Banner.svelte'
import {BGA} from "../../lib/BGA";
import {frTranslate} from "../../lib/texts-fr";

const params = new URLSearchParams(window.location.search);
const language = params.get('lang');
BGA.setTranslate(language === "fr" ? frTranslate : text => text);

const app = mount(Banner, {
  target: document.getElementById('app')!,
})

export default app
