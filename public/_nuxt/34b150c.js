(window.webpackJsonp=window.webpackJsonp||[]).push([[82,49,50],{1161:function(t,e,n){"use strict";n.r(e);var r={props:{form:{type:Object,default:function(){return{}}},logo:{type:String,default:""}},data:function(){return{companyNameView:!0,itemPaymentTerm:[],itemDateFormate:["Y-m-d","m/d/Y"]}},methods:{save:function(){this.$refs.sectionEdit.save(this.form),this.companyNameView=!0},cancel:function(){this.companyNameView=!0}}},o=n(47),c=n(54),l=n.n(c),f=n(153),d=n(537),m=n(234),v=n(155),h=n(53),y=n(565),O=n(606),component=Object(o.a)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-row",{attrs:{"no-gutters":""}},[n("v-col",{staticClass:"pa-1",attrs:{cols:"12",md:"2"}},[n("span",{staticClass:"text-subtitle-1"},[t._v("Other preferences")])]),t._v(" "),n("v-col",{staticClass:"pa-1",attrs:{cols:"12",md:"8"}},[t.companyNameView?n("FormSectionView",{scopedSlots:t._u([{key:"content",fn:function(){return[n("v-row",{attrs:{"no-gutters":""},on:{click:function(e){t.companyNameView=!1}}},[n("v-col",{staticClass:"pa-2 font-weight-medium",attrs:{cols:"12",md:"4"}},[t._v("\n            Date Format\n          ")]),t._v(" "),n("v-col",{staticClass:"pa-2",attrs:{cols:"12",md:"8"}},[n("span",{staticClass:"text-subtitle-2",domProps:{textContent:t._s(t.form.advanced_date_format)}})])],1)]},proxy:!0}],null,!1,1444627479)}):n("FormSectionEdit",{ref:"sectionEdit",on:{save:t.save,cancel:t.cancel},scopedSlots:t._u([{key:"content",fn:function(){return[n("v-row",{attrs:{"no-gutters":""}},[n("v-col",{staticClass:"pa-2",attrs:{cols:"12",md:"5"}},[n("v-list-item",{staticClass:"pa-0",attrs:{"two-line":""}},[n("v-list-item-content",[n("v-list-item-title",[t._v("Date Format")])],1)],1)],1),t._v(" "),n("v-col",{staticClass:"pa-2",attrs:{cols:"12",md:"7"}},[n("v-select",{attrs:{label:"Date Format",items:t.itemDateFormate,filled:"",dense:"","hide-details":"auto"},model:{value:t.form.advanced_date_format,callback:function(e){t.$set(t.form,"advanced_date_format",e)},expression:"form.advanced_date_format"}})],1)],1)]},proxy:!0}])})],1),t._v(" "),t.companyNameView?n("v-col",{staticClass:"pa-1 text-right",attrs:{cols:"12",md:"1"}},[n("v-btn",{attrs:{icon:"",small:""},on:{click:function(e){t.companyNameView=!1}}},[n("v-icon",[t._v("mdi-pencil")])],1)],1):t._e()],1)}),[],!1,null,null,null);e.default=component.exports;l()(component,{FormSectionView:n(575).default,FormSectionEdit:n(574).default}),l()(component,{VBtn:f.a,VCol:d.a,VIcon:m.a,VListItem:v.a,VListItemContent:h.a,VListItemTitle:h.c,VRow:y.a,VSelect:O.a})},565:function(t,e,n){"use strict";n(9),n(12),n(13),n(14);var r=n(1),o=(n(4),n(39),n(68),n(28),n(11),n(24),n(69),n(327),n(38),n(328),n(329),n(330),n(331),n(332),n(333),n(334),n(335),n(336),n(337),n(338),n(339),n(340),n(42),n(10),n(250),n(2)),c=n(74),l=n(0);function f(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(object);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,n)}return e}function d(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?f(Object(source),!0).forEach((function(e){Object(r.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):f(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}var m=["sm","md","lg","xl"],v=["start","end","center"];function h(t,e){return m.reduce((function(n,r){return n[t+Object(l.H)(r)]=e(),n}),{})}var y=function(t){return[].concat(v,["baseline","stretch"]).includes(t)},O=h("align",(function(){return{type:String,default:null,validator:y}})),w=function(t){return[].concat(v,["space-between","space-around"]).includes(t)},j=h("justify",(function(){return{type:String,default:null,validator:w}})),_=function(t){return[].concat(v,["space-between","space-around","stretch"]).includes(t)},V=h("alignContent",(function(){return{type:String,default:null,validator:_}})),C={align:Object.keys(O),justify:Object.keys(j),alignContent:Object.keys(V)},S={align:"align",justify:"justify",alignContent:"align-content"};function B(t,e,n){var r=S[t];if(null!=n){if(e){var o=e.replace(t,"");r+="-".concat(o)}return(r+="-".concat(n)).toLowerCase()}}var $=new Map;e.a=o.a.extend({name:"v-row",functional:!0,props:d(d(d({tag:{type:String,default:"div"},dense:Boolean,noGutters:Boolean,align:{type:String,default:null,validator:y}},O),{},{justify:{type:String,default:null,validator:w}},j),{},{alignContent:{type:String,default:null,validator:_}},V),render:function(t,e){var n=e.props,data=e.data,o=e.children,l="";for(var f in n)l+=String(n[f]);var d=$.get(l);return d||function(){var t,e;for(e in d=[],C)C[e].forEach((function(t){var r=n[t],o=B(e,t,r);o&&d.push(o)}));d.push((t={"no-gutters":n.noGutters,"row--dense":n.dense},Object(r.a)(t,"align-".concat(n.align),n.align),Object(r.a)(t,"justify-".concat(n.justify),n.justify),Object(r.a)(t,"align-content-".concat(n.alignContent),n.alignContent),t)),$.set(l,d)}(),t(n.tag,Object(c.a)(data,{staticClass:"row",class:d}),o)}})},574:function(t,e,n){"use strict";n.r(e);var r={data:function(){return{tabValue:"company",loadingButton:!1}},methods:{save:function(form){var t=this;this.loadingButton=!0,this.$axios.post("/api/settings",form).then((function(e){t.loadingButton=!1,"company"===t.tabValue&&(t.$nuxt.$emit("getLogo"),t.$nuxt.$emit("getCompany"))})).catch((function(e){t.loadingButton=!1,t.$swal({type:"error",title:"Error",text:e.response.data.message})}))},cancel:function(){this.$emit("cancel")},processData:function(){this.$emit("save")}}},o=n(47),c=n(54),l=n.n(c),f=n(153),d=n(239),m=n(108),v=n(578),h=n(543),component=Object(o.a)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-card",{attrs:{elevation:"0"}},[n("v-form",[t._t("content")],2),t._v(" "),n("v-card-actions",[n("v-spacer"),t._v(" "),n("v-btn",{attrs:{outlined:"",small:""},on:{click:t.cancel}},[t._v(" Cancel ")]),t._v(" "),n("v-btn",{attrs:{color:"primary",small:""},on:{click:t.processData}},[t._v(" Save ")])],1)],1)}),[],!1,null,null,null);e.default=component.exports;l()(component,{VBtn:f.a,VCard:d.a,VCardActions:m.a,VForm:v.a,VSpacer:h.a})},575:function(t,e,n){"use strict";n.r(e);var r=n(47),o=n(54),c=n.n(o),l=n(239),component=Object(r.a)({},(function(){var t=this,e=t.$createElement;return(t._self._c||e)("v-card",{staticStyle:{cursor:"pointer"},attrs:{elevation:"0"}},[t._t("content")],2)}),[],!1,null,null,null);e.default=component.exports;c()(component,{VCard:l.a})},578:function(t,e,n){"use strict";var r=n(1),o=(n(39),n(68),n(252),n(12),n(4),n(10),n(48),n(95),n(11),n(9),n(13),n(14),n(5)),c=n(96),l=n(115);function f(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(object);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,n)}return e}function d(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?f(Object(source),!0).forEach((function(e){Object(r.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):f(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}e.a=Object(o.a)(c.a,Object(l.b)("form")).extend({name:"v-form",provide:function(){return{form:this}},inheritAttrs:!1,props:{disabled:Boolean,lazyValidation:Boolean,readonly:Boolean,value:Boolean},data:function(){return{inputs:[],watchers:[],errorBag:{}}},watch:{errorBag:{handler:function(t){var e=Object.values(t).includes(!0);this.$emit("input",!e)},deep:!0,immediate:!0}},methods:{watchInput:function(input){var t=this,e=function(input){return input.$watch("hasError",(function(e){t.$set(t.errorBag,input._uid,e)}),{immediate:!0})},n={_uid:input._uid,valid:function(){},shouldValidate:function(){}};return this.lazyValidation?n.shouldValidate=input.$watch("shouldValidate",(function(r){r&&(t.errorBag.hasOwnProperty(input._uid)||(n.valid=e(input)))})):n.valid=e(input),n},validate:function(){return 0===this.inputs.filter((function(input){return!input.validate(!0)})).length},reset:function(){this.inputs.forEach((function(input){return input.reset()})),this.resetErrorBag()},resetErrorBag:function(){var t=this;this.lazyValidation&&setTimeout((function(){t.errorBag={}}),0)},resetValidation:function(){this.inputs.forEach((function(input){return input.resetValidation()})),this.resetErrorBag()},register:function(input){this.inputs.push(input),this.watchers.push(this.watchInput(input))},unregister:function(input){var t=this.inputs.find((function(i){return i._uid===input._uid}));if(t){var e=this.watchers.find((function(i){return i._uid===t._uid}));e&&(e.valid(),e.shouldValidate()),this.watchers=this.watchers.filter((function(i){return i._uid!==t._uid})),this.inputs=this.inputs.filter((function(i){return i._uid!==t._uid})),this.$delete(this.errorBag,t._uid)}}},render:function(t){var e=this;return t("form",{staticClass:"v-form",attrs:d({novalidate:!0},this.attrs$),on:{submit:function(t){return e.$emit("submit",t)}}},this.$slots.default)}})}}]);