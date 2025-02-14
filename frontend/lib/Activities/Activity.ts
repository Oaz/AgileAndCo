import {Action, Interaction} from "../Action";
import {cardsData} from "../Cards/CardsData";

export abstract class Activity extends Action {

    public card_key: string;
    public selection: Record<number, boolean>;
    public interaction: Record<number, Interaction>;
    public details: Record<string, any>;
    public cards: any;
    public datas: any;
    public selection_min: number = 0;
    public selection_max: number = Number.MAX_SAFE_INTEGER;

    protected constructor(datas, card_key: string) {
        super(datas.activity);
        this.card_key = card_key;
        if (!datas.conference)
            datas.conference = [];
        if (!datas.retrospective)
            datas.retrospective = [];
        this.datas = datas;
        this.selection = {};
        this.interaction = {};
        const removeUndefined = (obj: Record<string, any>) =>
            Object.fromEntries(Object.entries(obj).filter(([_, v]) => !!v));
        this.cards = removeUndefined({
            ...Object.fromEntries(datas.teams.map((value, index) => [this.id(0, index), value])),
            ...Object.fromEntries(datas.products.map((value, index) => [this.id(1, index), value])),
            ...Object.fromEntries(datas.company.map((value, index) => [this.id(2, index), value])),
            ...Object.fromEntries(datas.potential.map((value, index) => [this.id(3, index), value])),
            ...Object.fromEntries(datas.conference.map((value, index) => [this.id(4, index), value])),
            ...Object.fromEntries(datas.retrospective.map((value, index) => [this.id(5, index), value])),
        });
        const cardDetails = cardsData();
        this.details = Object.fromEntries(
            Object.entries(this.cards).map(
                ([_, value]) => [value, {
                    ...cardDetails[value.toString()].props,
                    ...{kind: cardDetails[value.toString()].kind}
                }]
            )
        );
    }

    public define_interactions(zones: number[], condition: (key: string) => boolean = _ => true) {
        this.interaction = Object.fromEntries(
            Object.entries(this.cards).map(
                ([key, _]) => [
                    key,
                    zones.includes(this.zone_id(key)) && condition(key) ? 'ACTIVE' : 'FROZEN'
                ]
            )
        );
    }

    public get can_act() {
        const selection_count = this.selected.length;
        return this.selection_min <= selection_count && selection_count <= this.selection_max;
    }

    public get selected() {
        return Object.entries(this.cards).filter(
            ([key, _]) => this.selection[key]
        );
    }

    public cards_in_zone(zone_id: number) {
        return Object.entries(this.cards).filter(
            ([key, _]) => this.zone_id(key) === zone_id
        );
    }

    public selected_in_zone(zone_id: number) {
        return Object.entries(this.cards).filter(
            ([key, _]) => this.selection[key] && this.zone_id(key) === zone_id
        );
    }

    public id(zone_id: number, index: number): number {
        return zone_id * 100 + index;
    }

    public zone_id(key: string): number {
        return Math.floor(parseInt(key, 10) / 100);
    }

    public index_in_zone(key: string): number {
        return parseInt(key, 10) % 100;
    }

}

export class NoActivity extends Activity {
    constructor(datas) {
        super(datas, undefined);
        this.interaction = Object.fromEntries(
            Object.entries(this.cards).map(
                ([key, _]) => [key, 'NEUTRAL']
            )
        );
    }

    public get action_text(): string {
        return '';
    }

    public do_act(): void {
    }
}
