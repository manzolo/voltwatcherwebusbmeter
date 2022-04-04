import React, { useState, useEffect } from 'react';
import ReactDOM from "react-dom/client";
import ReactDeviceChart from './react-device-chart.js'
const Routing = require('./Routing');

class ReactDevicesChart extends React.Component {
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
                                                <ReactDeviceChart
                                                    key={id}
                                                    deviceid={id}
                                                    address={address}
                                                    devicename={name?name:address}
                                                    >
                                                </ReactDeviceChart>
                                            ))}</React.Fragment>; 
    }
}
const root = ReactDOM.createRoot(document.getElementById("chart"));
root.render(
        <React.StrictMode>
            <ReactDevicesChart />
        </React.StrictMode>
        );

