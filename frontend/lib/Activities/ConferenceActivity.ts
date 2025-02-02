import {Activity, Interaction} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class ConferenceActivity extends Activity {
    constructor(datas) {
        super(datas);
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
        BGA.performAction('actDiscard', {selected: this.selected});
    }

    public get drawn_card_zone_title(): string {
        return _(Text.ACTIVITY_CONFERENCE_TITLE);
    }
}

