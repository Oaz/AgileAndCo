import {Activity, Interaction} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class ConferenceActivity extends Activity {
    constructor(datas) {
        super(datas);
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

