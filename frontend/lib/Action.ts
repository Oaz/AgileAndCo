export abstract class Action {
    protected constructor(enabled:boolean) {
        this.enabled = enabled
    }
    public readonly enabled : boolean;
    public abstract get can_act() : boolean;
    public abstract get action_text(): string;
    public abstract do_act(): void;
}
