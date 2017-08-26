function _parseNumber(value) {
    return parseFloat(value) || 0;
}

export function counter(state = { count: 0 }, action) {
    switch (action.type) {
        case 'INCREMENT':
            return { count: state.count + _parseNumber(action.by) };
        case 'DECREMENT':
            return { count: state.count - _parseNumber(action.by) };
        default:
            return state;
    }
}

export function form(state = { value: 1 }, action) {
    switch (action.type) {
        case 'UPDATE_VALUE':
            return { value: _parseNumber(action.value) };
        default:
            return state;
    }
}
