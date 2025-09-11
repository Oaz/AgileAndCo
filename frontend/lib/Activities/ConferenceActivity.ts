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

export class ConferenceActivity extends Activity {
    constructor(datas) {
        super(datas, 'ACTIVITY_CONFERENCE');
        this.selection_min = datas.initiate ? 4 : 1;
        if(datas.company.includes('AGILE_MATURITY_AGILE_ORGANIZER'))
            this.selection_min -= 1;
        this.selection_max = this.selection_min;
        let zones = datas.company.includes('AGILE_MATURITY_AGILE_PRACTITIONER') ? [3,4] : [4];
        this.define_interactions(zones);
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_DISCARD, this.selected.length.toString())
            : _(Text.CANNOT_DISCARD, this.selection_min.toString());
    }

    public do_act()  : void {
        BGA.performAction('actConference', {cards: JSON.stringify(this.selected)});
    }
}

