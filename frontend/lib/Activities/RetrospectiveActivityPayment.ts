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

export class RetrospectiveActivityPayment extends RetrospectiveActivity {
    constructor(datas) {
        super(datas);
        let selectedCard = this.details[datas.retrospective[0]];
        this.price = this.compute_cost(selectedCard);
        let zones = datas.company.includes('AGILE_MATURITY_DEVOPS') ? [1, 3] : [3];
        this.define_interactions(zones);
    }

    private readonly price: number;

    public get undeployed() {
        return this.selected_in_zone(1).length;
    }

    public get other() {
        return this.selected_in_zone(3).length;
    }

    public get can_act() {
        const payment = 2 * this.undeployed + this.other;
        const isFree = payment === 0 && this.price < 0;
        const exactPayment = payment === this.price;
        const overPayment = payment === this.price + 1 && this.undeployed > 0;
        return exactPayment || overPayment || isFree;
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_PAYMENT)
            : _(Text.CANNOT_PAYMENT);
    }

    public do_act(): void {
        BGA.performAction('actRetrospectivePayment', {cards: JSON.stringify(this.selected)});
    }
}

