import React, {Component} from 'react';
import {BrowserRouter as Router, Route, Switch} from 'react-router-dom';
import MenuBar from './components/MenuBar';
import routes from './config/routes';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/css/bootstrap-theme.min.css';
import 'font-awesome/css/font-awesome.min.css';
import './App.css';

class App extends Component {
    render() {
        return (
            <Router>
                <div>
                    <MenuBar />
                    <Switch>
                        {routes.map((route, idx) => <Route {...route} key={idx} />)}
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default App;
