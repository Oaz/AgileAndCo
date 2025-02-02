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

    public get undeployed() {
        return this.selected_in_zone(1).length;
    }
    public get other() {
        return this.selected_in_zone(3).length;
    }

    public get can_act() {
        const payment = 2*this.undeployed+this.other;
        return payment === this.price
            || (payment === this.price+1 && this.undeployed > 1);
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_PAYMENT)
            : _(Text.CANNOT_PAYMENT);
    }

    public do_act(): void {
        BGA.performAction('actRetrospectivePayment', {selected: this.selected});
    }
}

