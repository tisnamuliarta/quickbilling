(window.webpackJsonp=window.webpackJsonp||[]).push([[97,50],{1176:function(e,t,n){"use strict";n.r(t);var o={props:{form:{type:Object,default:function(){return{}}},logo:{type:String,default:""}},data:function(){return{companyNameView:!0,itemPaymentTerm:[],itemGreeting:["Dear"],itemName:["[Full Name]","[First Name]"],itemForm:["Invoice","Quotation"]}},methods:{save:function(){this.$refs.sectionEdit.save(this.form),this.companyNameView=!0},cancel:function(){this.companyNameView=!0}}},r=n(47),l=n(54),c=n.n(l),m=n(153),d=n(588),_=n(537),f=n(234),v=n(565),h=n(606),V=n(523),x=n(768),component=Object(r.a)(o,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-row",{attrs:{"no-gutters":""}},[n("v-col",{staticClass:"pa-1",attrs:{cols:"12",md:"2"}},[n("span",{staticClass:"text-subtitle-1"},[e._v("Reminders")])]),e._v(" "),n("v-col",{staticClass:"pa-1",attrs:{cols:"12",md:"8"}},[e.companyNameView?n("FormSectionView",{scopedSlots:e._u([{key:"content",fn:function(){return[n("v-row",{attrs:{"no-gutters":""},on:{click:function(t){e.companyNameView=!1}}},[n("v-col",{staticClass:"pa-2 font-weight-bold",attrs:{cols:"12",md:"12"}},[e._v("\n            Set Up invoice reminder emails\n          ")])],1)]},proxy:!0}],null,!1,2813253711)}):n("FormSectionEdit",{ref:"sectionEdit",on:{save:e.save,cancel:e.cancel},scopedSlots:e._u([{key:"content",fn:function(){return[n("v-row",{attrs:{"no-gutters":""}},[n("v-col",{staticClass:"pa-2",attrs:{cols:"4"}},[n("v-checkbox",{attrs:{label:"Use Greeting","hide-details":"auto"},model:{value:e.form.sales_reminder_use_greeting,callback:function(t){e.$set(e.form,"sales_reminder_use_greeting",t)},expression:"form.sales_reminder_use_greeting"}})],1),e._v(" "),n("v-col",{staticClass:"pa-2",attrs:{cols:"4"}},[n("v-select",{attrs:{label:"Greeting",items:e.itemGreeting,filled:"",dense:"","hide-details":"auto"},model:{value:e.form.sales_reminder_greeting,callback:function(t){e.$set(e.form,"sales_reminder_greeting",t)},expression:"form.sales_reminder_greeting"}})],1),e._v(" "),n("v-col",{staticClass:"pa-2",attrs:{cols:"4"}},[n("v-select",{attrs:{label:"Greeting Name",items:e.itemName,filled:"",dense:"","hide-details":"auto"},model:{value:e.form.sales_reminder_greeting_name,callback:function(t){e.$set(e.form,"sales_reminder_greeting_name",t)},expression:"form.sales_reminder_greeting_name"}})],1),e._v(" "),n("v-col",{staticClass:"pa-2",attrs:{cols:"12"}},[n("v-text-field",{attrs:{label:"Email Subject Line",filled:"",dense:"","hide-details":"auto"},model:{value:e.form.sales_reminder_subject,callback:function(t){e.$set(e.form,"sales_reminder_subject",t)},expression:"form.sales_reminder_subject"}})],1),e._v(" "),n("v-col",{staticClass:"pa-2",attrs:{cols:"12"}},[n("v-textarea",{attrs:{label:"Email Message",filled:"",dense:"","hide-details":"auto"},model:{value:e.form.sales_reminder_content,callback:function(t){e.$set(e.form,"sales_reminder_content",t)},expression:"form.sales_reminder_content"}})],1),e._v(" "),n("v-col",{staticClass:"pa-2",attrs:{cols:"12"}},[n("v-checkbox",{attrs:{label:"Copy me a email","hide-details":"auto"},model:{value:e.form.sales_reminder_copy_email,callback:function(t){e.$set(e.form,"sales_reminder_copy_email",t)},expression:"form.sales_reminder_copy_email"}})],1)],1)]},proxy:!0}])})],1),e._v(" "),e.companyNameView?n("v-col",{staticClass:"pa-1 text-right",attrs:{cols:"12",md:"1"}},[n("v-btn",{attrs:{icon:"",small:""},on:{click:function(t){e.companyNameView=!1}}},[n("v-icon",[e._v("mdi-pencil")])],1)],1):e._e()],1)}),[],!1,null,null,null);t.default=component.exports;c()(component,{FormSectionView:n(575).default,FormSectionEdit:n(574).default}),c()(component,{VBtn:m.a,VCheckbox:d.a,VCol:_.a,VIcon:f.a,VRow:v.a,VSelect:h.a,VTextField:V.a,VTextarea:x.a})},574:function(e,t,n){"use strict";n.r(t);var o={data:function(){return{tabValue:"company",loadingButton:!1}},methods:{save:function(form){var e=this;this.loadingButton=!0,this.$axios.post("/api/settings",form).then((function(t){e.loadingButton=!1,"company"===e.tabValue&&(e.$nuxt.$emit("getLogo"),e.$nuxt.$emit("getCompany"))})).catch((function(t){e.loadingButton=!1,e.$swal({type:"error",title:"Error",text:t.response.data.message})}))},cancel:function(){this.$emit("cancel")},processData:function(){this.$emit("save")}}},r=n(47),l=n(54),c=n.n(l),m=n(153),d=n(239),_=n(108),f=n(578),v=n(543),component=Object(r.a)(o,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-card",{attrs:{elevation:"0"}},[n("v-form",[e._t("content")],2),e._v(" "),n("v-card-actions",[n("v-spacer"),e._v(" "),n("v-btn",{attrs:{outlined:"",small:""},on:{click:e.cancel}},[e._v(" Cancel ")]),e._v(" "),n("v-btn",{attrs:{color:"primary",small:""},on:{click:e.processData}},[e._v(" Save ")])],1)],1)}),[],!1,null,null,null);t.default=component.exports;c()(component,{VBtn:m.a,VCard:d.a,VCardActions:_.a,VForm:f.a,VSpacer:v.a})},575:function(e,t,n){"use strict";n.r(t);var o=n(47),r=n(54),l=n.n(r),c=n(239),component=Object(o.a)({},(function(){var e=this,t=e.$createElement;return(e._self._c||t)("v-card",{staticStyle:{cursor:"pointer"},attrs:{elevation:"0"}},[e._t("content")],2)}),[],!1,null,null,null);t.default=component.exports;l()(component,{VCard:c.a})}}]);