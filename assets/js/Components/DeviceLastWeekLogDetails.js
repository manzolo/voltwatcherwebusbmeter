import React, { Component } from "react";
import moment from 'moment';

class DeviceLastWeekLogDetails extends Component {
    constructor(props) {
        //console.log("now");
        //console.log(props);
        super(props);
    }

    render() {
        return (
                <p className="card-text">{this.props.volt} {moment(this.props.date).format('DD/MM/YYYY')}</p>
                );
    }
}
export default DeviceLastWeekLogDetails;