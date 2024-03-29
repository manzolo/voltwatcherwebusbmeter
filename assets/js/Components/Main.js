/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import React from 'react';

import Devices from './Device/Devices';
import ReactDevicesChart from './Chart/react-devices-chart';

import Swal from 'sweetalert2';
import withReactContent from 'sweetalert2-react-content';

import Routing from './Routing';

const minSwipeDistance = 50;
const refreshInterval = 1000 * 60 * 5;
//const refreshInterval = 1000 * 5;

const MySwal = withReactContent(Swal);

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
        this.resizeHandler = addEventListener('resize', this.updateDimensions);
        this.swipeStartHandler = addEventListener("touchstart", this.onTouchStart, {passive: false});
        this.swipeMoveHandler = addEventListener("touchmove", this.onTouchMove, {passive: false});
        this.swipeEndHandler = addEventListener("touchend", this.onTouchEnd, {passive: false});

        this.interval = setInterval(() => {
            this.refreshData();
        }, refreshInterval);
        this.refreshData();
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

                            Swal.fire({
                                title: 'Tornare al login?',
                                text: 'Sessione scaduta',
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });

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
                        this.setState({hasError: false, error: null});
                        if (this.devicesHook.current) {
                            this.devicesHook.current.refreshData(deviceinfo);
                        }
                        if (this.chartsHook.current) {
                            this.chartsHook.current.refreshData(deviceinfo);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Ricaricare la pagina?',
                            text: 'Si è verificato un errore: ' + error,
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                        this.setState({hasError: true, error: error});
                    });

            ;
        } catch (e) {
            Swal.fire({
                title: 'Ricaricare la pagina?',
                text: 'Si è verificato un errore',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
            this.setState({hasError: true, error: e});
        }

    }

    render() {
        if (this.state.hasError) {
            return <div className="alert alert-danger" role="alert">{this.state.error.message}</div>;
        }
        return <React.Fragment>
            <Devices key="device" ref={this.devicesHook}/>
            <ReactDevicesChart key="chart" ref={this.chartsHook}/>
        </React.Fragment>;
    }
}


export default Main;