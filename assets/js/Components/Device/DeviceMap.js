import React, {Component} from "react";
//import fontawesome from '@fortawesome/fontawesome'
//import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
//import { faBatteryEmpty } from '@fortawesome/fontawesome-free-solid'
//fontawesome.library.add(faBatteryEmpty);
import { Map, View }
from 'ol';
import {addProjection, addCoordinateTransforms, fromLonLat, transform} from 'ol/proj.js';
import {Attribution, defaults as defaultControls} from 'ol/control';
import TileLayer from 'ol/layer/Tile';
import OSM from 'ol/source/OSM';
import Overlay from 'ol/Overlay.js';
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
        this.state = {
            longitude: props.longitude,
            latitude: props.latitude,
            deviceid: props.deviceid,
            controls: defaultControls({attribution: false}).extend([attribution]),
            center: [0, 0],
            zoom: 0,
            map: new Map({
                layers: [
                    new TileLayer({
                        source: new OSM()
                    })
                ],
                view: new View({
                    center: this.pos,
                    zoom: 15
                })
            })
        };
    }
    componentDidMount() {
        this.state.map.setTarget("map-container" + this.props.deviceid);
        this.marker = new Overlay({
            position: this.pos,
            positioning: "center-center",
            element: document.getElementById("marker" + this.props.deviceid),
            stopEvent: false
        });
        //console.log(this.marker);

        // Adding to the Map Object
        this.state.map.addOverlay(this.marker);
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
