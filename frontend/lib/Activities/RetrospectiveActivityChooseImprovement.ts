import {Activity, Interaction} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class RetrospectiveActivityChooseImprovement extends Activity {
    constructor(datas) {
        super(datas);
    }

    public update_details(card_id: number, details: any) {
        if (details === undefined)
            return;
        if(!this.datas.select_zones.includes(this.zone_id(card_id.toString())))
            return;
        this.interaction[card_id] = details.cost <= this.datas.affordable_cost ? 'ACTIVE' : 'FROZEN';
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

