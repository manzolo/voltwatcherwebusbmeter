import React, { Component, useRef } from "react";
import moment from 'moment';
import Device from './Device';
import { Oval } from  'react-loader-spinner';
const Routing = require('../Routing');

class Devices extends Component {
    constructor(props) {
        super(props);
        this.references = {};
        this.state = {
            devices: [],
            isLoading: true
        };
    }
    getOrCreateRef(id) {
        if (!this.references.hasOwnProperty(id)) {
            this.references[id] = React.createRef();
        }
        return this.references[id];
    }
    refreshData() {
        //console.log('Devices called from parent');
        let routeDevices = Routing.generate('Device_List');
        fetch(routeDevices)
                .then(response => response.json())
                .then(deviceinfo => {
                    this.setState({devices: deviceinfo, isLoading: false});
                });
        this.state.devices.map(({ id }) => (
                this.getOrCreateRef(id)).current.refreshData());
    }

    componentDidMount() {
        this.refreshData();
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
                                            ref={this.getOrCreateRef(id)}
                                            >
                                        </Device>
                                            ))}</React.Fragment>;
        }

    }
}
export default Devices;