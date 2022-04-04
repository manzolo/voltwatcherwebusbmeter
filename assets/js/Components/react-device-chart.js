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

    componentWillUnmount() {
        clearInterval(this.interval);
    }

    componentDidMount() {
        this.refreshData();
        this.interval = setInterval(() => {
            this.refreshData();
        }, 1000 * 60 * 5);

    }

    refreshData() {
        let routeLog = Routing.generate('Device_Chart', {device: this.props.deviceid});

        fetch(routeLog)
                .then(response => response.json())
                .then(deviceinfo => {
                    //console.log(deviceinfo);
                    //console.log(Object.keys(deviceinfo).length);
                    var myoptions = {
                        title: this.props.devicename,
                        tooltip: {textStyle: {color: '#0073e6'}, showColorCode: true, isHtml: true, trigger: "visible"},
                        //tooltip: { isHtml: true, trigger: "visible" },
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
                        chartArea: {'width': '97%', 'height': '80%'},
                        legend: {position: 'none'},
                        vAxis: {
                            format: '#0.00',
                            gridlines: {count: -1},
                            textStyle: {
                                fontName: 'Roboto',
                                fontSize: '10'},
                            //minValue: 11, maxValue: 15,
                            //viewWindow: {min: 10, max: 15}
                            //gridlines: {color: 'none'}
                        }
                    };
                    for (var i = 1; i < deviceinfo.length; i++) {
                        deviceinfo[i][0] = new Date(deviceinfo[i][0]);
                    }
                    /*var google = window.google;
                     var date_formatter = google.visualization.DateFormat({
                     pattern: "MMM dd, yyyy"
                     });
                     date_formatter.format(deviceinfo, 0);*/
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
                chartLanguage= 'it'
                data={this.state.chart}
                options={this.state.options}
                loader={ < div > Caricamento grafico {this.props.devicename
                                }
                                ... < /div>}
                                        width = "100%"
                                        height = "400px"
                                legendToggle
                                            />
                                </React.Fragment>;

                }
                }
                export default ReactDeviceChart;
        
