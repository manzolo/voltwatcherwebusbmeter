import React, { Component } from "react";
import moment from 'moment';
import Device from './Device';
import { Oval } from  'react-loader-spinner';
const Routing = require('./Routing');

class Devices extends Component {
    constructor(props) {
        //console.log(props);

        super(props);
        this.state = {
            devices: [],
            isLoading: true
        };
    }
    componentDidMount() {
        let routeDevices = Routing.generate('Device_List');
        fetch(routeDevices)
                .then(response => response.json())
                .then(deviceinfo => {
                    //console.log(deviceinfo);
                    this.setState({devices: deviceinfo, isLoading: false});
                });
    }
    render() {
        if (this.state.isLoading) {
            return <Oval height="100" width="100" color='blue' ariaLabel='loading' />;
        } else {
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
}
export default Devices;