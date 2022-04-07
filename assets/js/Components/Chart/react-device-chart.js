import { Chart } from "react-google-charts";
import { TailSpin } from  'react-loader-spinner'

import React from 'react';
import ReactDOM from "react-dom/client";

import {withGetScreen} from 'react-getscreen';

const Routing = require('../Routing');

const refreshInterval = 1000 * 60 * 5;
//const refreshInterval = 1000 * 5;

class ReactDeviceChart extends React.Component {

    constructor(props) {
        //console.log(props);
        super(props);
        this.state = {
            chart: [],
            options: {},
            width: props.innerWidth, height: props.innerHeight,
            hasError: false,
            sessionExpired: false,
            error: null,
            forceRefresh: false

        };
    }

    static getDerivedStateFromError(error) {
        return {hasError: true, error: error};
    }
    componentDidMount() {
        try {
            let routeLog = Routing.generate('Device_Chart', {device: this.props.deviceid});
            fetch(routeLog)
                    .then(response => {
                        if (response.status >= 400) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(deviceinfo => {
                        var width = (this.state.width <= 500) ? '80%' : '95%';
                        var myoptions = {
                            title: this.props.devicename,
                            tooltip: {textStyle: {color: '#0073e6'}, showColorCode: true, isHtml: true, trigger: "visible"},
                            hAxis: {
                                format: 'dd/MM HH:mm',
                                gridlines: {
                                    color: 'none'
                                },
                                textStyle: {
                                    fontName: 'Roboto',
                                    fontSize: '10',
                                    format: 'd/m/Y'
                                }
                            },
                            chartArea: {'width': width, 'height': '80%'},
                            legend: {position: 'none'},
                            vAxis: {
                                format: '#0.0',
                                gridlines: {count: -1},
                                textStyle: {
                                    fontName: 'Roboto',
                                    fontSize: '10'}
                                //minValue: 11, maxValue: 15,
                                //viewWindow: {min: 10, max: 15}
                                //gridlines: {color: 'none'}
                            }
                        };
                        for (var i = 1; i < deviceinfo.length; i++) {
                            deviceinfo[i][0] = new Date(deviceinfo[i][0]);
                        }
                        this.setState({chart: deviceinfo, options: myoptions, hasError: false, error: null, sessionExpired: false});
                    })
                    .catch(error => {
                        this.setState({hasError: true, error: error, sessionExpired: false});
                    });
        } catch (e) {
            this.setState({hasError: true, error: e, sessionExpired: false});
        }
    }

    render() {
        if (this.state.sessionExpired) {
            window.location.reload(false);
        }
        if (this.state.hasError) {
            return <div className="alert alert-danger" role="alert">
                {this.state.error.message}
            </div>;
        }
        if (Object.keys(this.state.chart).length <= 2)
        {
            return null;
        }

        var wait = <TailSpin height="100" width="100" color='blue' ariaLabel='loading' />;

        return <React.Fragment>
            <Chart
                chartType="LineChart"
                chartLanguage= 'it'
                data=
                {this.state.chart}
                options={this.state.options}
                loader={wait}
                width = "100%"
                height = "400px"
                legendToggle
                />
        </React.Fragment>;
    }
}
export default ReactDeviceChart;

