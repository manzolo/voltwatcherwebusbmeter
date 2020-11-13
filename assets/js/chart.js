/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

import {GoogleCharts} from 'google-charts';

$(document).ready(function () {
    GoogleCharts.load(function () {
        if ($("#mygraph").hasClass("invisible")) {
            //setTimeout(function () {
            loadChart();
            $("#mygraph").removeClass("invisible");
            //}, 1000);
        }
    });
});