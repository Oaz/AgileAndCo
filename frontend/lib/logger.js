
const is_debug_active = false;

export const debuglog = is_debug_active
    ? (...args) => console.debug(...args)
    : () => {};
