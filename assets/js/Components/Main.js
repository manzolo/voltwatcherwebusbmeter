/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import React, {  useRef } from 'react';
import ReactDOM from "react-dom/client";
import Devices from './Device/Devices';
import ReactDevicesChart from './Chart/react-devices-chart';
import Routing from './Routing';

const minSwipeDistance = 50;
const refreshInterval = 1000 * 60 * 5;
//const refreshInterval = 1000 * 5;

class Main extends React.Component {
    constructor(props) {
        super(props);
        this.devicesHook = React.createRef();
        this.chartsHook = React.createRef();
        this.state = {
            hasError: false, error: null,
            touchStart: null, touchEnd: null
        };
    }

    onTouchStart = (e) => {
        this.setState({touchStart: e.targetTouches[0].clientY, touchEnd: null});
    }

    onTouchMove = (e) => {
        this.setState({touchEnd: e.changedTouches[0].clientY});
    }
    onTouchEnd = (e) => {
        if (!this.state.touchStart || !this.state.touchEnd)
            return;
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
        this.refreshData();
    }
    refreshData() {
        //console.log("Trigger Refresh MAIN");

        try {
            let routeDevices = Routing.generate('Device_List');

            fetch(routeDevices)
                    .then(response => {
                        if (response.status >= 400) {
                            throw new Error(response.statusText);
                        }
                        if (response.redirected) {
                            throw new Error("Sessione scaduta, effettuare nuovamente il login");
                        }
                        return response.json();
                        /*const contentType = response.headers.get("content-type");
                         if (contentType && contentType.indexOf("application/json") !== -1) {
                         console.log(response);
                         return response.json();
                         } else {
                         return response.text();
                         }*/

                        //return response.json();
                    })
                    .then(deviceinfo => {
                        this.devicesHook.current.refreshData(deviceinfo);
                        this.chartsHook.current.refreshData(deviceinfo);
                    })
                    .catch(error => {
                        this.setState({hasError: true, error: error});
                    });

            ;
        } catch (e) {
            this.setState({hasError: true, error: e});
        }

    }

    render() {
        if (this.state.hasError) {
            return <div className="alert alert-danger" role="alert">{this.state.error.message}</div>;
        }
        return <React.Fragment>
            <Devices ref={this.devicesHook}/>
            <ReactDevicesChart ref={this.chartsHook}/>
        </React.Fragment>;
    }
}


export default Main;