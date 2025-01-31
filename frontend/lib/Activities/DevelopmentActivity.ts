import {Activity, Interaction} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class DevelopmentActivity extends Activity {
    constructor(datas) {
        super(datas);
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
        return this.can_act
            ? _(Text.CONFIRM_DEVELOPMENT, this.selected_teams.length.toString())
            : _(Text.CANNOT_DEVELOP);
    }

    public do_act()  : void {
        BGA.performAction('actDevelop', {teams: this.selected_teams, products: this.selected_potential});
    }

    public get drawn_card_zone_title(): string {
        return '';
    }

}

