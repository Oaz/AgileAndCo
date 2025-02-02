import {cardsData} from "../Cards/CardsData";

export type Interaction = 'NEUTRAL' | 'ACTIVE' | 'FROZEN';

export abstract class Activity {

    public selection: Record<number, boolean>;
    public interaction: Record<number, Interaction>;
    public details: Record<number, any>;
    public cards: any;
    public datas: any;
    public selection_min: number = 0;
    public selection_max: number = Number.MAX_SAFE_INTEGER;

    constructor(datas) {
        if (!datas.drawn)
            datas.drawn = [];
        this.datas = datas;
        this.selection = {};
        this.interaction = {};
        const removeUndefined = (obj: Record<string, any>) =>
            Object.fromEntries(Object.entries(obj).filter(([_, v]) => v !== undefined));
        this.cards = removeUndefined({
            ...Object.fromEntries(datas.teams.map((value, index) => [this.id(0, index), value[0]])),
            ...Object.fromEntries(datas.teams.map((value, index) => [this.id(1, index), value[1]])),
            ...Object.fromEntries(datas.company.map((value, index) => [this.id(2, index), value])),
            ...Object.fromEntries(datas.potential.map((value, index) => [this.id(3, index), value])),
            ...Object.fromEntries(datas.drawn.map((value, index) => [this.id(4, index), value])),
        });
        const cardDetails = cardsData();
        this.details = Object.fromEntries(
            Object.entries(this.cards).map(
                ([key, value]) => [key, cardDetails[value.toString()].props]
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

    public abstract get action_text(): string;

    public abstract do_act(): void;

    public get drawn_card_zone_title(): string {
        return '';
    }

    public get selected() {
        return Object.entries(this.cards).filter(
            ([key, _]) => this.selection[key]
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
        super(datas);
        this.interaction = Object.fromEntries(
            Object.entries(this.cards).map(
                ([key, _]) => [key, 'NEUTRAL']
            )
        );
    }

    public get action_text(): string {
        return '';
    }

    public get drawn_card_zone_title(): string {
        return '';
    }

    public do_act(): void {
    }
}
