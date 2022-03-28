(window.webpackJsonp=window.webpackJsonp||[]).push([[24],{1100:function(t,e,n){"use strict";n.r(e);var r=n(28),o=n(23),c=(n(84),n(4),n(10),n(31),n(9),n(63),n(255),n(796)),base=n(832),d=n(586),l=n(1023),j=n(1034),m=n(1038),h=n(604),f=n(1030),w=n(1041),v=n(1053),y=n(1047),I=n(1122),x=n(1119),R=n(1120),k=n(1032),_=n(1042);n(813);Object(d.c)(l.a),Object(d.c)(j.a),Object(d.c)(m.a),Object(h.d)(f.a),Object(h.d)(w.a),Object(h.d)(v.a),Object(h.d)(y.a),Object(h.d)(I.a),Object(h.d)(x.a),Object(h.d)(R.a),Object(h.d)(k.a),Object(h.d)(_.a);var D={name:"TableDetail",components:{HotTable:c.a},data:function(){return{settings:{licenseKey:"non-commercial-and-evaluation"},detailsRoot:"detailsRoot",colHeaders:[],form:{}}},created:function(){this.setInstance()},methods:{setInstance:function(){window.details=this},removeRow:function(t){this.$refs.details.hotInstance.alter("remove_row",t)},addLine:function(){var t=this.$refs.details.hotInstance.countRows();this.$refs.details.hotInstance.alter("insert_row",t+1)},updateTableSettings:function(header){this.$refs.details.hotInstance.updateSettings({currentRowClassName:"currentRow",currentColClassName:"currentCol",startRows:2,rowHeaders:!0,manualColumnResize:!0,rowHeights:28,filters:!0,autoColumnSize:!0,viewportRowRenderingOffset:1e3,viewportColumnRenderingOffset:100,colWidths:80,persistentState:!0,width:"100%",stretchH:"all",hiddenColumns:{copyPasteEnabled:!1,indicator:!1,columns:[1,2,3]},colHeaders:["","Id","Item ID","Item Code","Item Name","Description","Qty","Units","Curency","Unit Price","Discount","Tax","Amount",""],columns:[{width:"24px",wordWrap:!1,renderer:function(t,td,e,col,n,r,o){var button=null,c=window.details;return(button=document.createElement("button")).type="button",button.innerText=">",button.className="btnNPB",button.value="Details",base.a.dom.addEvent(button,"mousedown",(function(t){t.preventDefault(),c.$refs.dialogItem.openDialog(e)})),base.a.dom.empty(td),td.appendChild(button),td}},{data:"id",width:100,wordWrap:!1},{data:"item_id",width:"50px",wordWrap:!1},{data:"sku",width:"100px",readOnly:!0,wordWrap:!1},{data:"name",width:"150px",readOnly:!0,wordWrap:!1},{data:"description",width:"230px",wordWrap:!1},{data:"quantity",width:"100px",wordWrap:!1,type:"numeric",numericFormat:{pattern:"0,0"}},{data:"unit",width:"100px",readOnly:!0,wordWrap:!1},{data:"default_currency_symbol",width:"50px",readOnly:!0,wordWrap:!1,align:"right"},{data:"price",width:"100px",wordWrap:!1,type:"numeric",numericFormat:{pattern:"0,0.00"}},{data:"discount_rate",width:"100px",wordWrap:!1,type:"numeric",numericFormat:{pattern:"0,0.00"}},{data:"tax_name",width:"100px",type:"dropdown",height:26,wordWrap:!1,source:function(t,e){e(window.details.$auth.$storage.getLocalStorage("tax"))},strict:!0,filter:!1,allowInvalid:!1},{data:"total",width:"100px",wordWrap:!1,type:"numeric",readOnly:!0,numericFormat:{pattern:"0,0.00"}},{width:"24px",wordWrap:!1,renderer:function(t,td,e,col,n,r,o){var button=null,c=window.details;return(button=document.createElement("button")).type="button",button.innerText="X",button.className="btnDelete",button.value="Details",base.a.dom.addEvent(button,"mousedown",(function(t){t.preventDefault(),c.removeRow(e)})),base.a.dom.empty(td),td.appendChild(button),td}}],contextMenu:{callback:function(t,e){var n=window.details;"remove_row"===t&&(n.isInvDetailChanges=!0,n.isInvChanges=!0)}},afterRemoveRow:function(t,e,n,source){window.details.calculateTotal()},beforeRemoveRow:function(t,e,n,source){var r=window.details,o=[];return n.forEach((function(t,e){var n=r.$refs.details.hotInstance.getDataAtCell(t,0);n&&o.push(n)})),1===r.$refs.details.hotInstance.countRows()&&r.$emit("calcTotal",{subTotal:0,amount:0,discountAmount:0,taxDetail:[]}),o.length>0&&r.$emit("removeData",{id:o}),!0},afterChange:function(t,source){var e=window.details;if(t)try{var n=0;t.forEach((function(t){var r=Object(o.a)(t,4),c=(r[0],r[1]),d=r[2],l=r[3];"quantity"!==(n=c)&&"price"!==n&&"discount_rate"!==n&&"tax_name"!==n||d!==l&&e.calculateTotal()}))}catch(t){console.log(t)}}})},selectItems:function(data){var t=data.row,e=data.selected,n=this.$route.query.type,r=this;e.forEach((function(e,o){var c="S"===n.substr(0,1)?e.sale_price:e.purchase_price,d="S"===n.substr(0,1)?e.sell_tax_name:e.buy_tax_name;r.$refs.details.hotInstance.setDataAtRowProp([[t,"name",e.name],[t,"sku",e.code],[t,"unit",e.unit],[t,"description",e.description],[t,"default_currency_symbol",r.form.default_currency_symbol],[t,"item_id",e.id],[t,"price",c],[t,"tax_name",d],[t,"quantity",1]]),t++}))},setDataToDetails:function(data,form){this.updateTableSettings(),this.form=form;var t=void 0!==form.items?form.items:data;this.$refs.details.hotInstance.loadData(t);for(var e=this.$refs.details.hotInstance.countRows(),i=0;i<e;i++)this.$refs.details.hotInstance.setDataAtRowProp(i,"default_currency_symbol",this.form.default_currency_symbol)},calculateTotal:function(){var t=this,e=this.$refs.details.hotInstance.countRows(),n=0,r=0,o=[],c=0,d=0;if(e>0)for(var l=function(i){var e=t.$refs.details.hotInstance.getDataAtRowProp(i,"quantity"),l=t.$refs.details.hotInstance.getDataAtRowProp(i,"price"),j=t.$refs.details.hotInstance.getDataAtRowProp(i,"discount_rate"),m=t.$refs.details.hotInstance.getDataAtRowProp(i,"tax_name"),h=e*l;n+=e*l;var f=parseFloat(j/100*h).toFixed(2);if(r=parseFloat(r)+parseFloat(f),d=h-f,m){var w=0;t.$auth.$storage.getLocalStorage("tax_row").forEach((function(t,e){t.name===m&&(w=parseFloat(t.rate))}));var v=parseFloat(w)/100*d;o.push({name:m,amount:v})}c+=h-f,t.$refs.details.hotInstance.setDataAtRowProp(i,"total",d)},i=0;i<e;i++)l(i);this.$emit("calcTotal",{subTotal:n,amount:c,discountAmount:r,taxDetail:o})},getTaxRate:function(t){var e=this;return Object(r.a)(regeneratorRuntime.mark((function n(){var r;return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,e.$axios.get("/api/financial/taxes/0",{params:{name:t}});case 2:return r=n.sent,n.abrupt("return",r.data.data.rows);case 4:case"end":return n.stop()}}),n)})))()},getDataAtRowPro:function(t,e){return this.$refs.details.hotInstance.getDataAtRowProp(t,e)},countRows:function(){return this.$refs.details.hotInstance.countRows()},checkIfEmptyRow:function(t){return this.$refs.details.hotInstance.isEmptyRow(t)},getAddData:function(){return this.$refs.details.hotInstance.getSourceData()}}},$=n(64),component=Object($.a)(D,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("hot-table",{ref:"details",attrs:{root:t.detailsRoot,settings:t.settings}}),t._v(" "),n("LazyInventoryDialogItem",{ref:"dialogItem",attrs:{"view-data":!0,"show-add-btn":!1},on:{selectItems:t.selectItems}})],1)}),[],!1,null,null,null);e.default=component.exports},797:function(t,e,n){var map={"./af":608,"./af.js":608,"./ar":609,"./ar-dz":610,"./ar-dz.js":610,"./ar-kw":611,"./ar-kw.js":611,"./ar-ly":612,"./ar-ly.js":612,"./ar-ma":613,"./ar-ma.js":613,"./ar-sa":614,"./ar-sa.js":614,"./ar-tn":615,"./ar-tn.js":615,"./ar.js":609,"./az":616,"./az.js":616,"./be":617,"./be.js":617,"./bg":618,"./bg.js":618,"./bm":619,"./bm.js":619,"./bn":620,"./bn.js":620,"./bo":621,"./bo.js":621,"./br":622,"./br.js":622,"./bs":623,"./bs.js":623,"./ca":624,"./ca.js":624,"./cs":625,"./cs.js":625,"./cv":626,"./cv.js":626,"./cy":627,"./cy.js":627,"./da":628,"./da.js":628,"./de":629,"./de-at":630,"./de-at.js":630,"./de-ch":631,"./de-ch.js":631,"./de.js":629,"./dv":632,"./dv.js":632,"./el":633,"./el.js":633,"./en-SG":634,"./en-SG.js":634,"./en-au":635,"./en-au.js":635,"./en-ca":636,"./en-ca.js":636,"./en-gb":637,"./en-gb.js":637,"./en-ie":638,"./en-ie.js":638,"./en-il":639,"./en-il.js":639,"./en-nz":640,"./en-nz.js":640,"./eo":641,"./eo.js":641,"./es":642,"./es-do":643,"./es-do.js":643,"./es-us":644,"./es-us.js":644,"./es.js":642,"./et":645,"./et.js":645,"./eu":646,"./eu.js":646,"./fa":647,"./fa.js":647,"./fi":648,"./fi.js":648,"./fo":649,"./fo.js":649,"./fr":650,"./fr-ca":651,"./fr-ca.js":651,"./fr-ch":652,"./fr-ch.js":652,"./fr.js":650,"./fy":653,"./fy.js":653,"./ga":654,"./ga.js":654,"./gd":655,"./gd.js":655,"./gl":656,"./gl.js":656,"./gom-latn":657,"./gom-latn.js":657,"./gu":658,"./gu.js":658,"./he":659,"./he.js":659,"./hi":660,"./hi.js":660,"./hr":661,"./hr.js":661,"./hu":662,"./hu.js":662,"./hy-am":663,"./hy-am.js":663,"./id":664,"./id.js":664,"./is":665,"./is.js":665,"./it":666,"./it-ch":667,"./it-ch.js":667,"./it.js":666,"./ja":668,"./ja.js":668,"./jv":669,"./jv.js":669,"./ka":670,"./ka.js":670,"./kk":671,"./kk.js":671,"./km":672,"./km.js":672,"./kn":673,"./kn.js":673,"./ko":674,"./ko.js":674,"./ku":675,"./ku.js":675,"./ky":676,"./ky.js":676,"./lb":677,"./lb.js":677,"./lo":678,"./lo.js":678,"./lt":679,"./lt.js":679,"./lv":680,"./lv.js":680,"./me":681,"./me.js":681,"./mi":682,"./mi.js":682,"./mk":683,"./mk.js":683,"./ml":684,"./ml.js":684,"./mn":685,"./mn.js":685,"./mr":686,"./mr.js":686,"./ms":687,"./ms-my":688,"./ms-my.js":688,"./ms.js":687,"./mt":689,"./mt.js":689,"./my":690,"./my.js":690,"./nb":691,"./nb.js":691,"./ne":692,"./ne.js":692,"./nl":693,"./nl-be":694,"./nl-be.js":694,"./nl.js":693,"./nn":695,"./nn.js":695,"./pa-in":696,"./pa-in.js":696,"./pl":697,"./pl.js":697,"./pt":698,"./pt-br":699,"./pt-br.js":699,"./pt.js":698,"./ro":700,"./ro.js":700,"./ru":701,"./ru.js":701,"./sd":702,"./sd.js":702,"./se":703,"./se.js":703,"./si":704,"./si.js":704,"./sk":705,"./sk.js":705,"./sl":706,"./sl.js":706,"./sq":707,"./sq.js":707,"./sr":708,"./sr-cyrl":709,"./sr-cyrl.js":709,"./sr.js":708,"./ss":710,"./ss.js":710,"./sv":711,"./sv.js":711,"./sw":712,"./sw.js":712,"./ta":713,"./ta.js":713,"./te":714,"./te.js":714,"./tet":715,"./tet.js":715,"./tg":716,"./tg.js":716,"./th":717,"./th.js":717,"./tl-ph":718,"./tl-ph.js":718,"./tlh":719,"./tlh.js":719,"./tr":720,"./tr.js":720,"./tzl":721,"./tzl.js":721,"./tzm":722,"./tzm-latn":723,"./tzm-latn.js":723,"./tzm.js":722,"./ug-cn":724,"./ug-cn.js":724,"./uk":725,"./uk.js":725,"./ur":726,"./ur.js":726,"./uz":727,"./uz-latn":728,"./uz-latn.js":728,"./uz.js":727,"./vi":729,"./vi.js":729,"./x-pseudo":730,"./x-pseudo.js":730,"./yo":731,"./yo.js":731,"./zh-cn":732,"./zh-cn.js":732,"./zh-hk":733,"./zh-hk.js":733,"./zh-tw":734,"./zh-tw.js":734};function r(t){var e=o(t);return n(e)}function o(t){if(!n.o(map,t)){var e=new Error("Cannot find module '"+t+"'");throw e.code="MODULE_NOT_FOUND",e}return map[t]}r.keys=function(){return Object.keys(map)},r.resolve=o,t.exports=r,r.id=797}}]);