import React, {Component} from 'react';
import PropTypes from 'prop-types';
import { Table } from 'react-bootstrap';
import ReaderRow from './ReaderRow';

class ReadersTable extends Component {
    render() {
        const readersRows = this.props.readers.map((reader) => (
            <ReaderRow {...reader} key={reader.id} />
        ));

        return (
            <Table striped bordered condensed hover>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    {readersRows}
                </tbody>
            </Table>
        );
    }
}

ReadersTable.propTypes = {};
ReadersTable.defaultProps = {};

export default ReadersTable;
