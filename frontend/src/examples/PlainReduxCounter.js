import React, {Component} from 'react';
import { increment, decrement, updateValue } from './actions';
import { counter, form } from './reducers';
import { createStore } from 'redux';

let counterStore = createStore(counter);
let formStore = createStore(form);

class PlainReduxCounter extends Component {
    // https://gist.github.com/gaearon/1d19088790e70ac32ea636c025ba424e
    _increment = () => {
        counterStore.dispatch(increment(formStore.getState().value));
        this.forceUpdate();
    };

    _decrement = () => {
        counterStore.dispatch(decrement(formStore.getState().value));
        this.forceUpdate()
    };

    render() {
        return (
            <div>
                <input type="number"
                       onChange={event => formStore.dispatch(updateValue(event.target.value))}
                       defaultValue={formStore.getState().value}/>
                <button onClick={this._increment}>+</button>
                <button onClick={this._decrement}>-</button>
                <p>Counter: {counterStore.getState().count}</p>
            </div>
        );
    }
}

export default PlainReduxCounter;
