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
    refreshData(deviceinfo) {
        //console.log('ReactDevicesChart called from parent');
        this.setState({devices: deviceinfo, isLoading: false});
        this.state.devices.map(({ id }) => (this.getOrCreateRef(id)).current.refreshData());
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
