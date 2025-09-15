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

export class BGA {
    private static _translate: any = x => x;
    private static _performAction: any;
    private static _host: any = null;

    public static setTranslate(translate) {
        this._translate = translate;
    }

    public static setPerformAction(performAction) {
        this._performAction = performAction;
    }

    public static translate(text:string) {
        return this._translate(text);
    }

    public static performAction(action: string, args) {
        return this._performAction(action, args);
    }

    public static setHost(host) {
        this._host = host;
    }

    public static useActionButton() : boolean {
        return this._host !== null && this._host !== undefined;
    }

    public static defineActionButton(text: string, enabled:boolean, callback: () => void) {
        this._host.statusBar.removeActionButtons();
        this._host.statusBar.addActionButton(text, callback, {
            disabled: !enabled,
        });
    }
}



