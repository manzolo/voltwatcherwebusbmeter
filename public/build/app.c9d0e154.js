(window.webpackJsonp=window.webpackJsonp||[]).push([["app"],{"/GqU":function(t,n,r){var o=r("RK3t"),e=r("HYAF");t.exports=function(t){return o(e(t))}},"0BK2":function(t,n){t.exports={}},"0Dky":function(t,n){t.exports=function(t){try{return!!t()}catch(t){return!0}}},"0GbY":function(t,n,r){var o=r("Qo9l"),e=r("2oRo"),i=function(t){return"function"==typeof t?t:void 0};t.exports=function(t,n){return arguments.length<2?i(o[t])||i(e[t]):o[t]&&o[t][n]||e[t]&&e[t][n]}},"0eef":function(t,n,r){"use strict";var o={}.propertyIsEnumerable,e=Object.getOwnPropertyDescriptor,i=e&&!o.call({1:2},1);n.f=i?function(t){var n=e(this,t);return!!n&&n.enumerable}:o},"2oRo":function(t,n,r){(function(n){var r="object",o=function(t){return t&&t.Math==Math&&t};t.exports=o(typeof globalThis==r&&globalThis)||o(typeof window==r&&window)||o(typeof self==r&&self)||o(typeof n==r&&n)||Function("return this")()}).call(this,r("yLpj"))},"6JNq":function(t,n,r){var o=r("UTVS"),e=r("Vu81"),i=r("Bs8V"),c=r("m/L8");t.exports=function(t,n){for(var r=e(n),u=c.f,a=i.f,f=0;f<r.length;f++){var s=r[f];o(t,s)||u(t,s,a(n,s))}}},"93I0":function(t,n,r){var o=r("VpIT"),e=r("kOOl"),i=o("keys");t.exports=function(t){return i[t]||(i[t]=e(t))}},Bs8V:function(t,n,r){var o=r("g6v/"),e=r("0eef"),i=r("XGwC"),c=r("/GqU"),u=r("wE6v"),a=r("UTVS"),f=r("DPsx"),s=Object.getOwnPropertyDescriptor;n.f=o?s:function(t,n){if(t=c(t),n=u(n,!0),f)try{return s(t,n)}catch(t){}if(a(t,n))return i(!e.f.call(t,n),t[n])}},DPsx:function(t,n,r){var o=r("g6v/"),e=r("0Dky"),i=r("zBJ4");t.exports=!o&&!e((function(){return 7!=Object.defineProperty(i("div"),"a",{get:function(){return 7}}).a}))},HYAF:function(t,n){t.exports=function(t){if(null==t)throw TypeError("Can't call method on "+t);return t}},"I+eb":function(t,n,r){var o=r("2oRo"),e=r("Bs8V").f,i=r("X2U+"),c=r("busE"),u=r("zk60"),a=r("6JNq"),f=r("lMq5");t.exports=function(t,n){var r,s,p,l,v,h=t.target,g=t.global,y=t.stat;if(r=g?o:y?o[h]||u(h,{}):(o[h]||{}).prototype)for(s in n){if(l=n[s],p=t.noTargetGet?(v=e(r,s))&&v.value:r[s],!f(g?s:h+(y?".":"#")+s,t.forced)&&void 0!==p){if(typeof l==typeof p)continue;a(l,p)}(t.sham||p&&p.sham)&&i(l,"sham",!0),c(r,s,l,t)}}},I8vh:function(t,n,r){var o=r("ppGB"),e=Math.max,i=Math.min;t.exports=function(t,n){var r=o(t);return r<0?e(r+n,0):i(r,n)}},JBy8:function(t,n,r){var o=r("yoRg"),e=r("eDl+").concat("length","prototype");n.f=Object.getOwnPropertyNames||function(t){return o(t,e)}},Qo9l:function(t,n,r){t.exports=r("2oRo")},R5XZ:function(t,n,r){var o=r("I+eb"),e=r("2oRo"),i=r("s5pE"),c=[].slice,u=function(t){return function(n,r){var o=arguments.length>2,e=o?c.call(arguments,2):void 0;return t(o?function(){("function"==typeof n?n:Function(n)).apply(this,e)}:n,r)}};o({global:!0,bind:!0,forced:/MSIE .\./.test(i)},{setTimeout:u(e.setTimeout),setInterval:u(e.setInterval)})},RK3t:function(t,n,r){var o=r("0Dky"),e=r("xrYK"),i="".split;t.exports=o((function(){return!Object("z").propertyIsEnumerable(0)}))?function(t){return"String"==e(t)?i.call(t,""):Object(t)}:Object},TWQb:function(t,n,r){var o=r("/GqU"),e=r("UMSQ"),i=r("I8vh"),c=function(t){return function(n,r,c){var u,a=o(n),f=e(a.length),s=i(c,f);if(t&&r!=r){for(;f>s;)if((u=a[s++])!=u)return!0}else for(;f>s;s++)if((t||s in a)&&a[s]===r)return t||s||0;return!t&&-1}};t.exports={includes:c(!0),indexOf:c(!1)}},UMSQ:function(t,n,r){var o=r("ppGB"),e=Math.min;t.exports=function(t){return t>0?e(o(t),9007199254740991):0}},UTVS:function(t,n){var r={}.hasOwnProperty;t.exports=function(t,n){return r.call(t,n)}},VpIT:function(t,n,r){var o=r("2oRo"),e=r("zk60"),i=r("xDBR"),c=o["__core-js_shared__"]||e("__core-js_shared__",{});(t.exports=function(t,n){return c[t]||(c[t]=void 0!==n?n:{})})("versions",[]).push({version:"3.2.1",mode:i?"pure":"global",copyright:"© 2019 Denis Pushkarev (zloirock.ru)"})},Vu81:function(t,n,r){var o=r("0GbY"),e=r("JBy8"),i=r("dBg+"),c=r("glrk");t.exports=o("Reflect","ownKeys")||function(t){var n=e.f(c(t)),r=i.f;return r?n.concat(r(t)):n}},"X2U+":function(t,n,r){var o=r("g6v/"),e=r("m/L8"),i=r("XGwC");t.exports=o?function(t,n,r){return e.f(t,n,i(1,r))}:function(t,n,r){return t[n]=r,t}},XGwC:function(t,n){t.exports=function(t,n){return{enumerable:!(1&t),configurable:!(2&t),writable:!(4&t),value:n}}},afO8:function(t,n,r){var o,e,i,c=r("f5p1"),u=r("2oRo"),a=r("hh1v"),f=r("X2U+"),s=r("UTVS"),p=r("93I0"),l=r("0BK2"),v=u.WeakMap;if(c){var h=new v,g=h.get,y=h.has,b=h.set;o=function(t,n){return b.call(h,t,n),n},e=function(t){return g.call(h,t)||{}},i=function(t){return y.call(h,t)}}else{var d=p("state");l[d]=!0,o=function(t,n){return f(t,d,n),n},e=function(t){return s(t,d)?t[d]:{}},i=function(t){return s(t,d)}}t.exports={set:o,get:e,has:i,enforce:function(t){return i(t)?e(t):o(t,{})},getterFor:function(t){return function(n){var r;if(!a(n)||(r=e(n)).type!==t)throw TypeError("Incompatible receiver, "+t+" required");return r}}}},busE:function(t,n,r){var o=r("2oRo"),e=r("VpIT"),i=r("X2U+"),c=r("UTVS"),u=r("zk60"),a=r("noGo"),f=r("afO8"),s=f.get,p=f.enforce,l=String(a).split("toString");e("inspectSource",(function(t){return a.call(t)})),(t.exports=function(t,n,r,e){var a=!!e&&!!e.unsafe,f=!!e&&!!e.enumerable,s=!!e&&!!e.noTargetGet;"function"==typeof r&&("string"!=typeof n||c(r,"name")||i(r,"name",n),p(r).source=l.join("string"==typeof n?n:"")),t!==o?(a?!s&&t[n]&&(f=!0):delete t[n],f?t[n]=r:i(t,n,r)):f?t[n]=r:u(n,r)})(Function.prototype,"toString",(function(){return"function"==typeof this&&s(this).source||a.call(this)}))},"dBg+":function(t,n){n.f=Object.getOwnPropertySymbols},"eDl+":function(t,n){t.exports=["constructor","hasOwnProperty","isPrototypeOf","propertyIsEnumerable","toLocaleString","toString","valueOf"]},f5p1:function(t,n,r){var o=r("2oRo"),e=r("noGo"),i=o.WeakMap;t.exports="function"==typeof i&&/native code/.test(e.call(i))},"g6v/":function(t,n,r){var o=r("0Dky");t.exports=!o((function(){return 7!=Object.defineProperty({},"a",{get:function(){return 7}}).a}))},glrk:function(t,n,r){var o=r("hh1v");t.exports=function(t){if(!o(t))throw TypeError(String(t)+" is not an object");return t}},hh1v:function(t,n){t.exports=function(t){return"object"==typeof t?null!==t:"function"==typeof t}},kOOl:function(t,n){var r=0,o=Math.random();t.exports=function(t){return"Symbol("+String(void 0===t?"":t)+")_"+(++r+o).toString(36)}},lMq5:function(t,n,r){var o=r("0Dky"),e=/#|\.prototype\./,i=function(t,n){var r=u[c(t)];return r==f||r!=a&&("function"==typeof n?o(n):!!n)},c=i.normalize=function(t){return String(t).replace(e,".").toLowerCase()},u=i.data={},a=i.NATIVE="N",f=i.POLYFILL="P";t.exports=i},"m/L8":function(t,n,r){var o=r("g6v/"),e=r("DPsx"),i=r("glrk"),c=r("wE6v"),u=Object.defineProperty;n.f=o?u:function(t,n,r){if(i(t),n=c(n,!0),i(r),e)try{return u(t,n,r)}catch(t){}if("get"in r||"set"in r)throw TypeError("Accessors not supported");return"value"in r&&(t[n]=r.value),t}},ng4s:function(t,n,r){"use strict";r.r(n),function(t){r("R5XZ");var n=r("pl9+");r("sZ/o"),t(document).ready((function(){n.a.load((function(){})),t(document).on("click","#tabLog3a-tab",(function(){t("#mygraph").hasClass("invisible")&&(setTimeout((function(){loadgraphs()}),1e3),t("#mygraph").removeClass("invisible"))}))}))}.call(this,r("EVdn"))},noGo:function(t,n,r){var o=r("VpIT");t.exports=o("native-function-to-string",Function.toString)},"pl9+":function(t,n,r){"use strict";r.d(n,"a",(function(){return u}));const o=Symbol("loadScript"),e=Symbol("instance");let i;class c{get[e](){return i}set[e](t){i=t}constructor(){if(this[e])return this[e];this[e]=this}reset(){i=null}[o](){return this.scriptPromise||(this.scriptPromise=new Promise(t=>{const n=document.getElementsByTagName("body")[0],r=document.createElement("script");r.type="text/javascript",r.onload=function(){u.api=window.google,u.api.charts.load("current",{packages:["corechart","table"]}),u.api.charts.setOnLoadCallback(()=>{t()})},r.src="https://www.gstatic.com/charts/loader.js",n.appendChild(r)})),this.scriptPromise}load(t,n){return this[o]().then(()=>{if(n){let r={};r=n instanceof Object?n:Array.isArray(n)?{packages:n}:{packages:[n]},this.api.charts.load("current",r),this.api.charts.setOnLoadCallback(t)}else{if("function"!=typeof t)throw"callback must be a function";t()}})}}const u=new c},ppGB:function(t,n){var r=Math.ceil,o=Math.floor;t.exports=function(t){return isNaN(t=+t)?0:(t>0?o:r)(t)}},s5pE:function(t,n,r){var o=r("0GbY");t.exports=o("navigator","userAgent")||""},"sZ/o":function(t,n,r){},wE6v:function(t,n,r){var o=r("hh1v");t.exports=function(t,n){if(!o(t))return t;var r,e;if(n&&"function"==typeof(r=t.toString)&&!o(e=r.call(t)))return e;if("function"==typeof(r=t.valueOf)&&!o(e=r.call(t)))return e;if(!n&&"function"==typeof(r=t.toString)&&!o(e=r.call(t)))return e;throw TypeError("Can't convert object to primitive value")}},xDBR:function(t,n){t.exports=!1},xrYK:function(t,n){var r={}.toString;t.exports=function(t){return r.call(t).slice(8,-1)}},yoRg:function(t,n,r){var o=r("UTVS"),e=r("/GqU"),i=r("TWQb").indexOf,c=r("0BK2");t.exports=function(t,n){var r,u=e(t),a=0,f=[];for(r in u)!o(c,r)&&o(u,r)&&f.push(r);for(;n.length>a;)o(u,r=n[a++])&&(~i(f,r)||f.push(r));return f}},zBJ4:function(t,n,r){var o=r("2oRo"),e=r("hh1v"),i=o.document,c=e(i)&&e(i.createElement);t.exports=function(t){return c?i.createElement(t):{}}},zk60:function(t,n,r){var o=r("2oRo"),e=r("X2U+");t.exports=function(t,n){try{e(o,t,n)}catch(r){o[t]=n}return n}}},[["ng4s","runtime",0]]]);