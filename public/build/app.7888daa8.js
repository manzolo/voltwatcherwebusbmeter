(self.webpackChunk=self.webpackChunk||[]).push([[143],{15177:(t,e,r)=>{"use strict";r(32564);var s=r(35073),a=r(19755);r(80312),a(document).ready((function(){s.E.load((function(){})),a(document).on("click","#tabLog3a-tab",(function(){a("#mygraph").hasClass("invisible")&&(setTimeout((function(){loadgraphs()}),1e3),a("#mygraph").removeClass("invisible"))}))}))},50206:(t,e,r)=>{var s=r(1702);t.exports=s([].slice)},22104:(t,e,r)=>{var s=r(34374),a=Function.prototype,n=a.apply,o=a.call;t.exports="object"==typeof Reflect&&Reflect.apply||(s?o.bind(n):function(){return o.apply(n,arguments)})},48053:(t,e,r)=>{var s=r(17854).TypeError;t.exports=function(t,e){if(t<e)throw s("Not enough arguments");return t}},32564:(t,e,r)=>{var s=r(82109),a=r(17854),n=r(22104),o=r(60614),i=r(88113),c=r(50206),l=r(48053),u=/MSIE .\./.test(i),p=a.Function,h=function(t){return function(e,r){var s=l(arguments.length,1)>2,a=o(e)?e:p(e),i=s?c(arguments,2):void 0;return t(s?function(){n(a,this,i)}:a,r)}};s({global:!0,bind:!0,forced:u},{setTimeout:h(a.setTimeout),setInterval:h(a.setInterval)})},35073:(t,e,r)=>{"use strict";r.d(e,{E:()=>i});const s=Symbol("loadScript"),a=Symbol("instance");let n;class o{get[a](){return n}set[a](t){n=t}constructor(){if(this[a])return this[a];this[a]=this}reset(){n=null}[s](){return this.scriptPromise||(this.scriptPromise=new Promise((t=>{const e=document.getElementsByTagName("body")[0],r=document.createElement("script");r.type="text/javascript",r.onload=function(){i.api=window.google,i.api.charts.load("current",{packages:["corechart","table"]}),i.api.charts.setOnLoadCallback((()=>{t()}))},r.src="https://www.gstatic.com/charts/loader.js",e.appendChild(r)}))),this.scriptPromise}load(t,e){return this[s]().then((()=>{if(e){let r={};r=e instanceof Object?e:Array.isArray(e)?{packages:e}:{packages:[e]},this.api.charts.load("current",r),this.api.charts.setOnLoadCallback(t)}else{if("function"!=typeof t)throw"callback must be a function";t()}}))}}const i=new o},80312:(t,e,r)=>{"use strict";r.r(e)}},t=>{t.O(0,[755,109],(()=>{return e=15177,t(t.s=e);var e}));t.O()}]);