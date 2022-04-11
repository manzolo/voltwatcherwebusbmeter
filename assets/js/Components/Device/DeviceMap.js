import React, {Component} from "react";
//import fontawesome from '@fortawesome/fontawesome'
//import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
//import { faBatteryEmpty } from '@fortawesome/fontawesome-free-solid'
//fontawesome.library.add(faBatteryEmpty);
import { Map, View }
from 'ol';
import {addProjection, addCoordinateTransforms, fromLonLat, transform} from 'ol/proj.js';
import {Attribution, defaults as defaultControls} from 'ol/control';
import {Icon, Style} from 'ol/style';
import Feature from 'ol/Feature';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer';
import OSM from 'ol/source/OSM';
import Point from 'ol/geom/Point';
import Text from 'ol/style/Text';
import VectorSource from 'ol/source/Vector';

import 'ol/ol.css';
import '../../../css/maps.css';




const Routing = require('../Routing');

class DeviceMap extends Component {

    constructor(props) {
        super(props);
        this.pos = fromLonLat([this.props.longitude, this.props.latitude]);

        const attribution = new Attribution({
            collapsible: true
        });
        const iconFeature = new Feature({
            geometry: new Point(this.pos),
            name: 'Camper'
        });
        const iconStyle = new Style({
            text: new Text({
                text: '\uf3c5',
                font: '900 25px "Font Awesome 5 Free"'
            })
        });

        iconFeature.setStyle(iconStyle);

        const vectorSource = new VectorSource({
            features: [iconFeature]
        });

        const vectorLayer = new VectorLayer({
            source: vectorSource
        });

        const rasterLayer = new TileLayer({
            source: new OSM()
        });

        this.state = {
            longitude: props.longitude,
            latitude: props.latitude,
            deviceid: props.deviceid,
            controls: defaultControls({attribution: false}).extend([attribution]),
            center: [0, 0],
            zoom: 0,
            map: new Map({
                layers: [rasterLayer, vectorLayer],
                view: new View({
                    center: this.pos,
                    zoom: 15
                })
            })
        };
    }
    componentDidMount() {
        this.state.map.setTarget("map-container" + this.props.deviceid);
    }
    componentDidUpdate() {
        this.state.map.updateSize();
    }

    render() {
        return (
                <React.Fragment>
                    <div style={{display: "none"}}>
                        <div 
                            id={"marker" + this.props.deviceid}
                            title="Marker"
                            className="marker"
                            />
                    </div>
                    <div id={"map-container" + this.props.deviceid} className="map-container"  />
                </React.Fragment>
                );
    }

}
export default DeviceMap;
