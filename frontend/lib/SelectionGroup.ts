export type Interaction = 'NEUTRAL' | 'ACTIVE' | 'FROZEN';

export class SelectionGroup {

    public selection: Record<number, boolean>;
    public interaction: Record<number, Interaction>;
    public cards: any;
    public datas: any;

    constructor(datas) {
        this.datas = datas;
        this.selection = {};
        this.interaction = {};
        this.cards = {
            ...Object.fromEntries(datas.teams.map((value, index) => [this.id(0, index), value])),
            ...Object.fromEntries(datas.teams.map((value, index) => [this.id(1, index), value])),
            ...Object.fromEntries(datas.company.map((value, index) => [this.id(2, index), value])),
            ...Object.fromEntries(datas.potential.map((value, index) => [this.id(3, index), value])),
        };
        this.interaction = Object.fromEntries(
            Object.entries(this.cards).map(
                ([key, _]) => [key, this.deploymentActivity(parseInt(key, 10))]
            )
        );
    }

    public get selected() {
        return Object.entries(this.cards).filter(
            ([key, _]) => this.selection[key]
        ).map(
            ([_, value]) => value
        );
    }

    public id(group: number, index: number): number {
        return group * 100 + index;
    }

    private deploymentActivity(key: number): Interaction {
        if (!this.datas.activity)
            return 'NEUTRAL';
        const group = Math.floor(key / 100);
        const check = !!((group == 1) && this.datas.activity);
        // console.log(`key ${key} => group ${group} => check ${check}`);
        return check ? 'ACTIVE' : 'FROZEN';
    }

}