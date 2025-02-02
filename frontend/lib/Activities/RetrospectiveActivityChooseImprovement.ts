import {Activity, Interaction} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class RetrospectiveActivityChooseImprovement extends Activity {
    constructor(datas) {
        super(datas);
        this.define_interactions(
            datas.select_zones,
            key => this.details[key].cost <= this.datas.affordable_cost
        );
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_CHOOSE_IMPROVEMENT, this.selected.length.toString())
            : _(Text.CANNOT_CHOOSE_IMPROVEMENT);
    }

    public do_act(): void {
        BGA.performAction('actChooseImprovement', {selected: this.selected});
    }

    public get drawn_card_zone_title(): string {
        return '';
    }
}

