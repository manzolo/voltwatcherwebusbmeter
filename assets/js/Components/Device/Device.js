import React, { useState, useEffect, Component } from "react";

import moment from 'moment';
import DeviceLastWeekLog from './DeviceLastWeekLog';
import '../../../css/battery.scss';
import { Oval } from  'react-loader-spinner';
const Routing = require('../Routing');
import DeviceMap from  './DeviceMap';

import Swal from 'sweetalert2';
import withReactContent from 'sweetalert2-react-content';

const MySwal = withReactContent(Swal);

//import fontawesome from '@fortawesome/fontawesome'
//import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
//import { faBatteryEmpty } from '@fortawesome/fontawesome-free-solid'
//fontawesome.library.add(faBatteryEmpty);

class Device extends Component {

    constructor(props) {
        super(props);
        this.state = {
            device: {},
            isLoading: true,
            hasError: false,
            sessionExpired: false,
            error: null,
            mapLoading: true
        };
        this.showMap = this.showMap.bind(this);
    }
    refreshData() {
        //console.log('Device called from parent');
        try {
            let routeLog = Routing.generate('Log_last', {device: this.props.deviceid});

            fetch(routeLog)
                    .then(response => {
                        if (response.status >= 400) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(deviceinfo => {
                        this.setState({device: deviceinfo, isLoading: false, hasError: false, error: null, sessionExpired: false, mapLoading: false});
                    })
                    .catch(error => {
                        this.setState({hasError: true, error: error, sessionExpired: false});
                    });

            ;
        } catch (e) {
            this.setState({hasError: true, error: e, sessionExpired: false});
        }
    }
    componentDidMount() {
        this.refreshData();
    }

    showMap() {
        MySwal.fire({
            showCancelButton: false,
            showConfirmButton: false,
            html: <DeviceMap innerWidth={this.state.device.width} deviceid={this.props.deviceid} longitude={this.state.device.longitude} latitude={this.state.device.latitude}/>
        });
    }

    render() {
        if (Object.keys(this.state.device).length === 0) {
            return null;
        }
        if (this.state.sessionExpired) {
            window.location.reload(false);
        }
        if (this.state.hasError) {
            return <div className="alert alert-danger" role="alert">
                {this.state.error.message}
            </div>;
        }
        if (this.state.isLoading) {
            return <Oval height="100" width="100" color='blue' ariaLabel='loading' />;
        }
        if (this.state.mapLoading) {
            return <Oval height="100" width="100" color='blue' ariaLabel='loading' />;
        }

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
                                <button className="btn btn-xs btn-outline-primary" onClick={this.showMap}>{this.state.device.location}</button>
                            </div>
                        </div>
                    </div>
                </div>
                        );

            }
        }

        export default Device;
