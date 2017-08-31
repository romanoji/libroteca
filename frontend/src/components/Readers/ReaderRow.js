import React, {Component} from 'react';
import PropTypes from 'prop-types';

class ReaderRow extends Component {
    render() {
        return (
            <tr>
                <td>{this.props.id}</td>
                <td>{this.props.name}</td>
                <td>{this.props.surname}</td>
                <td>{this.props.email}</td>
                <td>{this.props.phone}</td>
            </tr>
        );
    }
}

ReaderRow.propTypes = {
    id: PropTypes.oneOfType([PropTypes.number, PropTypes.string]).isRequired,
    name: PropTypes.string.isRequired,
    surname: PropTypes.string.isRequired,
    email: PropTypes.string.isRequired,
    phone: PropTypes.string.isRequired
};
ReaderRow.defaultProps = {};

export default ReaderRow;
