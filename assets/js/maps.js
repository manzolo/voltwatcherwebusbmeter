/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/maps.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');
import Feature from 'ol/Feature.js';
import Map from 'ol/Map.js';
import View from 'ol/View.js';
import {easeIn, easeOut} from 'ol/easing.js';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer.js';
import TileJSON from 'ol/source/TileJSON.js';
import {addProjection, addCoordinateTransforms, fromLonLat, transform} from 'ol/proj.js';
import Projection from 'ol/proj/Projection.js';
import Point from 'ol/geom/Point.js';
import OSM from 'ol/source/OSM.js';
import {Icon, Style} from 'ol/style.js';
import VectorSource from 'ol/source/Vector.js';
import Control from 'ol/control/Control.js';
import {defaults as defaultControls, Attribution} from 'ol/control';
import XYZ from 'ol/source/XYZ';
import {defaults as defaultInteractions} from 'ol/interaction.js';

import Overlay from 'ol/Overlay.js';

import zoom from 'ol/control/Zoom.js';

import animation from './mapanimation.js';

$(document).ready(function () {
    //$("#map").hide();
    var coordinate;
    var map;
    var rome = fromLonLat([12.5, 41.9]);
    coordinate = rome;

    var view = new View({
        center: rome,
        zoom: 15
    });

    var marker;

    $(document).on("click", "#showmap", function () {

        map = new Map({
            view: view,
            target: 'map',
            projection: 'EPSG:4326',
            layers: [new TileLayer({
                    preload: 4,
                    source: new OSM()
                })]
                    /*interactions: defaultInteractions({
                     doubleClickZoom: false,
                     dragAndDrop: false,
                     dragPan: false,
                     keyboardPan: false,
                     keyboardZoom: false,
                     mouseWheelZoom: false,
                     pointer: false,
                     select: false,
                     
                     }),*/
        });
        
        var longitude = parseFloat($("#log_longitude").val());
        var latitude = parseFloat($("#log_latitude").val());
        if (longitude > 0 && latitude > 0) {
            if (marker) {
                map.removeLayer(marker);
            }
            coordinate = fromLonLat([longitude, latitude]);
            marker = new VectorLayer({
                source: new VectorSource({
                    features: [
                        new Feature({
                            geometry: new Point(coordinate)
                        })
                    ]
                })
            });
            map.addLayer(marker);
            map.updateSize();
            map.render();
            animation.flyTo(view, coordinate, function () {});
        }
    });



});

