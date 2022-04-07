import React, { Component } from "react";
import moment from 'moment';
import DeviceLastWeekLogDetails from './DeviceLastWeekLogDetails';
const Routing = require('../Routing');

class DeviceLastWeekLog extends Component {
    constructor(props) {
        super(props);
        this.state = {
            logs: {}
        };
    }
    componentDidMount() {
        let routeLog = Routing.generate('Log_last_week', {device: this.props.deviceid});

        fetch(routeLog)
                .then(response => response.json())
                .then(logs => {
                    this.setState({logs: logs});

                });
    }
    render() {
        if (Object.keys(this.state.logs).length === 0) {
            return null;
        }

        return <React.Fragment>{this.state.logs.map(({ id, date, volt }) => (
                                <DeviceLastWeekLogDetails
                                    key={id}
                                    deviceid={id}
                                    date={date}
                                    volt={volt}
                                    >
                                </DeviceLastWeekLogDetails>
                                    ))}</React.Fragment>;
    }
}
export default DeviceLastWeekLog;