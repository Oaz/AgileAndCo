import {Activity} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class RetrospectiveActivityChooseImprovement extends Activity {
    constructor(datas) {
        super(datas);
        this.selection_min = 0;
        this.selection_max = 1;
        const affordable_cost = datas.potential.length - 1;
        this.define_interactions(
            [3],
            key => this.details[key].cost <= affordable_cost
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
}

