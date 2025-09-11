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

export type Interaction = 'NEUTRAL' | 'ACTIVE' | 'FROZEN';

export abstract class Action {
    protected constructor(enabled:boolean) {
        this.enabled = enabled
    }
    public readonly enabled : boolean;
    public abstract get can_act() : boolean;
    public abstract get action_text(): string;
    public abstract do_act(): void;
}
