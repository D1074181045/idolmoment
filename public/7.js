(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{"37se":function(t,e,s){var a=s("i+4X");"string"==typeof a&&(a=[[t.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};s("aET+")(a,i);a.locals&&(t.exports=a.locals)},R2Ai:function(t,e,s){"use strict";s.r(e);var a={data:function(){return{information_list:[],next_name:null,opposite_profile:{use_character:{}},profile_type:localStorage.profile_type?localStorage.profile_type:"details",opposite_loaded:!1,title:null}},components:{msg:s("v9aq").a},computed:{self_profile:function(){return this.$store.state.profile},operating_ban:function(){return this.$store.state.ban_type.operating},operating_disabled:function(){return this.$store.state.ban_type.operating.status},api_prefix:function(){return this.$store.state.api_prefix},cool_down:function(){return this.$store.state.cool_down}},created:function(){switch(localStorage.profile_type){case"details":this.details();break;case"comparison":this.comparison();break;default:this.details()}},mounted:function(){this.$store.commit("cool_down","operating")},activated:function(){var t=this,e=this.api_prefix.concat("profile/",this.$route.params.name);this.title?document.title=this.title:document.title="玩家資訊",axios.get(e).then((function(e){var s=e.status,a=e.opposite_profile;s&&(t.title||(document.title=t.title="玩家資訊".concat("-",a.nickname)),t.opposite_profile=a,t.opposite_loaded=!0)}))},methods:{switch_show:function(){switch(this.get_profile_type()){case"details":this.details();break;case"comparison":this.comparison();break;default:this.details()}},get_profile_type:function(){switch(localStorage.profile_type){case"details":return"comparison";case"comparison":return"details";default:return"comparison"}},details:function(){this.next_name="查看能力比對",this.profile_type=localStorage.profile_type="details"},comparison:function(){this.next_name="顯示詳細資料",this.profile_type=localStorage.profile_type="comparison"},operating:function(t){var e=this;if(!this.operating_ban.time){var s=this.api_prefix.concat("operating");this.operating_ban.status=!0,axios.patch(s,{opposite_name:this.$route.params.name,operating_type:t}).then((function(t){var s=t.status,a=t.opposite_ability,i=t.self_ability,r=t.operating_time,o=t.information;s?(e.cool_down.operating=r,e.$store.commit("cool_down","operating"),Object.keys(a).forEach((function(t){e.opposite_profile[t]=a[t]})),Object.keys(i).forEach((function(t){e.self_profile[t]=i[t]})),e.information_list.push(o)):e.operating_ban.status=!1})).catch((function(t){e.operating_ban.status=!1}))}}}},i=(s("k+a9"),s("KHd+")),r=Object(i.a)(a,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{staticClass:"tb"},[s("h3",[t._v("玩家資料")]),t._v(" "),s("div",{staticClass:"text-center",staticStyle:{margin:"12px 0 12px 0"}},[s("button",{staticClass:"btn btn-info",staticStyle:{width:"115px"},attrs:{type:"button"},on:{click:t.switch_show}},[t._v(t._s(t.next_name)+"\n            ")])]),t._v(" "),"details"===t.profile_type?s("div",[s("table",{staticClass:"table"},[s("tbody",[s("tr",[s("th",{staticClass:"table-active"},[t._v("暱稱")]),t._v(" "),s("td",[t._v(t._s(t.opposite_profile.nickname))]),t._v(" "),s("td",{staticStyle:{width:"80px"},attrs:{rowspan:"2"}},[s("div",{staticClass:"img-big"},[t.opposite_loaded?s("picture",[s("source",{attrs:{type:"image/png",srcset:t.characters_img_path(t.opposite_profile.use_character.img_file_name)}}),t._v(" "),s("img",{attrs:{src:t.characters_img_path(t.opposite_profile.use_character.img_file_name),alt:t.opposite_profile.use_character.tc_name}})]):t._e()])])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("偶像")]),t._v(" "),s("td",[t._v(t._s(t.opposite_profile.use_character.tc_name))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("人氣")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.popularity)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("名聲")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.reputation)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("最大生命值")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.max_vitality)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("精力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.energy)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("抗壓性")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.resistance)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("魅力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.charm)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-primary"},[t._v("簽名檔")]),t._v(" "),s("td",{staticStyle:{color:"#DC3545"},attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.signature))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-secondary"},[t._v("轉生次數")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.rebirth_counter)))])])])])]):s("div",{staticStyle:{display:"flex"}},[s("table",{staticClass:"table",staticStyle:{width:"50%"}},[s("tbody",[t._m(0),t._v(" "),s("tr",[s("td",{staticStyle:{width:"80px"},attrs:{colspan:"2"}},[s("div",{staticClass:"img-big"},[s("picture",[s("source",{attrs:{type:"image/png",srcset:t.characters_img_path(t.self_profile.use_character.img_file_name)}}),t._v(" "),s("img",{attrs:{src:t.characters_img_path(t.self_profile.use_character.img_file_name),alt:t.self_profile.use_character.tc_name}})])])])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("暱稱")]),t._v(" "),s("td",[t._v(t._s(t.self_profile.nickname))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("偶像")]),t._v(" "),s("td",[t._v(t._s(t.self_profile.use_character.tc_name))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("人氣")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.self_profile.popularity)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("名聲")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.self_profile.reputation)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("最大生命值")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.self_profile.max_vitality)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("精力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.self_profile.energy)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("抗壓性")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.self_profile.resistance)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("魅力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.self_profile.charm)))])])])]),t._v(" "),s("table",{staticClass:"table",staticStyle:{width:"50%"}},[s("tbody",[t._m(1),t._v(" "),s("tr",[s("td",{staticStyle:{width:"80px"},attrs:{colspan:"2"}},[s("div",{staticClass:"img-big"},[t.opposite_loaded?s("picture",[s("source",{attrs:{type:"image/png",srcset:t.characters_img_path(t.opposite_profile.use_character.img_file_name)}}),t._v(" "),s("img",{attrs:{src:t.characters_img_path(t.opposite_profile.use_character.img_file_name),alt:t.opposite_profile.use_character.tc_name}})]):t._e()])])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("暱稱")]),t._v(" "),s("td",[t._v(t._s(t.opposite_profile.nickname))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("偶像")]),t._v(" "),s("td",[t._v(t._s(t.opposite_profile.use_character.tc_name))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("人氣")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.popularity)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("名聲")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.reputation)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("最大生命值")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.max_vitality)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("精力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.energy)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("抗壓性")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.resistance)))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("魅力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.$store.getters.NumberFormat(t.opposite_profile.charm)))])])])])])]),t._v(" "),t.opposite_profile.name!==t.self_profile.name&&t.opposite_loaded?s("div",{staticClass:"tb"},[t.opposite_profile.graduate?s("div",[s("h3",[t._v("對方已畢業")]),t._v(" "),t._m(2)]):t.self_profile.graduate?s("div",[s("h3",[t._v("你已畢業")]),t._v(" "),t._m(3)]):s("div",[s("h3",[t._v("操作")]),t._v(" "),t.operating_ban.time?s("msg",[t._v("剩餘時間："+t._s(t.operating_ban.time))]):t._e(),t._v(" "),s("div",{staticClass:"tb-gap",staticStyle:{"margin-left":"-10px"}},[s("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:t.operating_disabled},on:{click:function(e){return t.operating("send-blade")}}},[t._v("寄刀片\n                ")]),t._v(" "),s("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:t.operating_disabled},on:{click:function(e){return t.operating("endorse")}}},[t._v("聲援\n                ")]),t._v(" "),s("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:t.operating_disabled},on:{click:function(e){return t.operating("donate")}}},[t._v("斗內\n                ")])])],1)]):t._e(),t._v(" "),s("div",{staticClass:"tb"},[s("h3",[t._v("獲得情報")]),t._v(" "),t._l(t.information_list,(function(e,a){return s("div",{staticStyle:{display:"flex"}},[s("div",{staticStyle:{color:"rgb(153, 153, 153)","user-select":"none",width:"30px"}},[t._v(t._s(a+1))]),t._v(" "),s("div",{staticStyle:{display:"flex"},domProps:{innerHTML:t._s(e)}})])}))],2)])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("tr",[e("th",{staticClass:"table-active",staticStyle:{"text-align":"center"},attrs:{colspan:"2"}},[this._v("\n                        我的偶像\n                    ")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("tr",[e("th",{staticClass:"table-active",staticStyle:{"text-align":"center"},attrs:{colspan:"2"}},[this._v("\n                        對方偶像\n                    ")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"tb-gap",staticStyle:{"margin-left":"-10px"}},[e("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:""}},[this._v("寄刀片")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"tb-gap",staticStyle:{"margin-left":"-10px"}},[e("button",{staticClass:"btn btn-bottom btn-info",attrs:{type:"button",disabled:""}},[this._v("寄刀片")])])}],!1,null,"90475740",null);e.default=r.exports},"i+4X":function(t,e,s){(t.exports=s("I1BE")(!1)).push([t.i,"\n.tb .tb-gap[data-v-90475740] {\n    margin-top: 15px;\n    margin-bottom: 10px;\n}\n",""])},"k+a9":function(t,e,s){"use strict";s("37se")},v9aq:function(t,e,s){"use strict";var a;s.d(e,"a",(function(){return o}));var i,r,o=s("nFaw").a.div(a||(i=["\n    color: #9A0000;\n"],r||(r=i.slice(0)),a=Object.freeze(Object.defineProperties(i,{raw:{value:Object.freeze(r)}}))))}}]);