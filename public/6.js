(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{"0jpQ":function(t,e,i){"use strict";i.r(e);var s=i("v9aq"),a=new RegExp("^[㄀-ㄯ一-龥a-zA-Z0-9 ]+$"),n={data:function(){return{c_signature_disabled:!1}},components:{msg:s.a},computed:{signature:function(){return this.profile.signature},signature_disabled:function(){return this.$store.state.ban_type.signature.status},activity_disabled:function(){return this.$store.state.ban_type.activity.status},profile:function(){return this.$store.state.profile},teetee_info:function(){return this.$store.state.teetee_info},cool_down:function(){return this.$store.state.cool_down},signature_ban:function(){return this.$store.state.ban_type.signature},activity_ban:function(){return this.$store.state.ban_type.activity},api_prefix:function(){return this.$store.state.api_prefix}},mounted:function(){this.$store.commit("cool_down","activity"),this.$store.commit("cool_down","signature")},activated:function(){document.title="我的偶像",this.first_load?this.first_load=!1:this.$store.dispatch("load_my_profile")},methods:{set_teetee:function(){var t=this;if(!(this.profile.teetee.length>12||!this.profile.teetee.match(a)&&0!==this.profile.teetee.length)){var e=this.api_prefix.concat("update-teetee");axios.patch(e,{teetee:this.profile.teetee}).then((function(e){var i=e.teetee_status,s=e.teetee_name;t.teetee_info.status=i,t.teetee_info.teetee_name=s}))}},set_signature:function(){var t=this;if(!this.signature_ban.status){var e=this.api_prefix.concat("update-signature");this.signature_ban.status=!0,axios.patch(e,{signature:this.signature}).then((function(e){var i=e.status,s=e.signature_time;i?(t.cool_down.signature=s,t.$store.commit("cool_down","signature")):t.signature_ban.status=!1})).catch((function(e){t.signature_ban.status=!1}))}},ban_signature:function(){this.signature_ban.status||(this.profile.signature.match(a)?(this.signature.status=this.profile.signature.length>30,this.c_signature_disabled=this.profile.signature.length>30):(this.signature.status=0!==this.profile.signature.length,this.c_signature_disabled=0!==this.profile.signature.length))},do_activity:function(t){var e=this;if(!this.activity_ban.status){var i=this.api_prefix.concat("activity");this.activity_ban.status=!0,axios.patch(i,{activity_type:t}).then((function(t){var i=t.status,s=t.ability,a=t.activity_time;i?(e.cool_down.activity=a,e.$store.commit("cool_down","activity"),Object.keys(s).forEach((function(t){e.profile[t]=s[t]}))):e.activity_ban.status=!1})).catch((function(t){e.activity_ban.status=!1}))}}}},o=(i("UZKQ"),i("KHd+")),r=Object(o.a)(n,(function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("div",{staticClass:"tb"},[i("h3",[t._v("我的偶像")]),t._v(" "),i("table",{staticClass:"table"},[i("tbody",[i("tr",[i("th",{staticClass:"table-active"},[t._v("暱稱")]),t._v(" "),i("td",[t._v(t._s(t.profile.nickname))]),t._v(" "),i("td",{staticStyle:{width:"80px"},attrs:{rowspan:"2"}},[i("div",{staticClass:"img-big"},[i("picture",[i("source",{attrs:{type:"image/png",srcset:t.characters_img_path(t.profile.use_character.img_file_name)}}),t._v(" "),i("img",{attrs:{src:t.characters_img_path(t.profile.use_character.img_file_name),alt:t.characters_img_path(t.profile.use_character.tc_name)}})])])])]),t._v(" "),i("tr",[i("th",{staticClass:"table-active"},[t._v("偶像")]),t._v(" "),i("td",[t._v(t._s(t.profile.use_character.tc_name))])]),t._v(" "),i("tr",[i("th",{staticClass:"table-info"},[t._v("人氣")]),t._v(" "),i("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.profile.popularity)))])]),t._v(" "),i("tr",[i("th",{staticClass:"table-info"},[t._v("名聲")]),t._v(" "),i("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.profile.reputation)))])]),t._v(" "),i("tr",[i("th",{staticClass:"table-info"},[t._v("最大生命值")]),t._v(" "),i("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.profile.max_vitality)))])]),t._v(" "),i("tr",[i("th",{staticClass:"table-info"},[t._v("目前生命值")]),t._v(" "),i("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.profile.current_vitality)))])]),t._v(" "),i("tr",[i("th",{staticClass:"table-info"},[t._v("精力")]),t._v(" "),i("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.profile.energy)))])]),t._v(" "),i("tr",[i("th",{staticClass:"table-info"},[t._v("抗壓性")]),t._v(" "),i("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.profile.resistance)))])]),t._v(" "),i("tr",[i("th",{staticClass:"table-info"},[t._v("魅力")]),t._v(" "),i("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.profile.charm)))])]),t._v(" "),i("tr",[i("th",{staticClass:"table-secondary"},[t._v("轉生次數")]),t._v(" "),i("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.profile.rebirth_counter)))])])])])]),t._v(" "),i("div",{staticClass:"tb"},[i("h3",[t._v("個人設定")]),t._v(" "),t.signature_ban.time?i("msg",[t._v("剩餘時間："+t._s(t.signature_ban.time))]):t._e(),t._v(" "),i("div",{staticClass:"tb-gap"},[i("div",{staticClass:"setting"},[i("label",{staticStyle:{width:"80px","margin-bottom":"0"}},[t._v("簽名檔")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.profile.signature,expression:"profile.signature"}],staticClass:"form-control",class:t.$store.getters.disabled_class(t.c_signature_disabled),attrs:{placeholder:"最多30個字",type:"text"},domProps:{value:t.profile.signature},on:{input:[function(e){e.target.composing||t.$set(t.profile,"signature",e.target.value)},t.ban_signature]}}),t._v(" "),i("button",{staticClass:"btn btn-info",attrs:{type:"button",disabled:t.signature_disabled},on:{click:t.set_signature}},[t._v("更新")])]),t._v(" "),i("div",{staticClass:"setting"},[i("label",{staticStyle:{width:"80px","margin-bottom":"0"}},[t._v("貼貼")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.profile.teetee,expression:"profile.teetee"}],staticClass:"form-control",class:t.$store.getters.disabled_class(!t.teetee_info.status),attrs:{type:"text",maxlength:"12"},domProps:{value:t.profile.teetee},on:{input:function(e){e.target.composing||t.$set(t.profile,"teetee",e.target.value)}}}),t._v(" "),i("button",{staticClass:"btn btn-info",attrs:{type:"button"},on:{click:t.set_teetee}},[t._v("設定")])])])],1),t._v(" "),t.profile.graduate?i("div",{staticClass:"tb"},[i("h3",[t._v("已畢業，無法進行活動")]),t._v(" "),t._m(0)]):i("div",{staticClass:"tb"},[i("h3",[t._v("進行活動")]),t._v(" "),t.activity_ban.time?i("msg",[t._v("剩餘時間："+t._s(t.activity_ban.time))]):t._e(),t._v(" "),i("div",{staticClass:"tb-gap",staticStyle:{"margin-left":"-10px"}},[i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:t.activity_disabled},on:{click:function(e){return t.do_activity("adult-live")}}},[t._v("成人直播")]),t._v(" "),i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:t.activity_disabled},on:{click:function(e){return t.do_activity("live")}}},[t._v("直播")]),t._v(" "),i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:t.activity_disabled},on:{click:function(e){return t.do_activity("do-good-things")}}},[t._v("做善事")]),t._v(" "),i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:t.activity_disabled},on:{click:function(e){return t.do_activity("go-to-sleep")}}},[t._v("睡覺")]),t._v(" "),i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:t.activity_disabled},on:{click:function(e){return t.do_activity("meditation")}}},[t._v("打坐")])])],1)])}),[function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"tb-gap",staticStyle:{"margin-left":"-10px"}},[i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:""}},[t._v("成人直播")]),t._v(" "),i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:""}},[t._v("直播")]),t._v(" "),i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:""}},[t._v("做善事")]),t._v(" "),i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:""}},[t._v("睡覺")]),t._v(" "),i("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:""}},[t._v("打坐")])])}],!1,null,"bbd9631e",null);e.default=r.exports},"57gc":function(t,e,i){var s=i("Gb/0");"string"==typeof s&&(s=[[t.i,s,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};i("aET+")(s,a);s.locals&&(t.exports=s.locals)},"Gb/0":function(t,e,i){(t.exports=i("I1BE")(!1)).push([t.i,"\n.tb .tb-gap[data-v-bbd9631e] {\n    margin-top: 15px;\n    margin-bottom: 10px;\n}\nbutton.btn.btn-bottom[data-v-bbd9631e] {\n    margin-bottom: 12px;\n    margin-right: 3px;\n}\n",""])},UZKQ:function(t,e,i){"use strict";i("57gc")},v9aq:function(t,e,i){"use strict";var s;i.d(e,"a",(function(){return o}));var a,n,o=i("nFaw").a.div(s||(a=["\n    color: #9A0000;\n"],n||(n=a.slice(0)),s=Object.freeze(Object.defineProperties(a,{raw:{value:Object.freeze(n)}}))))}}]);