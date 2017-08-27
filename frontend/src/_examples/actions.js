// counter
export const INCREMENT = 'INCREMENT';
export const DECREMENT = 'DECREMENT';

export function increment(by) {
    return { type: INCREMENT, by }
}

export function decrement(by) {
    return { type: DECREMENT, by }
}


// form input
export const UPDATE_VALUE = 'UPDATE_VALUE';

export function updateValue(value) {
    return { type: UPDATE_VALUE, value }
}
