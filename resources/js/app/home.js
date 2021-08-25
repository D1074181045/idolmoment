import Vue from 'vue';

import {mapActions} from "vuex";

import store from '../store';
import router from "../router/home";

import LightSwitch from "../components/LightSwitch";
import Loading from '../components/Loading'

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

    window.axios.interceptors.request.use(
        function (config) {
            router.app.$nextTick(() => {
                router.app.$refs.loading.start();
            })
            return config;
        }
    );

    window.axios.interceptors.response.use(
        function (response) {
            router.app.$nextTick(() => {
                router.app.$refs.loading.finish(true);
            })
            return response;
        },
        function (error) {
            router.app.$nextTick(() => {
                router.app.$refs.loading.finish(false);
            })
            return Promise.reject(error);
        }
    );

    vue_global.variables();
    vue_global.methods();

    router.beforeEach((to, from, next) => {
        document.title = to.meta.title;
        router.app.$nextTick(() => {
            router.app.$refs.loading.start();
        })
        next();
    });

    router.afterEach((to, from, next) => {
        router.app.$nextTick(() => {
            router.app.$refs.loading.finish(true);
        })
    })

    new Vue({
        el: '#app',
        components: {
            lightswitch: LightSwitch,
            loading: Loading
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
                if (store.state.personal_profile_status) {
                    this.keyup_unlock_character();
                    this.unlock_character_event();
                    this.danger_event();
                }
            }).finally(() => {
                this.loaded = true;
            });
        },
        mounted() {
            store.state.loading = this.$refs.loading;
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
            keyup_unlock_character: function () {
                let key = "";

                const surprises = [
                    {'keyCode': "726567726577656772657765", 'character-name': "Akai Haato"}, // hachamachama
                    {'keyCode': "82828282", 'character-name': "Uruha Rushia"}, // rrrr
                    {'keyCode': "85668566", 'character-name': "Inugami Korone"} // ubub
                ];

                document.addEventListener('keyup', function (e) {
                    if (e.keyCode) {
                        let t = 0;

                        key += e.keyCode.toString();

                        surprises.forEach((surprise) => {
                            if (key === surprise['keyCode']) {
                                unlock_character(surprise['character-name']);
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

                function unlock_character (character_name) {
                    const url = store.state.api_prefix.concat('unlock-character');
                    axios.post(url, {
                        character_name: character_name,
                    })
                }
            },
            unlock_character_event: function () {
                Echo.private('unlock-character-channel-'.concat(store.state.profile.name))
                    .listen('.unlock-character-event', ({Character}) => {
                        console.log("DEBUG", '獲得新偶像', Character);
                        unlock_character_message(Character);
                    });

                function unlock_character_message (new_character_name) {
                    let character = '<div style="color: #DC3545;">' + new_character_name + '</div>';

                    return Swal.fire({
                        title: "已解鎖新偶像",
                        html: character,
                        icon: "success",
                        allowOutsideClick: false
                    });
                }
            },
            danger_event: function () {
                Echo.private('danger-channel-'.concat(store.state.profile.name))
                    .listen('.danger-event', ({type, message}) => {
                        console.log("DEBUG", '緊急事件', {type: type, message: message});
                        if (message) {
                            if (this.clear_prompt_msg) clearTimeout(this.clear_prompt_msg);

                            this.prompt_type = type;
                            this.prompt_msg = message;

                            this.load_my_profile().then(() => {
                                store.state.prompt_count++;
                            });

                            this.clear_prompt_msg = setTimeout(() => {
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

        if (store.state.personal_profile_status) {
            Vue.prototype.first_load = false;
            next();
        } else {
            if (to.name !== "create-profile" && to.name !== "update-password")
                next({name: "create-profile"});
            else
                next();
            router.app.$nextTick(() => {
                router.app.$refs.loading.finish(true);
            })
        }
    });

} catch (e) {
    console.log(e);
}
