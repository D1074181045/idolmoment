(window.webpackJsonp=window.webpackJsonp||[]).push([[9],{R2Ai:function(t,a,s){"use strict";s.r(a);var e={data:function(){return{next_name:null,opposite_profile:{use_character:{}},profile_type:localStorage.profile_type?localStorage.profile_type:"details"}},computed:{self_profile:function(){return this.$store.state.profile}},created:function(){switch(localStorage.profile_type){case"details":this.details();break;case"comparison":this.comparison();break;default:this.details()}},activated:function(){var t=this;document.title="玩家資訊",axios.get(this.api_prefix.concat("profile/",this.$route.params.name)).then((function(a){var s=a.status,e=a.opposite_profile;a.operating_time;s&&(document.title="玩家資訊".concat("-",e.nickname),t.opposite_profile=e)}))},methods:{switch_show:function(){switch(this.get_profile_type()){case"details":this.details();break;case"comparison":this.comparison();break;default:this.details()}},get_profile_type:function(){switch(localStorage.profile_type){case"details":return"comparison";case"comparison":return"details";default:return"comparison"}},details:function(){this.next_name="查看能力比對",this.profile_type=localStorage.profile_type="details"},comparison:function(){this.next_name="顯示詳細資料",this.profile_type=localStorage.profile_type="comparison"}}},i=s("KHd+"),_=Object(i.a)(e,(function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",{staticClass:"tb"},[s("h3",[t._v("玩家資料")]),t._v(" "),s("div",{staticClass:"text-center",staticStyle:{margin:"12px 0 12px 0"}},[s("button",{staticClass:"btn btn-info",staticStyle:{width:"115px"},attrs:{type:"button"},on:{click:t.switch_show}},[t._v(t._s(t.next_name))])]),t._v(" "),"details"===t.profile_type?s("div",[s("table",{staticClass:"table"},[s("tbody",[s("tr",[s("th",{staticClass:"table-active"},[t._v("暱稱")]),t._v(" "),s("td",[t._v(t._s(t.opposite_profile.nickname))]),t._v(" "),s("td",{staticStyle:{width:"80px"},attrs:{rowspan:"2"}},[s("div",{staticClass:"img-big"},[s("picture",[s("img",{attrs:{src:t.characters_img_path(t.opposite_profile.use_character.img_file_name),alt:t.opposite_profile.use_character.tc_name}})])])])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("偶像")]),t._v(" "),s("td",[t._v(t._s(t.opposite_profile.use_character.tc_name))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("人氣")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.popularity))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("名聲")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.reputation))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("最大生命值")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.max_vitality))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("精力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.energy))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("抗壓性")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.resistance))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-info"},[t._v("魅力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.charm))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-primary"},[t._v("簽名檔")]),t._v(" "),s("td",{staticStyle:{color:"#DC3545"},attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.signature))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-secondary"},[t._v("轉生次數")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.rebirth_counter))])])])])]):s("div",{staticStyle:{display:"flex"}},[s("table",{staticClass:"table",staticStyle:{width:"50%"}},[s("tbody",[t._m(0),t._v(" "),s("tr",[s("td",{staticStyle:{width:"80px"},attrs:{colspan:"2"}},[s("div",{staticClass:"img-big"},[s("picture",[s("img",{attrs:{src:t.characters_img_path(t.self_profile.use_character.img_file_name),alt:t.self_profile.use_character.tc_name}})])])])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("暱稱")]),t._v(" "),s("td",[t._v(t._s(t.self_profile.nickname))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("偶像")]),t._v(" "),s("td",[t._v(t._s(t.self_profile.use_character.tc_name))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("人氣")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.self_profile.popularity))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("名聲")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.self_profile.reputation))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("最大生命值")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.self_profile.max_vitality))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("精力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.self_profile.energy))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("抗壓性")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.self_profile.resistance))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("魅力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.self_profile.charm))])])])]),t._v(" "),s("table",{staticClass:"table",staticStyle:{width:"50%"}},[s("tbody",[t._m(1),t._v(" "),s("tr",[s("td",{staticStyle:{width:"80px"},attrs:{colspan:"2"}},[s("div",{staticClass:"img-big"},[s("picture",[s("img",{attrs:{src:t.characters_img_path(t.opposite_profile.use_character.img_file_name),alt:t.opposite_profile.use_character.tc_name}})])])])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("暱稱")]),t._v(" "),s("td",[t._v(t._s(t.opposite_profile.nickname))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("偶像")]),t._v(" "),s("td",[t._v(t._s(t.opposite_profile.use_character.tc_name))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("人氣")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.popularity))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("名聲")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.reputation))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("最大生命值")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.max_vitality))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("精力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.energy))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("抗壓性")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.resistance))])]),t._v(" "),s("tr",[s("th",{staticClass:"table-active"},[t._v("魅力")]),t._v(" "),s("td",{attrs:{colspan:"2"}},[t._v(t._s(t.opposite_profile.charm))])])])])])])}),[function(){var t=this.$createElement,a=this._self._c||t;return a("tr",[a("th",{staticClass:"table-active",staticStyle:{"text-align":"center"},attrs:{colspan:"2"}},[this._v("\n                    我的偶像\n                ")])])},function(){var t=this.$createElement,a=this._self._c||t;return a("tr",[a("th",{staticClass:"table-active",staticStyle:{"text-align":"center"},attrs:{colspan:"2"}},[this._v("\n                    對方偶像\n                ")])])}],!1,null,"25e9c849",null);a.default=_.exports}}]);