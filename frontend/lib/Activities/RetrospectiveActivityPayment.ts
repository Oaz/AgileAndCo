import {RetrospectiveActivity} from "./RetrospectiveActivity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class RetrospectiveActivityPayment extends RetrospectiveActivity {
    constructor(datas) {
        super(datas);
        const choice = datas.choice.toString();
        this.price = this.compute_cost(choice)
        let zones = datas.company.includes('AGILE_MATURITY_DEVOPS') ? [1,3] : [3];
        this.define_interactions(
            zones,
            key => key !== choice
        );
    }

    private readonly price:number;

    public get can_act() {
        const selection_count = this.selected.length;
        return this.price === selection_count;
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_DISCARD, this.price.toString())
            : _(Text.CANNOT_DISCARD, this.price.toString());
    }

    public do_act(): void {
        BGA.performAction('actRetrospectivePayment', {selected: this.selected});
    }
}

