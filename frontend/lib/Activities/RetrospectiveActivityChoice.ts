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

import {RetrospectiveActivity} from "./RetrospectiveActivity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class RetrospectiveActivityChoice extends RetrospectiveActivity {
    constructor(datas) {
        super(datas);
        this.selection_min = 0;
        this.selection_max = 1;
        let affordable_cost = datas.potential.length - 1;
        if(datas.company.includes('AGILE_MATURITY_DEVOPS'))
            affordable_cost += 2 * this.cards_in_zone(1).length;
        this.define_interactions(
            [3],
            key => {
                return this.compute_cost(this.details[this.cards[key].name]) <= affordable_cost;
            }
        );
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_CHOICE, this.selected.length.toString())
            : _(Text.CANNOT_CHOICE);
    }

    public do_act(): void {
        if(this.selected.length == 0)
            BGA.performAction('actRetrospectiveChoice', {card: '{}'});
        else
            BGA.performAction('actRetrospectiveChoice', {card: JSON.stringify(this.selected[0])});
    }
}

