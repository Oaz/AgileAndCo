import {Activity} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class DeploymentActivity extends Activity {
    constructor(datas) {
        super(datas);
        this.selection_min = 0;
        this.selection_max = datas.initiate ? 2 : 1;
        if(datas.company.includes('AGILE_MATURITY_CONTINUOUS_DELIVERY'))
            this.selection_max += 1;
        this.define_interactions([1]);
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_DEPLOYMENT, this.selected.length.toString())
            : _(Text.CANNOT_DEPLOY, this.selection_max.toString());
    }

    public do_act()  : void {
        BGA.performAction('actDeploy', {selected: this.selected});
    }
}

