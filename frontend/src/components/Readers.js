import React, {Component} from 'react';
import PropTypes from 'prop-types';
import { Grid, PageHeader } from 'react-bootstrap';
import ReadersTable from './Readers/ReadersTable';
import 'whatwg-fetch';
import ReaderRow from './Readers/ReaderRow';

class Readers extends Component {
    constructor() {
        super();

        this.state = {
            readers: []
        };
    }

    componentDidMount() {
        fetch('http://localhost:8080/readers')
            .then(response => response.json())
            .then(json => this.setState({readers: json.data}))
            .catch(e => e)
    }

    render() {
        return (
            <Grid>
                <PageHeader>Readers</PageHeader>
                <ReadersTable readers={this.state.readers} />
            </Grid>
        );
    }
}

Readers.propTypes = {};
Readers.defaultProps = {};

export default Readers;
