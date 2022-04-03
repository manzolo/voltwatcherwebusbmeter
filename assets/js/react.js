/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import Devices from './Components/Devices';
const Routing = require('./Components/Routing');

class Device extends React.Component {
    constructor() {
        super();
    }

    render() {
        return <React.Fragment><Devices /></React.Fragment>;

    }
}

ReactDOM.render(<Device />, document.getElementById('react'));