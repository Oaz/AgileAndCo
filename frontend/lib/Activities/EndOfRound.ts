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

import {Activity} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class EndOfRound extends Activity {
    constructor(datas) {
        super(datas, 'END_OF_ROUND');
        var potentialSize = datas.potential.length;
        var freePotential = 6;
        if(datas.company.includes('AGILE_MATURITY_AGILE_HR'))
            freePotential = 10;
        freePotential += datas.company.filter(cardName => this.details[cardName].kind === 'AGILE_VALUE').length;
        var toDismiss = potentialSize > freePotential ? potentialSize-freePotential : 0;
        this.selection_min = toDismiss;
        this.selection_max = toDismiss;
        if(toDismiss > 0)
            this.define_interactions([3]);
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_DISCARD, this.selected.length.toString())
            : _(Text.CANNOT_DISCARD, this.selection_min.toString());
    }

    public do_act()  : void {
        BGA.performAction('actAdjustPotential', {cards: JSON.stringify(this.selected)});
    }
}

