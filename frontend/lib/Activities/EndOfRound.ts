import {Activity} from "./Activity";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class EndOfRound extends Activity {
    constructor(datas) {
        super(datas, 'END_OF_ROUND');
        var potentialSize = this.cards_in_zone(3).length;
        var toDismiss = potentialSize > 6 ? potentialSize-6 : 0;
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

