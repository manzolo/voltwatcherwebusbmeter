(self.webpackChunk=self.webpackChunk||[]).push([[884],{8869:(e,t,a)=>{"use strict";a(9826),a(1539),a(3210),a(2772),a(1058),a(4678),a(1249),a(8304),a(489),a(2419),a(9070),a(8011),a(4819),a(5003),a(2526),a(1817),a(2165),a(6992),a(8783),a(3948);var n=a(7036),o=a(9755);const r=function(){console.log("Estrazione dati sottotabella tramite class Tabella estesa");var e,t=Routing.generate("Ordine_indexdettaglio"),a={prefiltri:[{nomecampo:"Ordine.Cliente.id",operatore:"=",valore:e=o("#tabellaOrdineCliente").data("clienteid")}],titolotabella:"Ordini del cliente "+o("#tabellaOrdineCliente").data("clientenominativo"),modellocolonne:[{nomecampo:"Ordine.Cliente",escluso:!0}],colonneordinamento:{"Ordine.data":"DESC","Ordine.quantita":"DESC"},parametriform:{cliente_id:e},multiselezione:!0};o.ajax({type:"POST",url:t,data:{parametripassati:JSON.stringify(a)}}).done((function(e){o("#tabellaOrdineCliente").html(e).promise().done((function(){new n.Z("Ordine").caricatabella()}))})),t=Routing.generate("Magazzino_indexdettaglio"),a={prefiltri:[{nomecampo:"Magazzino.Ordine.Cliente.id",operatore:"=",valore:e=o("#tabellaOrdineCliente").data("clienteid")}],modellocolonne:[{nomecampo:"Magazzino.giornodellasettimana",escluso:!1,decodifiche:["Domenica","Lunedì","Martedì","Mercoledì","Giovedì","Venerdì","Sabato"]}],titolotabella:"Roba in magazzino del cliente "+o("#tabellaOrdineCliente").data("clientenominativo")},o.ajax({type:"POST",url:t,data:{parametripassati:JSON.stringify(a)}}).done((function(e){o("#tabellaMagazzinoCliente").html(e).promise().done((function(){new n.Z("Magazzino").caricatabella()}))}))};var i=a(2929),l=a(2642),c=a(2381),s=a(1369),u=a.n(s),d=a(6455),p=a.n(d),f=a(9755);function m(e){return m="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},m(e)}function b(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function v(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function h(){return h="undefined"!=typeof Reflect&&Reflect.get?Reflect.get:function(e,t,a){var n=g(e,t);if(n){var o=Object.getOwnPropertyDescriptor(n,t);return o.get?o.get.call(arguments.length<3?e:a):o.value}},h.apply(this,arguments)}function g(e,t){for(;!Object.prototype.hasOwnProperty.call(e,t)&&null!==(e=k(e)););return e}function y(e,t){return y=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},y(e,t)}function w(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var a,n=k(e);if(t){var o=k(this).constructor;a=Reflect.construct(n,arguments,o)}else a=n.apply(this,arguments);return O(this,a)}}function O(e,t){if(t&&("object"===m(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e)}function k(e){return k=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},k(e)}const C=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");Object.defineProperty(e,"prototype",{value:Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),writable:!1}),t&&y(e,t)}(c,e);var t,a,n,o=w(c);function c(){return b(this,c),o.apply(this,arguments)}return t=c,(a=[{key:"caricatabella",value:function(){console.log("caricatabella TabellaEstesa"),h(k(c.prototype),"caricatabella",this).call(this)}},{key:"aggiungirecord",value:function(e){console.log("addrow TabellaEstesa"),h(k(c.prototype),"aggiungirecord",this).call(this,e)}},{key:"modificarecord",value:function(e,t){console.log("editrow TabellaEstesa"),h(k(c.prototype),"modificarecord",this).call(this,e,r)}},{key:"cancellarecord",value:function(e,t){console.log("deleterow TabellaEstesa"),h(k(c.prototype),"cancellarecord",this).call(this,e,(function(){console.log("Cancellato record "+e)}))}},{key:"aggiornaselezionati",value:function(e){var t=this;if(!0!==JSON.parse(i.Z.getTabellaParameter(this.parametri.permessi)).update)return l.Z.show("Non si dispongono dei diritti per eliminare questo elemento","warning","it-error"),!1;f("#table"+this.nometabella).attr("data-tabletoken");var a=f("#table"+t.nometabella+" > tbody > tr .biselecttablerow").map((function(){if(!0===f(this).prop("checked"))return parseInt(this.dataset.bitableid)})).get();if(a.length>0){var n=Routing.generate("Cliente_preparazioneaggiornamentomultiplo");f.ajax({type:"GET",url:n,context:"body",dataType:"html",success:function(n){var o=document.createElement("html");o.innerHTML=n;var r=f(o).find("#selectmultipladiv");u().dialog({title:"Seleziona il campo",closeButton:!1,message:r,size:"large",buttons:{cancel:{label:"Annulla",className:"btn-danger",callback:function(){console.log("Custom cancel clicked"),e()}},ok:{label:"Conferma",className:"btn-info",callback:function(){var n=Routing.generate("Cliente_aggiornamentomultiplo"),o=f("#selectmultipla").val(),r=f("#selectmultiplainputtext").val();f.ajax({type:"POST",url:n,data:{camposelezionato:o,valoreselezionato:r,idsselezionati:a},context:"body",dataType:"json",success:function(e){e.errcode<0?p().fire("Oops...",e.message,"error"):(t.caricatabella(),p().fire(e.message))}}),e()}}}}),f(".bootstrap-select-wrapper select").selectpicker("refresh")}})}}}])&&v(t.prototype,a),n&&v(t,n),Object.defineProperty(t,"prototype",{writable:!1}),c}(n.Z);var T=a(9755);document.addEventListener("DOMContentLoaded",(function(e){e.preventDefault();var t=C.getMainTabella();new C(t).caricatabella()})),T(document).on("change","#selectmultipla",(function(e){var t=T(this).find(":selected").data("datatype");switch(T("#selectmultiplainputtext").remove(),t){case"integer":T("<input/>").attr({type:"text",id:"selectmultiplainputtext",name:"selectmultiplainputtext"}).appendTo("#selectmultiplainputdiv");break;case"boolean":T("<input/>").attr({type:"checkbox",class:"form-check",id:"selectmultiplainputtext",name:"selectmultiplainputtext"}).appendTo("#selectmultiplainputdiv");break;case"date":T("<input/>").attr({type:"text",class:"bidatepicker",id:"selectmultiplainputtext",name:"selectmultiplainputtext"}).appendTo("#selectmultiplainputdiv");break;case"datetime":T("<input/>").attr({type:"text",class:"bidatetimepicker",id:"selectmultiplainputtext",name:"selectmultiplainputtext"}).appendTo("#selectmultiplainputdiv")}T(".bidatepicker").datetimepicker({locale:"it",format:"L"}),T(".bidatetimepicker").datetimepicker({locale:"it"})})),T(document).on("click",".filterable .btn-filter",(function(e){var t=T(this).parents(".filterable").find(".filters input.colonnatabellafiltro");!0===t.prop("readonly")?(t.prop("readonly",!1),T.each(t,(function(e,t){T(this).attr("placeholder",T(this).attr("placeholder").trim()),T(this).closest("th").removeClass("sorting sorting_asc sorting_desc")})),t.first().focus()):t.val("").prop("readonly",!0)})),T(document).on("keypress",".filterable .filters input",(function(e){var t=e.keyCode||e.which;if("9"!=t&&"13"==t){var a=this.dataset.nomecontroller,n=new C(a),o=new Array;T(".colonnatabellafiltro").each((function(e){if(""!=T(this).val()){var t=T(this).data("tipocampo"),a=T(this).val();if(void 0!==T(this).data("decodifiche")&&null!==T(this).data("decodifiche")){var r=T(this).data("decodifiche"),i=Array();T.each(r,(function(e,t){-1!==t.toLowerCase().indexOf(a.toLowerCase())&&i.push(e)}));var l={nomecampo:T(this).data("nomecampo"),operatore:"IN",valore:i}}else switch(t){case"string":case"text":var c=encodeURIComponent(a);l={nomecampo:T(this).data("nomecampo"),operatore:"CONTAINS",valore:c};break;case"integer":l={nomecampo:T(this).data("nomecampo"),operatore:"=",valore:parseInt(a)};break;case"decimal":l={nomecampo:T(this).data("nomecampo"),operatore:"=",valore:parseFloat(a)};break;case"boolean":var s=a.toUpperCase(),u=a;switch(s){case"SI":u=!0;break;case"NO":u=!1}l={nomecampo:T(this).data("nomecampo"),operatore:"=",valore:u};break;case"date":var d=n.getDateTimeTabella(a+" 00:00:00");l={nomecampo:T(this).data("nomecampo"),operatore:"=",valore:{date:d}};break;case"datetime":d=n.getDateTimeTabella(a),l={nomecampo:T(this).data("nomecampo"),operatore:"=",valore:{date:d}};break;default:l={nomecampo:T(this).data("nomecampo"),operatore:"=",valore:a}}o.push(l)}})),n.setDataParameterTabella("filtri",JSON.stringify(o)),n.setDataParameterTabella("paginacorrente","1"),n.caricatabella()}})),T(document).on("click","th.sorting .colonnatabellafiltro[readonly], th.sorting_asc .colonnatabellafiltro[readonly], th.sorting_desc .colonnatabellafiltro[readonly]",(function(e){var t=this.dataset.nomecampo,a=this.dataset.nomecontroller,n="ASC",o=new C(a),r=o.getParametriTabellaDataset(),l=JSON.parse(i.Z.getTabellaParameter(r.colonneordinamento));void 0!==l[t]&&(n="ASC"===l[t]?"DESC":"ASC"),o.setDataParameterTabella("colonneordinamento",'{"'+t+'": "'+n+'" }'),o.caricatabella()})),T(document).ready((function(){T(document).on("click",".tabellarefresh",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new C(t).caricatabella()})),T(document).on("click",".tabellamodificamultipla",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new C(t).aggiornaselezionati((function(){console.log("Modifica records tabella estesa")}))})),T(document).on("click",".tabelladel",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new C(t).eliminaselezionati((function(){console.log("Cancellati records tabella estesa")}))})),T(document).on("click",".paginascelta",(function(e){e.preventDefault();var t=this.dataset.nomecontroller,a=new C(t);a.getParametriTabellaDataset().paginacorrente=i.Z.setTabellaParameter(this.dataset.paginascelta),a.caricatabella()})),T(document).on("click",".tabellaadd",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new C(t).aggiungirecord()})),T(document).on("click",".tabelladownload",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new C(t).download()})),T(document).on("click",".birimuovifiltri",(function(e){var t=this.dataset.nomecontroller,a=new C(t);a.setDataParameterTabella("filtri",JSON.stringify([])),a.caricatabella()})),T(document).on("click",".bibottonieditinline",(function(e){var t=this.closest("tr").dataset.bitableid,a=T(this).closest("tr").closest("table").attr("id"),n=this.closest("tr").closest("table").dataset.nomecontroller,o=this.dataset.azione,r=T("#"+a+" > tbody > tr.inputeditinline[data-bitableid='"+t+"'] :input");if("conferma"===o){var i=Array();r.each((function(e,t){var a,n=t.closest("td").dataset.nomecampo,o=t.closest("td").dataset.tipocampo,r=T(t).attr("disabled");a="boolean"===o?T(t).is(":checked"):T(t).val(),n&&void 0===r&&i.push({fieldname:n,fieldvalue:a,fieldtype:o})}));var l=this.closest("tr").dataset.token,s=Routing.generate(n+"_aggiorna",{id:t,token:l});T.ajax({url:s,type:"POST",data:{values:i},async:!0,error:function(e,t,a){return u().alert({size:"large",closeButton:!1,title:'<div class="alert alert-warning" role="alert">Si è verificato un errore</div>',message:c.Z.showErrori(e.responseText)}),!1},beforeSend:function(e){},success:function(e){var a=new C(n);a.reseteditinline(r),T("#table"+n+" > tbody > tr > td.colonnazionitabella a.bibottonieditinline[data-biid='"+t+"']").addClass("sr-only"),T("#table"+n+" > tbody > tr > td.colonnazionitabella a.bibottonimodificatabella"+n+"[data-biid='"+t+"']").removeClass("sr-only"),a.caricatabella()}})}"annulla"===o&&(new C(n).reseteditinline(r),T("#table"+n+" > tbody > tr > td.colonnazionitabella a.bibottonieditinline[data-biid='"+t+"']").addClass("sr-only"),T("#table"+n+" > tbody > tr > td.colonnazionitabella a.bibottonimodificatabella"+n+"[data-biid='"+t+"']").removeClass("sr-only"))}))}));a(7481),a(7634);a(8878)},7065:(e,t,a)=>{"use strict";var n=a(7854),o=a(1702),r=a(9662),i=a(111),l=a(2597),c=a(206),s=n.Function,u=o([].concat),d=o([].join),p={},f=function(e,t,a){if(!l(p,t)){for(var n=[],o=0;o<t;o++)n[o]="a["+o+"]";p[t]=s("C,a","return new C("+d(n,",")+")")}return p[t](e,a)};e.exports=s.bind||function(e){var t=r(this),a=t.prototype,n=c(arguments,1),o=function(){var a=u(n,c(arguments));return this instanceof o?f(t,a.length,a):t.apply(e,a)};return i(a)&&(o.prototype=a),o}},5032:(e,t,a)=>{var n=a(2597);e.exports=function(e){return void 0!==e&&(n(e,"value")||n(e,"writable"))}},8011:(e,t,a)=>{a(2109)({target:"Object",stat:!0,sham:!a(9781)},{create:a(30)})},5003:(e,t,a)=>{var n=a(2109),o=a(7293),r=a(5656),i=a(1236).f,l=a(9781),c=o((function(){i(1)}));n({target:"Object",stat:!0,forced:!l||c,sham:!l},{getOwnPropertyDescriptor:function(e,t){return i(r(e),t)}})},489:(e,t,a)=>{var n=a(2109),o=a(7293),r=a(7908),i=a(9518),l=a(8544);n({target:"Object",stat:!0,forced:o((function(){i(1)})),sham:!l},{getPrototypeOf:function(e){return i(r(e))}})},8304:(e,t,a)=>{a(2109)({target:"Object",stat:!0},{setPrototypeOf:a(7674)})},2419:(e,t,a)=>{var n=a(2109),o=a(5005),r=a(2104),i=a(7065),l=a(9483),c=a(9670),s=a(111),u=a(30),d=a(7293),p=o("Reflect","construct"),f=Object.prototype,m=[].push,b=d((function(){function e(){}return!(p((function(){}),[],e)instanceof e)})),v=!d((function(){p((function(){}))})),h=b||v;n({target:"Reflect",stat:!0,forced:h,sham:h},{construct:function(e,t){l(e),c(t);var a=arguments.length<3?e:l(arguments[2]);if(v&&!b)return p(e,t,a);if(e==a){switch(t.length){case 0:return new e;case 1:return new e(t[0]);case 2:return new e(t[0],t[1]);case 3:return new e(t[0],t[1],t[2]);case 4:return new e(t[0],t[1],t[2],t[3])}var n=[null];return r(m,n,t),new(r(i,e,n))}var o=a.prototype,d=u(s(o)?o:f),h=r(e,d,t);return s(h)?h:d}})},4819:(e,t,a)=>{var n=a(2109),o=a(6916),r=a(111),i=a(9670),l=a(5032),c=a(1236),s=a(9518);n({target:"Reflect",stat:!0},{get:function e(t,a){var n,u,d=arguments.length<3?t:arguments[2];return i(t)===d?t[a]:(n=c.f(t,a))?l(n)?n.value:void 0===n.get?void 0:o(n.get,d):r(u=s(t))?e(u,a,d):void 0}})}},e=>{e.O(0,[755,109,981,215,803,239,515,919],(()=>{return t=8869,e(e.s=t);var t}));e.O()}]);