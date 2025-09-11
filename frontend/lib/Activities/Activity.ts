/*
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

import {Action, Interaction} from "../Action";
import {Card} from "./Card";
import {cardsData} from "../Cards/CardsData";

export abstract class Activity extends Action {

    public card_key: string;
    public selection: Record<number, boolean>;
    public interaction: Record<number, Interaction>;
    public details: Record<string, any>;
    public cards: Record<number, Card>;
    public datas: any;
    public selection_min: number = 0;
    public selection_max: number = Number.MAX_SAFE_INTEGER;

    static zone_names: { [key: string]: string } = {
        0: 'teams',
        1: 'products',
        2: 'company',
        3: 'potential',
        4: 'conference',
        5: 'retrospective',
    };

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
            ...Object.fromEntries(datas.teams.map(this.make_value(0))),
            ...Object.fromEntries(datas.products.map(this.make_value(1))),
            ...Object.fromEntries(datas.company.map(this.make_value(2))),
            ...Object.fromEntries(datas.potential.map(this.make_value(3))),
            ...Object.fromEntries(datas.conference.map(this.make_value(4))),
            ...Object.fromEntries(datas.retrospective.map(this.make_value(5))),
        });
        const cardDetails = cardsData();
        this.details = Object.fromEntries(
            Object.entries(this.cards).map(
                ([_, card]) => {
                    return [card.name, {
                        ...cardDetails[card.name].props,
                        ...{kind: cardDetails[card.name].kind}
                    }]
                }
            )
        );
    }

    private make_value(zone_index: number) {
        return (value: string, index: number) => {
            return [
                this.id(zone_index, index), !value ? false : new Card(
                    Activity.zone_names[zone_index],
                    index,
                    value,
                ),
            ];
        }
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
        return Object.keys(this.cards).filter(key => this.selection[key]).map(key => this.cards[key]);
    }

    public cards_in_zone(zone_id: number) {
        return Object.entries(this.cards).filter(
            ([key, _]) => this.zone_id(key) === zone_id
        );
    }

    public selected_in_zone(zone_id: number) {
        return Object.keys(this.cards)
            .filter(key => this.selection[key] && this.zone_id(key) === zone_id)
            .map(key => this.cards[key]);
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
