(self.webpackChunk=self.webpackChunk||[]).push([[884],{8869:(e,t,a)=>{"use strict";a(9826),a(3210),a(2772),a(1058),a(4678),a(1249),a(8304),a(489),a(2419),a(8011),a(4819),a(5003),a(9070),a(2526),a(1817),a(1539),a(2165),a(8783),a(6992),a(3948);var n=a(7036),o=a(9755);const i=function(){console.log("Estrazione dati sottotabella tramite class Tabella estesa");var e,t=Routing.generate("Ordine_indexdettaglio"),a={prefiltri:[{nomecampo:"Ordine.Cliente.id",operatore:"=",valore:e=o("#tabellaOrdineCliente").data("clienteid")}],titolotabella:"Ordini del cliente "+o("#tabellaOrdineCliente").data("clientenominativo"),modellocolonne:[{nomecampo:"Ordine.Cliente",escluso:!0}],colonneordinamento:{"Ordine.data":"DESC","Ordine.quantita":"DESC"},parametriform:{cliente_id:e},multiselezione:!0};o.ajax({type:"POST",url:t,data:{parametripassati:JSON.stringify(a)}}).done((function(e){o("#tabellaOrdineCliente").html(e).promise().done((function(){new n.Z("Ordine").caricatabella()}))})),t=Routing.generate("Magazzino_indexdettaglio"),a={prefiltri:[{nomecampo:"Magazzino.Ordine.Cliente.id",operatore:"=",valore:e=o("#tabellaOrdineCliente").data("clienteid")}],modellocolonne:[{nomecampo:"Magazzino.giornodellasettimana",escluso:!1,decodifiche:["Domenica","Lunedì","Martedì","Mercoledì","Giovedì","Venerdì","Sabato"]}],titolotabella:"Roba in magazzino del cliente "+o("#tabellaOrdineCliente").data("clientenominativo")},o.ajax({type:"POST",url:t,data:{parametripassati:JSON.stringify(a)}}).done((function(e){o("#tabellaMagazzinoCliente").html(e).promise().done((function(){new n.Z("Magazzino").caricatabella()}))}))};var r=a(2929),l=a(2642),c=a(2381),s=a(1369),u=a.n(s),d=a(6455),p=a.n(d),f=a(9755);function m(e){return(m="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function b(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function h(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function v(e,t,a){return(v="undefined"!=typeof Reflect&&Reflect.get?Reflect.get:function(e,t,a){var n=function(e,t){for(;!Object.prototype.hasOwnProperty.call(e,t)&&null!==(e=O(e)););return e}(e,t);if(n){var o=Object.getOwnPropertyDescriptor(n,t);return o.get?o.get.call(a):o.value}})(e,t,a||e)}function g(e,t){return(g=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function y(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var a,n=O(e);if(t){var o=O(this).constructor;a=Reflect.construct(n,arguments,o)}else a=n.apply(this,arguments);return w(this,a)}}function w(e,t){return!t||"object"!==m(t)&&"function"!=typeof t?function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e):t}function O(e){return(O=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}const k=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&g(e,t)}(c,e);var t,a,n,o=y(c);function c(){return b(this,c),o.apply(this,arguments)}return t=c,(a=[{key:"caricatabella",value:function(){console.log("caricatabella TabellaEstesa"),v(O(c.prototype),"caricatabella",this).call(this)}},{key:"aggiungirecord",value:function(e){console.log("addrow TabellaEstesa"),v(O(c.prototype),"aggiungirecord",this).call(this,e)}},{key:"modificarecord",value:function(e,t){console.log("editrow TabellaEstesa"),v(O(c.prototype),"modificarecord",this).call(this,e,i)}},{key:"cancellarecord",value:function(e,t){console.log("deleterow TabellaEstesa"),v(O(c.prototype),"cancellarecord",this).call(this,e,(function(){console.log("Cancellato record "+e)}))}},{key:"aggiornaselezionati",value:function(e){var t=this;if(!0!==JSON.parse(r.Z.getTabellaParameter(this.parametri.permessi)).update)return l.Z.show("Non si dispongono dei diritti per eliminare questo elemento","warning","it-error"),!1;f("#table"+this.nometabella).attr("data-tabletoken");var a=f("#table"+t.nometabella+" > tbody > tr .biselecttablerow").map((function(){if(!0===f(this).prop("checked"))return parseInt(this.dataset.bitableid)})).get();if(a.length>0){var n=Routing.generate("Cliente_preparazioneaggiornamentomultiplo");f.ajax({type:"GET",url:n,context:"body",dataType:"html",success:function(n){var o=document.createElement("html");o.innerHTML=n;var i=f(o).find("#selectmultipladiv");u().dialog({title:"Seleziona il campo",closeButton:!1,message:i,size:"large",buttons:{cancel:{label:"Annulla",className:"btn-danger",callback:function(){console.log("Custom cancel clicked"),e()}},ok:{label:"Conferma",className:"btn-info",callback:function(){var n=Routing.generate("Cliente_aggiornamentomultiplo"),o=f("#selectmultipla").val(),i=f("#selectmultiplainputtext").val();f.ajax({type:"POST",url:n,data:{camposelezionato:o,valoreselezionato:i,idsselezionati:a},context:"body",dataType:"json",success:function(e){e.errcode<0?p().fire("Oops...",e.message,"error"):(t.caricatabella(),p().fire(e.message))}}),e()}}}}),f(".bootstrap-select-wrapper select").selectpicker("refresh")}})}}}])&&h(t.prototype,a),n&&h(t,n),c}(n.Z);var C=a(9755);document.addEventListener("DOMContentLoaded",(function(e){e.preventDefault();var t=k.getMainTabella();new k(t).caricatabella()})),C(document).on("change","#selectmultipla",(function(e){var t=C(this).find(":selected").data("datatype");switch(C("#selectmultiplainputtext").remove(),t){case"integer":C("<input/>").attr({type:"text",id:"selectmultiplainputtext",name:"selectmultiplainputtext"}).appendTo("#selectmultiplainputdiv");break;case"boolean":C("<input/>").attr({type:"checkbox",class:"form-check",id:"selectmultiplainputtext",name:"selectmultiplainputtext"}).appendTo("#selectmultiplainputdiv");break;case"date":C("<input/>").attr({type:"text",class:"bidatepicker",id:"selectmultiplainputtext",name:"selectmultiplainputtext"}).appendTo("#selectmultiplainputdiv");break;case"datetime":C("<input/>").attr({type:"text",class:"bidatetimepicker",id:"selectmultiplainputtext",name:"selectmultiplainputtext"}).appendTo("#selectmultiplainputdiv")}C(".bidatepicker").datetimepicker({locale:"it",format:"L"}),C(".bidatetimepicker").datetimepicker({locale:"it"})})),C(document).on("click",".filterable .btn-filter",(function(e){var t=C(this).parents(".filterable").find(".filters input.colonnatabellafiltro");!0===t.prop("readonly")?(t.prop("readonly",!1),C.each(t,(function(e,t){C(this).attr("placeholder",C(this).attr("placeholder").trim()),C(this).closest("th").removeClass("sorting sorting_asc sorting_desc")})),t.first().focus()):t.val("").prop("readonly",!0)})),C(document).on("keypress",".filterable .filters input",(function(e){var t=e.keyCode||e.which;if("9"!=t&&"13"==t){var a=this.dataset.nomecontroller,n=new k(a),o=new Array;C(".colonnatabellafiltro").each((function(e){if(""!=C(this).val()){var t=C(this).data("tipocampo"),a=C(this).val();if(void 0!==C(this).data("decodifiche")&&null!==C(this).data("decodifiche")){var i=C(this).data("decodifiche"),r=Array();C.each(i,(function(e,t){-1!==t.toLowerCase().indexOf(a.toLowerCase())&&r.push(e)}));var l={nomecampo:C(this).data("nomecampo"),operatore:"IN",valore:r}}else switch(t){case"string":case"text":var c=encodeURIComponent(a);l={nomecampo:C(this).data("nomecampo"),operatore:"CONTAINS",valore:c};break;case"integer":l={nomecampo:C(this).data("nomecampo"),operatore:"=",valore:parseInt(a)};break;case"decimal":l={nomecampo:C(this).data("nomecampo"),operatore:"=",valore:parseFloat(a)};break;case"boolean":var s=a.toUpperCase(),u=a;switch(s){case"SI":u=!0;break;case"NO":u=!1}l={nomecampo:C(this).data("nomecampo"),operatore:"=",valore:u};break;case"date":var d=n.getDateTimeTabella(a+" 00:00:00");l={nomecampo:C(this).data("nomecampo"),operatore:"=",valore:{date:d}};break;case"datetime":d=n.getDateTimeTabella(a),l={nomecampo:C(this).data("nomecampo"),operatore:"=",valore:{date:d}};break;default:l={nomecampo:C(this).data("nomecampo"),operatore:"=",valore:a}}o.push(l)}})),n.setDataParameterTabella("filtri",JSON.stringify(o)),n.setDataParameterTabella("paginacorrente","1"),n.caricatabella()}})),C(document).on("click","th.sorting .colonnatabellafiltro[readonly], th.sorting_asc .colonnatabellafiltro[readonly], th.sorting_desc .colonnatabellafiltro[readonly]",(function(e){var t=this.dataset.nomecampo,a=this.dataset.nomecontroller,n="ASC",o=new k(a),i=o.getParametriTabellaDataset(),l=JSON.parse(r.Z.getTabellaParameter(i.colonneordinamento));void 0!==l[t]&&(n="ASC"===l[t]?"DESC":"ASC"),o.setDataParameterTabella("colonneordinamento",'{"'+t+'": "'+n+'" }'),o.caricatabella()})),C(document).ready((function(){C(document).on("click",".tabellarefresh",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new k(t).caricatabella()})),C(document).on("click",".tabellamodificamultipla",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new k(t).aggiornaselezionati((function(){console.log("Modifica records tabella estesa")}))})),C(document).on("click",".tabelladel",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new k(t).eliminaselezionati((function(){console.log("Cancellati records tabella estesa")}))})),C(document).on("click",".paginascelta",(function(e){e.preventDefault();var t=this.dataset.nomecontroller,a=new k(t);a.getParametriTabellaDataset().paginacorrente=r.Z.setTabellaParameter(this.dataset.paginascelta),a.caricatabella()})),C(document).on("click",".tabellaadd",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new k(t).aggiungirecord()})),C(document).on("click",".tabelladownload",(function(e){e.preventDefault();var t=this.dataset.nomecontroller;new k(t).download()})),C(document).on("click",".birimuovifiltri",(function(e){var t=this.dataset.nomecontroller,a=new k(t);a.setDataParameterTabella("filtri",JSON.stringify([])),a.caricatabella()})),C(document).on("click",".bibottonieditinline",(function(e){var t=this.closest("tr").dataset.bitableid,a=C(this).closest("tr").closest("table").attr("id"),n=this.closest("tr").closest("table").dataset.nomecontroller,o=this.dataset.azione,i=C("#"+a+" > tbody > tr.inputeditinline[data-bitableid='"+t+"'] :input");if("conferma"===o){var r=Array();i.each((function(e,t){var a,n=t.closest("td").dataset.nomecampo,o=t.closest("td").dataset.tipocampo,i=C(t).attr("disabled");a="boolean"===o?C(t).is(":checked"):C(t).val(),n&&void 0===i&&r.push({fieldname:n,fieldvalue:a,fieldtype:o})}));var l=this.closest("tr").dataset.token,s=Routing.generate(n+"_aggiorna",{id:t,token:l});C.ajax({url:s,type:"POST",data:{values:r},async:!0,error:function(e,t,a){return u().alert({size:"large",closeButton:!1,title:'<div class="alert alert-warning" role="alert">Si è verificato un errore</div>',message:c.Z.showErrori(e.responseText)}),!1},beforeSend:function(e){},success:function(e){var a=new k(n);a.reseteditinline(i),C("#table"+n+" > tbody > tr > td.colonnazionitabella a.bibottonieditinline[data-biid='"+t+"']").addClass("sr-only"),C("#table"+n+" > tbody > tr > td.colonnazionitabella a.bibottonimodificatabella"+n+"[data-biid='"+t+"']").removeClass("sr-only"),a.caricatabella()}})}"annulla"===o&&(new k(n).reseteditinline(i),C("#table"+n+" > tbody > tr > td.colonnazionitabella a.bibottonieditinline[data-biid='"+t+"']").addClass("sr-only"),C("#table"+n+" > tbody > tr > td.colonnazionitabella a.bibottonimodificatabella"+n+"[data-biid='"+t+"']").removeClass("sr-only"))}))}));a(7481),a(7634);a(8878)},7065:(e,t,a)=>{"use strict";var n=a(3099),o=a(111),i=[].slice,r={},l=function(e,t,a){if(!(t in r)){for(var n=[],o=0;o<t;o++)n[o]="a["+o+"]";r[t]=Function("C,a","return new C("+n.join(",")+")")}return r[t](e,a)};e.exports=Function.bind||function(e){var t=n(this),a=i.call(arguments,1),r=function(){var n=a.concat(i.call(arguments));return this instanceof r?l(t,n.length,n):t.apply(e,n)};return o(t.prototype)&&(r.prototype=t.prototype),r}},8011:(e,t,a)=>{a(2109)({target:"Object",stat:!0,sham:!a(9781)},{create:a(30)})},5003:(e,t,a)=>{var n=a(2109),o=a(7293),i=a(5656),r=a(1236).f,l=a(9781),c=o((function(){r(1)}));n({target:"Object",stat:!0,forced:!l||c,sham:!l},{getOwnPropertyDescriptor:function(e,t){return r(i(e),t)}})},489:(e,t,a)=>{var n=a(2109),o=a(7293),i=a(7908),r=a(9518),l=a(8544);n({target:"Object",stat:!0,forced:o((function(){r(1)})),sham:!l},{getPrototypeOf:function(e){return r(i(e))}})},8304:(e,t,a)=>{a(2109)({target:"Object",stat:!0},{setPrototypeOf:a(7674)})},2419:(e,t,a)=>{var n=a(2109),o=a(5005),i=a(3099),r=a(9670),l=a(111),c=a(30),s=a(7065),u=a(7293),d=o("Reflect","construct"),p=u((function(){function e(){}return!(d((function(){}),[],e)instanceof e)})),f=!u((function(){d((function(){}))})),m=p||f;n({target:"Reflect",stat:!0,forced:m,sham:m},{construct:function(e,t){i(e),r(t);var a=arguments.length<3?e:i(arguments[2]);if(f&&!p)return d(e,t,a);if(e==a){switch(t.length){case 0:return new e;case 1:return new e(t[0]);case 2:return new e(t[0],t[1]);case 3:return new e(t[0],t[1],t[2]);case 4:return new e(t[0],t[1],t[2],t[3])}var n=[null];return n.push.apply(n,t),new(s.apply(e,n))}var o=a.prototype,u=c(l(o)?o:Object.prototype),m=Function.apply.call(e,u,t);return l(m)?m:u}})},4819:(e,t,a)=>{var n=a(2109),o=a(111),i=a(9670),r=a(6656),l=a(1236),c=a(9518);n({target:"Reflect",stat:!0},{get:function e(t,a){var n,s,u=arguments.length<3?t:arguments[2];return i(t)===u?t[a]:(n=l.f(t,a))?r(n,"value")?n.value:void 0===n.get?void 0:n.get.call(u):o(s=c(t))?e(s,a,u):void 0}})}},0,[[8869,666,755,818,981,994,550,919]]]);