import {Activity} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class DevelopmentActivity extends Activity {
    constructor(datas) {
        super(datas);
        this.selection_min = 0;
        let available = datas.initiate ? 2 : 1;
        if(datas.company.includes('AGILE_MATURITY_CLEAN_CODE'))
            available += 1;
        this.selection_max = available * 2;
        this.define_interactions(
            [0,3],
            key =>
                this.zone_id(key) == 3
                || this.cards[this.id(1, this.index_in_zone(key))] === undefined
        );
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
        const max_product = this.selection_max /2;
        return this.can_act
            ? _(Text.CONFIRM_DEVELOPMENT, this.selected_teams.length.toString())
            : (this.selected_teams.length > max_product
                ? _(Text.CANNOT_DEVELOP_MAX_PRODUCT, max_product.toString())
                : _(Text.CANNOT_DEVELOP));
    }

    public do_act()  : void {
        BGA.performAction('actDevelop', {teams: this.selected_teams, products: this.selected_potential});
    }

    public get drawn_card_zone_title(): string {
        return '';
    }

}

