(self.webpackChunk=self.webpackChunk||[]).push([[442],{5048:(e,n,t)=>{var r=t(9755);function o(){var e=r("#menuhide").data("items");r("#navbar-menu").html("");var n=r(window).width()>=980;r(e).each((function(t){if(e[t].hasOwnProperty("sottolivello")&&e[t].sottolivello.length>0){var o=0,i=1;if(submenuitems=e[t].sottolivello,n){r(submenuitems).each((function(e){(o+=1)>=6&&(o=0,i+=1),submenuitems.hasOwnProperty("sottolivello")&&submenuitems.sottolivello.length>0&&(o+=1,subsubmenuitems=submenuitems.sottolivello,r(subsubmenuitems).each((function(e){(o+=1)>=6&&(o=0,i+=1)})))}));var s=3*i}var a=submenuitems.hasOwnProperty("classe")?"submenuitems.classe":"",u=r('<li class="'+(i>0&&n?"nav-item dropdown megamenu moved-megamenu "+a:"nav-item dropdown")+'"></li>').appendTo(r("#navbar-menu"));r('<a class="nav-link dropdown-toggle bicore-menu-item-level-1" href="'+e[t].percorso.percorso+'" data-toggle="dropdown" aria-expanded="false"><span>'+e[t].nome+"</span></a>").appendTo(u);var c,l,p,d=r('<div class="mainmenu-dropdown-menu dropdown-menu col-'+parseInt(s)+'"></div>').appendTo(u),f=r('<div class="row"></div>').appendTo(d),m=0;r(submenuitems).each((function(e){if(m>5&&n&&(m=0),0===m&&(p=r('<div class="col"></div>').appendTo(f),l=r('<div class="link-list-wrapper"></div>').appendTo(p),c=r('<ul class="link-list"></ul>').appendTo(l)),m++,submenuitems[e].hasOwnProperty("sottolivello")&&submenuitems[e].sottolivello.length>0){r('<li class="no_toc text-uppercase"><strong>'+submenuitems[e].nome+"</strong></li>").appendTo(c),r('<li><div class="dropdown-divider"></div></li>').appendTo(c),m+=1;var t=submenuitems[e].sottolivello;r(t).each((function(e){m+=1,r('<li role="menuitem"><a class="list-item bicore-menu-item-level-2" href="'+t[e].percorso+'" target="'+t[e].target+'"><span>'+t[e].nome+"</span></a></li>").appendTo(c)})),r('<li><div class="dropdown-divider"></div></li>').appendTo(c)}else r('<li><a class="list-item bicore-menu-item-level-2" href="'+submenuitems[e].percorso+'" target="'+submenuitems[e].target+'"><span>'+submenuitems[e].nome+"</span></a></li>").appendTo(c)}))}else{var v=e[t].hasOwnProperty("classe")?"nav-link "+e[t].classe:"";r('<li class="'+(v.length>0?"nav-item "+v:"nav-item")+'"><a href="'+e[t].percorso.percorso+'" class="nav-link bicore-menu-item-level-1"><span>'+e[t].nome+"</span></a></li>").appendTo(r("#navbar-menu"))}}))}t(1058),t(9826),t(1539),t(6793),r(window).resize((function(){o()})),document.addEventListener("DOMContentLoaded",(function(){o()})),r(document).on("shown.bs.dropdown",".moved-megamenu",(function(e){var n=r(this).find(".mainmenu-dropdown-menu"),t=r(this).offset(),o=n.offset(),i=t.left-parseInt(n.width()/2);for(i<10&&(i=10);n.width()+i>r(window).width();)i--;n.offset({top:o.top,left:i})}))},1223:(e,n,t)=>{var r=t(5112),o=t(30),i=t(3070),s=r("unscopables"),a=Array.prototype;null==a[s]&&i.f(a,s,{configurable:!0,value:o(null)}),e.exports=function(e){a[s][e]=!0}},2092:(e,n,t)=>{var r=t(9974),o=t(1702),i=t(8361),s=t(7908),a=t(6244),u=t(5417),c=o([].push),l=function(e){var n=1==e,t=2==e,o=3==e,l=4==e,p=6==e,d=7==e,f=5==e||p;return function(m,v,b,h){for(var w,g,y=s(m),x=i(y),O=r(v,b),T=a(x),j=0,A=h||u,k=n?A(m,T):t||d?A(m,0):void 0;T>j;j++)if((f||j in x)&&(g=O(w=x[j],j,y),e))if(n)k[j]=g;else if(g)switch(e){case 3:return!0;case 5:return w;case 6:return j;case 2:c(k,w)}else switch(e){case 4:return!1;case 7:c(k,w)}return p?-1:o||l?l:k}};e.exports={forEach:l(0),map:l(1),filter:l(2),some:l(3),every:l(4),find:l(5),findIndex:l(6),filterReject:l(7)}},7475:(e,n,t)=>{var r=t(7854),o=t(3157),i=t(4411),s=t(111),a=t(5112)("species"),u=r.Array;e.exports=function(e){var n;return o(e)&&(n=e.constructor,(i(n)&&(n===u||o(n.prototype))||s(n)&&null===(n=n[a]))&&(n=void 0)),void 0===n?u:n}},5417:(e,n,t)=>{var r=t(7475);e.exports=function(e,n){return new(r(e))(0===n?0:n)}},648:(e,n,t)=>{var r=t(7854),o=t(1694),i=t(614),s=t(4326),a=t(5112)("toStringTag"),u=r.Object,c="Arguments"==s(function(){return arguments}());e.exports=o?s:function(e){var n,t,r;return void 0===e?"Undefined":null===e?"Null":"string"==typeof(t=function(e,n){try{return e[n]}catch(e){}}(n=u(e),a))?t:c?s(n):"Object"==(r=s(n))&&i(n.callee)?"Arguments":r}},9974:(e,n,t)=>{var r=t(1702),o=t(9662),i=t(4374),s=r(r.bind);e.exports=function(e,n){return o(e),void 0===n?e:i?s(e,n):function(){return e.apply(n,arguments)}}},490:(e,n,t)=>{var r=t(5005);e.exports=r("document","documentElement")},3157:(e,n,t)=>{var r=t(4326);e.exports=Array.isArray||function(e){return"Array"==r(e)}},4411:(e,n,t)=>{var r=t(1702),o=t(7293),i=t(614),s=t(648),a=t(5005),u=t(2788),c=function(){},l=[],p=a("Reflect","construct"),d=/^\s*(?:class|function)\b/,f=r(d.exec),m=!d.exec(c),v=function(e){if(!i(e))return!1;try{return p(c,l,e),!0}catch(e){return!1}},b=function(e){if(!i(e))return!1;switch(s(e)){case"AsyncFunction":case"GeneratorFunction":case"AsyncGeneratorFunction":return!1}try{return m||!!f(d,u(e))}catch(e){return!0}};b.sham=!0,e.exports=!p||o((function(){var e;return v(v.call)||!v(Object)||!v((function(){e=!0}))||e}))?b:v},3009:(e,n,t)=>{var r=t(7854),o=t(7293),i=t(1702),s=t(1340),a=t(3111).trim,u=t(1361),c=r.parseInt,l=r.Symbol,p=l&&l.iterator,d=/^[+-]?0x/i,f=i(d.exec),m=8!==c(u+"08")||22!==c(u+"0x16")||p&&!o((function(){c(Object(p))}));e.exports=m?function(e,n){var t=a(s(e));return c(t,n>>>0||(f(d,t)?16:10))}:c},30:(e,n,t)=>{var r,o=t(9670),i=t(6048),s=t(748),a=t(3501),u=t(490),c=t(317),l=t(6200),p=l("IE_PROTO"),d=function(){},f=function(e){return"<script>"+e+"</"+"script>"},m=function(e){e.write(f("")),e.close();var n=e.parentWindow.Object;return e=null,n},v=function(){try{r=new ActiveXObject("htmlfile")}catch(e){}var e,n;v="undefined"!=typeof document?document.domain&&r?m(r):((n=c("iframe")).style.display="none",u.appendChild(n),n.src=String("javascript:"),(e=n.contentWindow.document).open(),e.write(f("document.F=Object")),e.close(),e.F):m(r);for(var t=s.length;t--;)delete v.prototype[s[t]];return v()};a[p]=!0,e.exports=Object.create||function(e,n){var t;return null!==e?(d.prototype=o(e),t=new d,d.prototype=null,t[p]=e):t=v(),void 0===n?t:i.f(t,n)}},6048:(e,n,t)=>{var r=t(9781),o=t(3353),i=t(3070),s=t(9670),a=t(5656),u=t(1956);n.f=r&&!o?Object.defineProperties:function(e,n){s(e);for(var t,r=a(n),o=u(n),c=o.length,l=0;c>l;)i.f(e,t=o[l++],r[t]);return e}},1956:(e,n,t)=>{var r=t(6324),o=t(748);e.exports=Object.keys||function(e){return r(e,o)}},288:(e,n,t)=>{"use strict";var r=t(1694),o=t(648);e.exports=r?{}.toString:function(){return"[object "+o(this)+"]"}},3111:(e,n,t)=>{var r=t(1702),o=t(4488),i=t(1340),s=t(1361),a=r("".replace),u="["+s+"]",c=RegExp("^"+u+u+"*"),l=RegExp(u+u+"*$"),p=function(e){return function(n){var t=i(o(n));return 1&e&&(t=a(t,c,"")),2&e&&(t=a(t,l,"")),t}};e.exports={start:p(1),end:p(2),trim:p(3)}},1694:(e,n,t)=>{var r={};r[t(5112)("toStringTag")]="z",e.exports="[object z]"===String(r)},1340:(e,n,t)=>{var r=t(7854),o=t(648),i=r.String;e.exports=function(e){if("Symbol"===o(e))throw TypeError("Cannot convert a Symbol value to a string");return i(e)}},1361:e=>{e.exports="\t\n\v\f\r                　\u2028\u2029\ufeff"},9826:(e,n,t)=>{"use strict";var r=t(2109),o=t(2092).find,i=t(1223),s="find",a=!0;s in[]&&Array(1).find((function(){a=!1})),r({target:"Array",proto:!0,forced:a},{find:function(e){return o(this,e,arguments.length>1?arguments[1]:void 0)}}),i(s)},1539:(e,n,t)=>{var r=t(1694),o=t(1320),i=t(288);r||o(Object.prototype,"toString",i,{unsafe:!0})},1058:(e,n,t)=>{var r=t(2109),o=t(3009);r({global:!0,forced:parseInt!=o},{parseInt:o})},6793:(e,n,t)=>{"use strict";t.r(n)}},e=>{e.O(0,[755,109],(()=>{return n=5048,e(e.s=n);var n}));e.O()}]);