
export class BGA {
    private static _translate: any;
    private static _performAction: any;

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
}



