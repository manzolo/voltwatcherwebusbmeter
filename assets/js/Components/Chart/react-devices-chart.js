import React, { useState, useEffect } from 'react';
import ReactDOM from "react-dom/client";
import ReactDeviceChart from './react-device-chart';
const Routing = require('../Routing');

class ReactDevicesChart extends React.Component {
    constructor(props) {
        super(props);
        this.references = {};
        this.state = {
            devices: []
        };
    }
    getOrCreateRef(id) {
        if (!this.references.hasOwnProperty(id)) {
            this.references[id] = React.createRef();
        }
        return this.references[id];
    }
    refreshData() {
        //console.log('ReactDevicesChart called from parent');
        this.state.devices.map(({ id }) => (
                this.getOrCreateRef(id)).current.refreshData());
                
        let routeDevices = Routing.generate('Device_List');
        fetch(routeDevices)
                .then(response => response.json())
                .then(deviceinfo => {
                    //console.log(deviceinfo);
                    this.setState({devices: deviceinfo});
                });

    }
    componentDidMount() {
        this.refreshData();
    }
    render() {
        return <React.Fragment>{this.state.devices.map(({ id, address, name }) => (
                                <ReactDeviceChart
                                    key={id}
                                    deviceid={id}
                                    address={address}
                                    devicename={name ? name : address}
                                    ref={this.getOrCreateRef(id)}
                                    >
                                </ReactDeviceChart>
                                    ))}</React.Fragment>;
    }
}

export default ReactDevicesChart;
