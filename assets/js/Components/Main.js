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

const minSwipeDistance = 50;
const refreshInterval = 1000 * 60 * 5;

class Main extends React.Component {
    constructor(props) {
        super(props);
        this.devicesHook = React.createRef();
        this.chartsHook = React.createRef();
        this.state = {
            width: window.innerWidth, height: window.innerHeight,
            touchStart: null, touchEnd: null
        };
    }

    forceRefreshAction = () => {
        this.setState({forceRefresh: !this.state.forceRefresh});
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
        this.setState({width: window.innerWidth, height: window.innerHeight});
        this.refreshData();
    }
    refreshData() {
        //console.log("Trigger Refresh MAIN");
        this.devicesHook.current.refreshData();
        this.chartsHook.current.refreshData();
    }

    render() {
        return <React.Fragment>
            <Devices innerWidth="{this.state.width}" innerHeight="{this.state.height}" ref={this.devicesHook}/>
            <ReactDevicesChart  innerWidth="{this.state.width}" innerHeight="{this.state.height}" ref={this.chartsHook}/>
        </React.Fragment>;
    }
}


export default Main;