import {Activity} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class RetrospectiveActivityChooseImprovement extends Activity {
    constructor(datas) {
        super(datas, 'ACTIVITY_RETROSPECTIVE');
        this.selection_min = 0;
        this.selection_max = 1;
        const affordable_cost = datas.potential.length - 1;
        const improvementBonus = datas.company.includes('AGILE_MATURITY_INTERNAL_COACH') ? 1 : 0;
        const teamBonus = datas.company.includes('AGILE_MATURITY_PASSIONATE_DEVELOPER') ? 1 : 0;
        this.define_interactions(
            [3],
            key => {
                const details = this.details[key];
                let cost = details.cost;
                if(details.kind === 'AGILE_MATURITY' || details.kind === 'AGILE_VALUE')
                    cost -= improvementBonus;
                if(details.kind === 'PRODUCT_TEAM')
                    cost -= teamBonus;
                return cost <= affordable_cost;
            }
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

