(window.webpackJsonp=window.webpackJsonp||[]).push([[20],{832:function(t,e,n){"use strict";n.r(e);n(59);var o=n(752),r=n.n(o),d=(n(753),{name:"FieldUpload",components:{Dropzone:r.a},props:{formType:{type:String,default:""},formData:{type:Object,default:function(){return{}}}},data:function(){return{showLoadingAttachment:!1,form:this.formData,options:{url:"/api/document-files",timeout:9e9,addRemoveLinks:!0,withCredentials:!0,thumbnailWidth:50,thumbnailHeight:50,acceptedFiles:"image/*",dictDefaultMessage:"<span class='mdi mdi-cloud-upload'></span> UPLOAD HERE",headers:{"X-XSRF-TOKEN":this.$cookies.get("XSRF-TOKEN")}}}},methods:{sendingParams:function(t,e,n){var o=this.form.id?this.form.id:this.form.temp_id;n.append("temp_id",o),n.append("type",this.formType)},handleError:function(t,e,n){this.$swal({type:"error",title:"Error...",text:e.message})},reloadAttachment:function(t,e){var n=this;e.errors?this.$swal({type:"error",title:"Oops...",text:e.message}):(setTimeout((function(){n.getFiles()}),300),this.$swal({type:"success",title:"Success...",text:"Attachment uploaded!"}))},getFiles:function(){var t=this;this.showLoadingAttachment=!0;var e=this,n=this.form.id?this.form.id:this.form.temp_id;this.$axios.get(e.options.url,{params:{type:this.formType,temp_id:n}}).then((function(n){t.$emit("eventGetFiles",{total:n.data.data.total,row:n.data.data.rows}),e.showLoadingAttachment=!1})).catch((function(e){t.showLoadingAttachment=!1,t.$swal({type:"error",title:"Error...",text:e.response.message})}))},deleteFile:function(t){var e=this,n=this;this.$swal({title:"Are you sure?",text:"The file will be permanently deleted",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then((function(o){o.value&&e.$axios.delete(n.options.url,{params:{id:t.id}}).then((function(t){e.$swal({type:"success",title:"Success...",text:"Attachment Deleted!"}),n.getFiles()})).catch((function(t){e.$swal({type:"error",title:"Oops...",text:t.response.data.message})}))}))}}}),l=n(64),component=Object(l.a)(d,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("dropzone",{ref:"attachment",attrs:{id:"attachment",options:t.options,"destroy-dropzone":!0},on:{"vdropzone-sending":function(e,n,o){return t.sendingParams(e,n,o)},"vdropzone-success":function(e,n){return t.reloadAttachment(e,n)},"vdropzone-error":function(e,n,o){return t.handleError(e,n,o)}}})}),[],!1,null,null,null);e.default=component.exports}}]);