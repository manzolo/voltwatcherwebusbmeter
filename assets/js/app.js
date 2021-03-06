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
    GoogleCharts.load(function () {});
    $(document).on("click", "#tabLog3a-tab", function () {
        if ($("#mygraph").hasClass("invisible")) {
            setTimeout(function () {
                loadgraphs();
            }, 1000);
            $("#mygraph").removeClass("invisible");
        }
    });
});
