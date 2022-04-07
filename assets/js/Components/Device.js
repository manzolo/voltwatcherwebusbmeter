import React, { useState, useEffect, Component } from "react";

import moment from 'moment';
import DeviceLastWeekLog from './DeviceLastWeekLog';
import '../../css/battery.scss';
import { Oval } from  'react-loader-spinner';
const Routing = require('./Routing');

const refreshInterval = 1000 * 60 * 5;
//const refreshInterval = 1000 * 5;

//import fontawesome from '@fortawesome/fontawesome'
//import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
//import { faBatteryEmpty } from '@fortawesome/fontawesome-free-solid'
//fontawesome.library.add(faBatteryEmpty);

const minSwipeDistance = 50;

class Device extends Component {

    constructor(props) {
        super(props);
        this.state = {
            device: {},
            width: window.innerWidth, height: window.innerHeight,
            touchStart: null, touchEnd: null,
            isLoading: true,
            hasError: false,
            sessionExpired: false,
            error: null
        };
        this.self = this;
    }
    onTouchStart = (e) => {
        this.setState({touchStart: e.targetTouches[0].clientY, touchEnd: null});
    }

    onTouchMove = (e) => {
        this.setState({touchEnd: e.changedTouches[0].clientY});
    }
    onTouchEnd = (e) => {
        if (!this.state.touchStart || !this.state.touchEnd) return;
        const distance = this.state.touchStart - this.state.touchEnd;
        const isUpSwipe = distance > minSwipeDistance;
        const isDownSwipe = distance < -minSwipeDistance;
        if (isUpSwipe || isDownSwipe) {
            if (isDownSwipe) {
                this.refreshData();
            }
        }
    }

    componentWillUnmount() {
        this.resizeHandler = removeEventListener('resize', this.updateDimensions);
        this.swipeStartHandler = removeEventListener("touchstart", this.onTouchStart);
        this.swipeMoveHandler = removeEventListener("touchmove", this.onTouchMove);
        this.swipeEndHandler = removeEventListener("touchend", this.onTouchEnd);
        clearInterval(this.interval);
    }

    componentDidMount() {
        const self = this; //  this should not be double quoted;
        this.resizeHandler = addEventListener('resize', this.updateDimensions);
        this.swipeStartHandler = addEventListener("touchstart", this.onTouchStart, {passive: false});
        this.swipeMoveHandler = addEventListener("touchmove", this.onTouchMove, {passive: false});
        this.swipeEndHandler = addEventListener("touchend", this.onTouchEnd, {passive: false});

        this.refreshData();
        this.interval = setInterval(() => {
            this.refreshData();
        }, refreshInterval);

    }

    updateDimensions = () => {
        this.setState({width: window.innerWidth, height: window.innerHeight});
        this.refreshData();
    }

    refreshData() {
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
                        this.setState({device: deviceinfo, isLoading: false, hasError: false, error: null, sessionExpired: false});
                    })
                    .catch(error => {
                        this.setState({hasError: true, error: error, sessionExpired: false});
                    });

            ;
        } catch (e) {
            this.setState({hasError: true, error: e, sessionExpired: false});
        }
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
