import React, {Component} from 'react';

class StateCounterWithoutRef extends Component {
    constructor() {
        super();

        this.state = {
            value: 1,
            counter: 0
        };
    }

    _updateValue = (event) => {
        this.setState({
            value: event.target.value
        });
    };

    _increment = () => {
        this.setState({
            counter: this.state.counter + this._parseNumber(this.state.value)
        });
    };

    _decrement = () => {
        this.setState({
            counter: this.state.counter - this._parseNumber(this.state.value)
        });
    };

    _parseNumber(value) {
        return parseFloat(value) || 0;
    }

    render() {
        return (
            <div>
                <input type="number" placeholder='Value' value={this.state.value} onChange={this._updateValue} />
                <button onClick={this._increment}>+</button>
                <button onClick={this._decrement}>-</button>
                <p>Counter: {this.state.counter}</p>
            </div>
        );
    }
}

export default StateCounterWithoutRef;
