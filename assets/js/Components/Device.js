import React, { Component } from "react";
import moment from 'moment';
import DeviceLastWeekLog from './DeviceLastWeekLog'
import '../../css/battery.scss'; 
const Routing = require('./Routing');

//import fontawesome from '@fortawesome/fontawesome'
//import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
//import { faBatteryEmpty } from '@fortawesome/fontawesome-free-solid'
//fontawesome.library.add(faBatteryEmpty);


class Device extends Component {
    constructor(props) {
        //console.log("now");
        //console.log(props);

        super(props);
        this.state = {
            device: {}
        };
    }
    componentDidMount() {
        //const [councomponentDidMountt, setCount] = useState(0);

        //useEffect(() => {
        //this.setState({devices: this.props.arr});
        //}, [count]); // Only re-run the effect if count changes
        let routeLog = Routing.generate('Log_last', {device: this.props.deviceid});

        fetch(routeLog)
                .then(response => response.json())
                .then(deviceinfo => {
                    //console.log(deviceinfo);
                    //console.log(Object.keys(deviceinfo).length);
                    this.setState({device: deviceinfo});

                });
    }
    render() {
        if (Object.keys(this.state.device).length === 0) {
            return null;
        }
        return (
                <div key={this.props.id} className="col-6 col-lg-3">
                    <div className="card-wrapper">
                        <div className="card">
                            <div className="card-body">
                                <div className="categoryicon-top" title={this.state.device.batteryperc + '%'}>
                                    <i className="fa fa-battery-empty font-70px fa-battery-filling" aria-hidden="true">
                                        <span data-perc={this.state.device.batteryperc} style={{width: `calc(${this.state.device.batteryperc}% * 0.73)`}}></span>
                                    </i>
                                </div>
                                <h6 className="card-title">
                                    {this.state.device.devicename}<br/>
                                    {this.state.device.volt} v<br/>
                                    {this.state.device.batteryperc}%
                                </h6>
                                <p className="card-text" data-toggle="collapse" data-target="#collapseStorico" aria-label="Storico modifiche" aria-expanded="false" aria-controls="collapseStorico">{moment(this.state.device.date).format('DD/MM/YYYY HH:mm')}</p>
                                <div className="collapse" id="collapseStorico">
                                <DeviceLastWeekLog deviceid={this.props.deviceid}/>
                                </div>
                                <img src={"https://openweathermap.org/img/wn/" + this.state.device.weathericon + "@2x.png"} alt="Weather icon" title={`${this.state.device.location}`}></img>
                                <br />
                                {this.state.device.location}
                            </div>
                        </div>
                    </div>
                </div>

                        );
            }
        }
        export default Device;