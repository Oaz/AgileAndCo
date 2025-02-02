import {Activity} from "./Activity";

export abstract class RetrospectiveActivity extends Activity {
    constructor(datas) {
        super(datas, 'ACTIVITY_RETROSPECTIVE');
    }

    public compute_cost(key:string) : number {
        const details = this.details[key];
        let cost = details.cost;
        if(
            (details.kind === 'AGILE_MATURITY' || details.kind === 'AGILE_VALUE')
            && this.datas.company.includes('AGILE_MATURITY_INTERNAL_COACH')
        )
            cost -=  1;
        if(details.kind === 'PRODUCT_TEAM' && this.datas.company.includes('AGILE_MATURITY_PASSIONATE_DEVELOPER'))
            cost -= 1;
        return cost;
    }
}

