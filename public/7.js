(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{"0gwx":function(t,r,e){(t.exports=e("I1BE")(!1)).push([t.i,"\n.table[data-v-60d51514] {\n    margin-bottom: 1px;\n}\n.current-select[data-v-60d51514] {\n    border-width: 3px;\n    border-style: solid;\n    border-image: initial;\n    display: flex;\n    flex-direction: column;\n    height: 100%;\n    padding: 2px 4px;\n    transition: all 0.2s ease 0s;\n    cursor: default;\n    border-color: var(--primary-bg-color);\n}\n.not-select[data-v-60d51514] {\n    border: 3px solid transparent;\n    cursor: pointer;\n    display: flex;\n    flex-direction: column;\n    height: 100%;\n    padding: 2px 4px;\n    transition: all 0.2s ease 0s;\n}\n.not-select[data-v-60d51514]:hover {\n    border-color: rgb(119, 119, 119);\n}\n.character-frame[data-v-60d51514] {\n    width: 50%;\n}\n.character-frame[data-v-60d51514]:first-child {\n    width: 50%;\n    border-top: 1px solid var(--border-color);\n    border-bottom: 1px solid var(--border-color);\n    border-right: 1px solid var(--border-color);\n    border-left: 1px solid var(--border-color);\n}\n.character-frame[data-v-60d51514]:nth-child(2) {\n    width: 50%;\n    border-top: 1px solid var(--border-color);\n    border-bottom: 1px solid var(--border-color);\n    border-right: 1px solid var(--border-color);\n}\n.character-frame[data-v-60d51514]:nth-child(odd) {\n    width: 50%;\n    border-bottom: 1px solid var(--border-color);\n    border-right: 1px solid var(--border-color);\n    border-left: 1px solid var(--border-color);\n}\n.character-frame[data-v-60d51514]:nth-child(even) {\n    width: 50%;\n    border-bottom: 1px solid var(--border-color);\n    border-right: 1px solid var(--border-color);\n}\n@media screen and (max-width: 640px) {\n.character-frame[data-v-60d51514] {\n        width: 100%;\n        border-right: 1px solid var(--border-color);\n        border-left: 1px solid var(--border-color);\n        border-bottom: 1px solid var(--border-color);\n}\n.character-frame[data-v-60d51514]:nth-child(odd) {\n        width: 100%;\n}\n.character-frame[data-v-60d51514]:nth-child(even) {\n        width: 100%;\n}\n.character-frame[data-v-60d51514]:last-child {\n        width: 100%;\n}\n}\n",""])},eXOz:function(t,r,e){var a=e("0gwx");"string"==typeof a&&(a=[[t.i,a,""]]);var c={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(a,c);a.locals&&(t.exports=a.locals)},j9DL:function(t,r,e){"use strict";e.r(r);var a={data:function(){return{rebirth_disabled:!0,selected_character:"",own_character_list:[]}},activated:function(){document.title="偶像轉生",this.get_own_character().then((function(){document.getElementsByClassName("character-frame")[0].firstChild.click()}))},methods:{character_select_status:function(t){return this.selected_character===t?"current-select":"not-select"},get_own_character:function(){var t=this;return axios.get(this.api_prefix.concat("own-character")).then((function(r){var e=r.status,a=r.own_character_list;e&&(t.own_character_list=a)}))},select_character:function(t){this.selected_character=t,this.rebirth_disabled=!1},to_rebirth:function(){var t=this;this.rebirth_disabled=!0,axios.patch(this.api_prefix.concat("rebirth"),{character_name:this.selected_character}).then((function(r){r.status?t.$router.push({name:"index"}):t.rebirth_disabled=!1})).catch((function(r){t.rebirth_disabled=!1}))}}},c=(e("nNnI"),e("KHd+")),n=Object(c.a)(a,(function(){var t=this,r=t.$createElement,e=t._self._c||r;return e("div",[e("div",{staticClass:"tb"},[e("h3",[t._v("選擇轉生偶像")]),t._v(" "),e("div",{staticStyle:{display:"flex","flex-wrap":"wrap"}},t._l(t.own_character_list,(function(r){return e("div",{staticClass:"character-frame"},[e("div",{class:t.character_select_status(r.character_name),on:{click:function(e){return t.select_character(r.character_name)}}},[e("div",{staticClass:"img-big"},[e("picture",[e("source",{attrs:{type:"image/png",srcset:t.characters_img_path(r.game_character.img_file_name)}}),t._v(" "),e("img",{attrs:{src:t.characters_img_path(r.game_character.img_file_name),alt:r.character_name}})])]),t._v(" "),e("div",{staticStyle:{flex:"1 1"}},[e("p",{staticStyle:{"margin-top":"1rem"}},[t._v("\n                            "+t._s(r.game_character.introduction)+"\n                        ")])]),t._v(" "),e("table",{staticClass:"table"},[e("tbody",{staticClass:"thead-light"},[e("tr",[e("th",{staticClass:"text-center"},[t._v("英文名稱")]),t._v(" "),e("td",[t._v(t._s(r.game_character.en_name))])]),t._v(" "),e("tr",[e("th",{staticClass:"text-center"},[t._v("中文名稱")]),t._v(" "),e("td",[t._v(t._s(r.game_character.tc_name))])]),t._v(" "),e("tr",[e("th",{staticClass:"text-center"},[t._v("生命值")]),t._v(" "),e("td",[t._v(t._s(r.game_character.vitality))])]),t._v(" "),e("tr",[e("th",{staticClass:"text-center"},[t._v("精力")]),t._v(" "),e("td",[t._v(t._s(r.game_character.energy))])]),t._v(" "),e("tr",[e("th",{staticClass:"text-center"},[t._v("抗壓性")]),t._v(" "),e("td",[t._v(t._s(r.game_character.resistance))])]),t._v(" "),e("tr",[e("th",{staticClass:"text-center"},[t._v("魅力")]),t._v(" "),e("td",[t._v(t._s(r.game_character.charm))])])])])])])})),0)]),t._v(" "),e("button",{staticClass:"btn btn-primary btn-block",staticStyle:{margin:"0 0"},attrs:{disabled:t.rebirth_disabled},on:{click:t.to_rebirth}},[t._v("轉生")])])}),[],!1,null,"60d51514",null);r.default=n.exports},nNnI:function(t,r,e){"use strict";e("eXOz")}}]);