import { Chart } from "react-google-charts";
import React, { useState, useEffect } from 'react';
import ReactDOM from "react-dom/client";
const Routing = require('./Routing');

class ReactDeviceChart extends React.Component {

    constructor(props) {
        //console.log(props);
        super(props);
        this.state = {
            chart: [],
            options: {}
        };
    }
    componentDidMount() {
        let routeLog = Routing.generate('Device_Chart', {device: this.props.deviceid});

        fetch(routeLog)
                .then(response => response.json())
                .then(deviceinfo => {
                    //console.log(deviceinfo);
                    //console.log(Object.keys(deviceinfo).length);
                    var myoptions = {
                        title: this.props.devicename,
                        hAxis: {
                            format: 'dd/MM HH:mm',
                            gridlines: {
                                color: 'none'
                            }

                        },
                        legend: {position: 'none'},
                        vAxis: {
                            format: '#0',
                            //minValue: 11,maxValue: 15,
                            //viewWindow: { min: 10, max: 15 }
                            //gridlines: {color: 'none'}
                        }
                    };
                    for (var i = 1; i < deviceinfo.length; i++) {
                        deviceinfo[i][0] = new Date(deviceinfo[i][0]);
                    }
                    this.setState({chart: deviceinfo, options: myoptions});
                });
    }
    render() {
        if (Object.keys(this.state.chart).length <= 2) {
            return null;
        }
        return <React.Fragment>
            <Chart
                chartType="LineChart"
                data={this.state.chart}
                options={this.state.options}
                width="100%"
                height="400px"
                legendToggle
                />
        </React.Fragment>;

    }
}
export default ReactDeviceChart;

