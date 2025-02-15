
export class Card {

    public constructor(zone: string, index: number, name: string) {
        this.zone = zone;
        this.index = index;
        this.name = name;
    }

    public zone: string;
    public index: number;
    public name: string;
}