import {Activity, Interaction} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class DeploymentActivity extends Activity {
    constructor(datas) {
        super(datas);
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_DEPLOYMENT, this.selected.length.toString())
            : _(Text.CANNOT_DEPLOY, this.selection_max.toString());
    }

    public do_act()  : void {
        BGA.performAction('actDeploy', {selected: this.selected});
    }

    public get drawn_card_zone_title(): string {
        return '';
    }

}

