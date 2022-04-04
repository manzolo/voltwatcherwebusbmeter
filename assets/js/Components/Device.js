import React, { Component } from "react";
import moment from 'moment';
import DeviceLastWeekLog from './DeviceLastWeekLog';
import '../../css/battery.scss';
import { Oval } from  'react-loader-spinner';
const Routing = require('./Routing');

//import fontawesome from '@fortawesome/fontawesome'
//import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
//import { faBatteryEmpty } from '@fortawesome/fontawesome-free-solid'
//fontawesome.library.add(faBatteryEmpty);


class Device extends Component {
    constructor(props) {
        //console.log("now");
        //console.log(props);

        super(props);
        this.state = {
            device: {},
            isLoading: true
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
        let routeLog = Routing.generate('Log_last', {device: this.props.deviceid});

        fetch(routeLog)
                .then(response => response.json())
                .then(deviceinfo => {
                    this.setState({device: deviceinfo, isLoading: false});
                });
    }
    render() {
        if (Object.keys(this.state.device).length === 0) {
            return null;
        }
        if (this.state.isLoading) {
            return <Oval height="100" width="100" color='blue' ariaLabel='loading' />;
        } else {
            return (
                    <div key={this.props.id} className="col-6 col-lg-3">
                        <div className="card-wrapper">
                            <div className="card">
                                <div className="card-body">
                                    <div className="categoryicon-top" title={this.state.device.batteryperc + '%'}>
                                        <i className="fa fa-battery-empty font-70px fa-battery-filling" aria-hidden="true">
                                            <span data-perc={this.state.device.batteryperc} style={{width: `calc(${this.state.device.batteryperc}% * 0.73)`}}></span>
                                        </i>
                                    </div>
                                    <h6 className="card-title">
                                        {this.state.device.devicename}<br/>
                                        {this.state.device.volt} v<br/>
                                        {this.state.device.batteryperc}%
                                    </h6>
                                    <p className="card-text" data-toggle="collapse" data-target="#collapseStorico" aria-label="Storico modifiche" aria-expanded="false" aria-controls="collapseStorico">{moment(this.state.device.date).format('DD/MM/YYYY HH:mm')}</p>
                                    <div className="collapse" id="collapseStorico">
                                        <DeviceLastWeekLog deviceid={this.props.deviceid}/>
                                    </div>
                                    <img src={"https://openweathermap.org/img/wn/" + this.state.device.weathericon + "@2x.png"} alt="Weather icon" title={`${this.state.device.location}`}></img>
                                    <br />
                                    {this.state.device.location}
                                </div>
                            </div>
                        </div>
                    </div>
                            );

                }
            }
        }
        export default Device;