import React, {Component} from 'react';
import {BrowserRouter as Router, Route, Switch} from 'react-router-dom';
import routes from './config/routes';
import './App.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/css/bootstrap-theme.css';

class App extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Switch>
                        {routes.map(route => <Route {...route} />)}
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default App;
