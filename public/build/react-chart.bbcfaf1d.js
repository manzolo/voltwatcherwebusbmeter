(self.webpackChunk=self.webpackChunk||[]).push([[547],{8725:t=>{t.exports=window.Routing},21528:(t,e,n)=>{"use strict";n(41539),n(88674),n(21249),n(68309),n(68304),n(30489),n(12419),n(78011),n(69070),n(82526),n(41817),n(32165),n(66992),n(78783),n(33948);var r=n(67294),o=n(20745),i=(n(32564),n(83710),n(47941),n(76887)),c=n(79351);n(7747);function u(t){return u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},u(t)}function a(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}function f(t,e){return f=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},f(t,e)}function l(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}();return function(){var n,r=p(t);if(e){var o=p(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return s(this,n)}}function s(t,e){if(e&&("object"===u(e)||"function"==typeof e))return e;if(void 0!==e)throw new TypeError("Derived constructors may only return object or undefined");return function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t)}function p(t){return p=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},p(t)}var y=n(8725);const h=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),Object.defineProperty(t,"prototype",{writable:!1}),e&&f(t,e)}(s,t);var e,n,o,u=l(s);function s(t){var e;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,s),(e=u.call(this,t)).state={chart:[],options:{}},e}return e=s,(n=[{key:"componentWillUnmount",value:function(){clearInterval(this.interval)}},{key:"componentDidMount",value:function(){var t=this;this.refreshData(),this.interval=setInterval((function(){t.refreshData()}),3e5)}},{key:"refreshData",value:function(){var t=this,e=y.generate("Device_Chart",{device:this.props.deviceid});fetch(e).then((function(t){return t.json()})).then((function(e){for(var n=window.innerWidth<=500?"85%":"97%",r={title:t.props.devicename,tooltip:{textStyle:{color:"#0073e6"},showColorCode:!0,isHtml:!0,trigger:"visible"},hAxis:{format:"dd/MM HH:mm",gridlines:{color:"none"},textStyle:{fontName:"Roboto",fontSize:"10",format:"d/m/Y"}},chartArea:{width:n,height:"80%"},legend:{position:"none"},vAxis:{format:"#0.0",gridlines:{count:-1},textStyle:{fontName:"Roboto",fontSize:"10"}}},o=1;o<e.length;o++)e[o][0]=new Date(e[o][0]);t.setState({chart:e,options:r})})).catch((function(t){console.error("There was an error!",t)}))}},{key:"render",value:function(){if(Object.keys(this.state.chart).length<=2)return null;var t=r.createElement(c.gy,{height:"100",width:"100",color:"blue",ariaLabel:"loading"});return r.createElement(r.Fragment,null,r.createElement(i.kL,{chartType:"LineChart",chartLanguage:"it",data:this.state.chart,options:this.state.options,loader:t,width:"100%",height:"400px",legendToggle:!0}))}}])&&a(e.prototype,n),o&&a(e,o),Object.defineProperty(e,"prototype",{writable:!1}),s}(r.Component);function b(t){return b="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},b(t)}function d(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}function v(t,e){return v=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},v(t,e)}function m(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}();return function(){var n,r=g(t);if(e){var o=g(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return w(this,n)}}function w(t,e){if(e&&("object"===b(e)||"function"==typeof e))return e;if(void 0!==e)throw new TypeError("Derived constructors may only return object or undefined");return function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t)}function g(t){return g=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},g(t)}var O=n(8725),j=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),Object.defineProperty(t,"prototype",{writable:!1}),e&&v(t,e)}(c,t);var e,n,o,i=m(c);function c(t){var e;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,c),(e=i.call(this,t)).state={devices:[]},e}return e=c,(n=[{key:"componentDidMount",value:function(){var t=this,e=O.generate("Device_List");fetch(e).then((function(t){return t.json()})).then((function(e){t.setState({devices:e})}))}},{key:"render",value:function(){return r.createElement(r.Fragment,null,this.state.devices.map((function(t){var e=t.id,n=t.address,o=t.name;return r.createElement(h,{key:e,deviceid:e,address:n,devicename:o||n})})))}}])&&d(e.prototype,n),o&&d(e,o),Object.defineProperty(e,"prototype",{writable:!1}),c}(r.Component);o.createRoot(document.getElementById("chart")).render(r.createElement(r.StrictMode,null,r.createElement(j,null)))}},t=>{t.O(0,[109,78,845,458],(()=>{return e=21528,t(t.s=e);var e}));t.O()}]);