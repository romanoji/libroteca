import React, {Component} from 'react';

// https://facebook.github.io/react/docs/uncontrolled-components.html
class StateCounter extends Component {
    constructor() {
        super();

        this.state = {
            counter: 0
        };
    }

    _increment = () => {
        this.setState({
            counter: this.state.counter + this._parseNumber(this._input.value)
        });
    };

    _decrement = () => {
        this.setState({
            counter: this.state.counter - this._parseNumber(this._input.value)
        });
    };

    _parseNumber(value) {
        return parseFloat(value) || 0;
    }

    render() {
        return (
            <div>
                <input type="number" placeholder='Value' ref={input => this._input = input} defaultValue={1} />
                <button onClick={this._increment}>+</button>
                <button onClick={this._decrement}>-</button>
                <p>Counter: {this.state.counter}</p>
            </div>
        );
    }
}

export default StateCounter;
