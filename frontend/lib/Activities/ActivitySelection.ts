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

import {Action, Interaction} from "../Action";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class ActivitySelection extends Action {

    public selection: Record<number, boolean>;
    public interaction: Record<number, Interaction>;
    public data : any;

    constructor(datas) {
        super(datas.selection);
        this.data = datas;
        this.selection = datas.activities.map(
            (value, index) => value[2]
        );
        this.interaction = datas.activities.map(
            (value, index) =>
                this.enabled ? (value[1] ? 'FROZEN' : 'ACTIVE') : 'FROZEN'
        );
    }

    public get selected() {
        return this.data.activities.filter(
            (value, index) => this.selection[index]
        );
    }
    public get can_act() {
        return this.selected.length == 1;
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_ACTIVITY_SELECTION)
            : _(Text.CANNOT_ACTIVITY_SELECTION);
    }

    public do_act(): void {
        BGA.performAction('actChooseActivity', {activity_id: this.selected[0][0]});
    }
}
