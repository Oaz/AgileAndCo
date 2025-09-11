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

