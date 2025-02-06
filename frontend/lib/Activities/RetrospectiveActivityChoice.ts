import {RetrospectiveActivity} from "./RetrospectiveActivity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class RetrospectiveActivityChoice extends RetrospectiveActivity {
    constructor(datas) {
        super(datas);
        this.selection_min = 0;
        this.selection_max = 1;
        let affordable_cost = datas.potential.length - 1;
        if(datas.initiate)
            affordable_cost += 1;
        if(datas.company.includes('AGILE_MATURITY_DEVOPS'))
            affordable_cost += 2 * this.cards_in_zone(1).length;
        this.define_interactions(
            [3],
            key => {
                return this.compute_cost(key) <= affordable_cost;
            }
        );
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_CHOICE, this.selected.length.toString())
            : _(Text.CANNOT_CHOICE);
    }

    public do_act(): void {
        BGA.performAction('actRetrospectiveChoice', {card: JSON.stringify(this.selected[0])});
    }
}

