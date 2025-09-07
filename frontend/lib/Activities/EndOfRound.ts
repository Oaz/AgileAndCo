import {Activity} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class EndOfRound extends Activity {
    constructor(datas) {
        super(datas, 'END_OF_ROUND');
        var potentialSize = datas.potential.length;
        var freePotential = 6;
        if(datas.company.includes('AGILE_MATURITY_AGILE_HR'))
            freePotential = 10;
        freePotential += datas.company.filter(cardName => this.details[cardName].kind === 'AGILE_VALUE').length;
        var toDismiss = potentialSize > freePotential ? potentialSize-freePotential : 0;
        this.selection_min = toDismiss;
        this.selection_max = toDismiss;
        if(toDismiss > 0)
            this.define_interactions([3]);
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_DISCARD, this.selected.length.toString())
            : _(Text.CANNOT_DISCARD, this.selection_min.toString());
    }

    public do_act()  : void {
        BGA.performAction('actAdjustPotential', {cards: JSON.stringify(this.selected)});
    }
}

