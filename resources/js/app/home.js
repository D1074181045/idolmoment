import Vue from 'vue';
import {mapActions} from "vuex";

import store from '../store';
import router from "../router/home";

import LightSwitch from "../components/LightSwitch";

class vue_global {
    static variables() {
        Vue.prototype.first_load = true;
    };
    static methods() {
        let surplus_clear_cache = Date.now();

        Vue.prototype.reload = () => {
            if (Date.now() > surplus_clear_cache) {
                let now = new Date();
                now.setSeconds(now.getSeconds() + 5);
                surplus_clear_cache = now;
                return true;
            } else {
                return false;
            }
        }
    }
}

try {
    require('../plugins');

    vue_global.variables();
    vue_global.methods();

    router.beforeEach((to, from, next) => {
        document.title = to.meta.title;
        next();
    });

    new Vue({
        el: '#app',
        components: {
            lightswitch: LightSwitch
        },
        store,
        data() {
            return {
                loaded: false,
                prompt_msg: null,
                prompt_type: null
            }
        },
        created() {
            window.axios.defaults.headers.common['Authorization'] = 'Bearer'.concat(' ', localStorage.token);

            this.load_my_profile().then(() => {
                this.loaded = true;

                if (store.state.IsCreated) {
                    this.unlock_character_event();
                    this.danger_event();
                }
            });
        },
        methods:{
            ...mapActions([
                'load_my_profile'
            ]),
            logout: function () {
                Swal.fire({
                    title: "登出",
                    text: "確定登出?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '確定',
                    cancelButtonText: '取消',
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = '/logout';
                        localStorage.removeItem('token');
                    }
                });
            },
            unlock_character_event: function () {
                function keyup_unlock_role (character_name) {
                    const url = store.state.api_prefix.concat('unlock-character');
                    axios.post(url, {
                        character_name: character_name,
                    })
                }

                function unlock_character_message (new_character_name) {
                    let character = '<div style="color: #DC3545;">' + new_character_name + '</div>';

                    return Swal.fire({
                        title: "已解鎖新偶像",
                        html: character,
                        icon: "success",
                        allowOutsideClick: false
                    });
                }

                let key = "";

                const surprises = [
                    {'keyCode': "383840403737393966656665", 'character-name': "Akai Haato"}, // 上上下下左左右右BABA
                    {'keyCode': "82828282", 'character-name': "Uruha Rushia"}, // rrrr
                    {'keyCode': "85668566", 'character-name': "Inugami Korone"} // ubub
                ];

                document.addEventListener('keyup', function (e) {
                    if (e.keyCode) {
                        let t = 0;

                        key += e.keyCode.toString();

                        surprises.forEach((surprise) => {
                            if (key === surprise['keyCode']) {
                                keyup_unlock_role(surprise['character-name']);
                                return true;
                            } else if (surprise['keyCode'].includes(key)) {
                                return true;
                            } else {
                                t++;
                            }
                        });

                        if (surprises.length === t)
                            key = e.keyCode.toString();
                    }
                })

                Echo.private('unlock-character-channel-'.concat(store.state.profile.name))
                    .listen('.unlock-character-event', ({Character}) => {
                        unlock_character_message(Character);
                    });
            },
            danger_event: function () {
                let clear_prompt_msg = null;

                Echo.private('danger-channel-'.concat(store.state.profile.name))
                    .listen('.danger-event', ({type, message}) => {
                        if (message) {
                            if (this.prompt_msg)
                                clearTimeout(clear_prompt_msg);

                            this.prompt_type = type;
                            this.prompt_msg = message;
                            this.load_my_profile().then(() => {
                                store.state.prompt_count++;
                            });

                            clear_prompt_msg = setTimeout(() => {
                                this.prompt_msg = null;
                            }, 10000);
                        }
                    });
            }
        },
        router
    });

    router.beforeEach((to, from, next) => {
        store.commit('error_clear');

        if (store.state.IsCreated) {
            Vue.prototype.first_load = false;
            next();
        } else {
            if (to.name !== "create-profile" && to.name !== "update-password")
                next({name: "create-profile"});
            else
                next();
        }
    });

} catch (e) {
    console.log(e);
}
