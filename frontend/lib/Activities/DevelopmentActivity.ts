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

export class DevelopmentActivity extends Activity {
    constructor(datas) {
        super(datas, 'ACTIVITY_DEVELOPMENT');
        this.selection_min = 0;
        let available = datas.initiate ? 2 : 1;
        if(datas.company.includes('AGILE_MATURITY_CLEAN_CODE'))
            available += 1;
        this.selection_max = available * 2;
        this.define_interactions(
            [0,3],
            key =>
                this.zone_id(key) == 3
                || this.cards[this.id(1, this.index_in_zone(key))] === undefined
        );
    }

    public get selected_teams() {
        return this.selected_in_zone(0);
    }

    public get selected_potential() {
        return this.selected_in_zone(3);
    }

    public get can_act() {
        return this.selected.length <= this.selection_max && this.selected_teams.length == this.selected_potential.length;
    }

    public get action_text(): string {
        const max_product = this.selection_max /2;
        return this.can_act
            ? _(Text.CONFIRM_DEVELOPMENT, this.selected_teams.length.toString())
            : (this.selected_teams.length > max_product
                ? _(Text.CANNOT_DEVELOP_MAX_PRODUCT, max_product.toString())
                : _(Text.CANNOT_DEVELOP));
    }

    public do_act()  : void {
        BGA.performAction('actDevelop', {teams: JSON.stringify(this.selected_teams), products: JSON.stringify(this.selected_potential)});
    }
}

