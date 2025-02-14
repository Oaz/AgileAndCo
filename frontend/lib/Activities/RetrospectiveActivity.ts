import {Activity} from "./Activity";

export abstract class RetrospectiveActivity extends Activity {
    constructor(datas) {
        super(datas, 'ACTIVITY_RETROSPECTIVE');
    }
    public compute_cost(cardDetails:any) : number {
        let cost = cardDetails.cost;
        if(
            (cardDetails.kind === 'AGILE_MATURITY' || cardDetails.kind === 'AGILE_VALUE')
            && this.datas.company.includes('AGILE_MATURITY_INTERNAL_COACH')
        )
            cost -=  1;
        if(cardDetails.kind === 'PRODUCT_TEAM' && this.datas.company.includes('AGILE_MATURITY_PASSIONATE_DEVELOPER'))
            cost -= 1;
        if(this.datas.initiate)
            cost -= 1;
        return cost;
    }
}

