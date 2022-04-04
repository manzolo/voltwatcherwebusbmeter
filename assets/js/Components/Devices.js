import React, { Component } from "react";
import moment from 'moment';
import Device from './Device';
const Routing = require('./Routing');

class Devices extends Component {
    constructor(props) {
        //console.log(props);

        super(props);
        this.state = {
            devices: []
        };
    }
    componentDidMount() {
        let routeDevices = Routing.generate('Device_List');
        fetch(routeDevices)
                .then(response => response.json())
                .then(deviceinfo => {
                    //console.log(deviceinfo);
                    this.setState({devices: deviceinfo});
                });
    }
    render() {
        return <React.Fragment>{this.state.devices.map(({ id, address, name }) => (
                                                <Device
                                                    key={id}
                                                    deviceid={id}
                                                    address={address}
                                                    devicename={name}
                                                    >
                                                </Device>
                                            ))}</React.Fragment>; 
    }
}
export default Devices;