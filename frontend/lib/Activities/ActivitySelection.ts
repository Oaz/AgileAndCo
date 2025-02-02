import {Action, Interaction} from "../Action";
import {_, Text} from "../texts";
import {BGA} from "../BGA";

export class ActivitySelection extends Action {

    public selection: Record<number, boolean>;
    public interaction: Record<number, Interaction>;
    public data : any;

    constructor(datas) {
        super(datas.selection);
        this.data = datas;
        this.selection = {};
        this.interaction = datas.activities.map(
            (value, index) =>
                this.enabled ? (value[1] ? 'FROZEN' : 'ACTIVE') : 'NEUTRAL'
        );
    }

    public get selected() {
        return this.data.activities.filter(
            (value, index) => this.selection[index]
        );
    }
    public get can_act() {
        return this.selected.length == 1;
    }

    public get action_text(): string {
        return this.can_act
            ? _(Text.CONFIRM_ACTIVITY_SELECTION)
            : _(Text.CANNOT_ACTIVITY_SELECTION);
    }

    public do_act(): void {
        BGA.performAction('actActivitySelection', {selected: this.selected[0][0]});
    }
}
