/*
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
*/

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

setTimeout(() => {
    window.print();
}, 100);

export default app