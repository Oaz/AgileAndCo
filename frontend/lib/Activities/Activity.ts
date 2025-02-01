export type Interaction = 'NEUTRAL' | 'ACTIVE' | 'FROZEN';

export abstract class Activity {

    public selection: Record<number, boolean>;
    public interaction: Record<number, Interaction>;
    public cards: any;
    public datas: any;
    public readonly selection_min : number = 0;
    public readonly selection_max : number = Number.MAX_SAFE_INTEGER;

    constructor(datas) {
        if(!datas.drawn)
            datas.drawn = [];
        let cannot_select : Interaction = 'FROZEN';
        if(!datas.select_zones) {
            datas.select_zones = [];
            cannot_select = 'NEUTRAL';
        }
        this.datas = datas;
        this.selection = {};
        this.interaction = {};
        this.cards = {
            ...Object.fromEntries(datas.teams.map((value, index) => [this.id(0, index), value[0]])),
            ...Object.fromEntries(datas.teams.map((value, index) => [this.id(1, index), value[1]])),
            ...Object.fromEntries(datas.company.map((value, index) => [this.id(2, index), value])),
            ...Object.fromEntries(datas.potential.map((value, index) => [this.id(3, index), value])),
            ...Object.fromEntries(datas.drawn.map((value, index) => [this.id(4, index), value])),
        };
        this.interaction = Object.fromEntries(
            Object.entries(this.cards).map(
                ([key, _]) => [
                    key,
                    datas.select_zones.includes(this.zone_id(key)) ? 'ACTIVE' : cannot_select
                ]
            )
        );

        if(datas.select_min !== undefined)
            this.selection_min = datas.select_min;
        if(datas.select_max !== undefined)
            this.selection_max = datas.select_max;
    }

    public get can_act() {
        const selection_count = this.selected.length;
        return this.selection_min <= selection_count && selection_count <= this.selection_max;
    }

    public abstract get action_text() : string;

    public abstract do_act() : void;

    public abstract get drawn_card_zone_title() : string;

    public get selected() {
        return Object.entries(this.cards).filter(
            ([key, _]) => this.selection[key]
        );
    }

    public selected_in_zone(zone_id:number) {
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

}

export class NoActivity extends Activity {
    constructor(datas) {
        super(datas);
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
