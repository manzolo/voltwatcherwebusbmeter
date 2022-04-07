/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import React, { useState, useEffect } from 'react';
import ReactDOM from "react-dom/client";
import Devices from './Device/Devices';
import ReactDevicesChart from './Chart/react-devices-chart';

class Main extends React.Component {
    constructor() {
        super();
    }

    render() {
        return <React.Fragment>
            <Devices />
            <ReactDevicesChart />
        </React.Fragment>;
    }
}


export default Main;