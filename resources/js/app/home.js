import Vue from 'vue';

import store from '../store';
import router from "../router/home";

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
        Vue.prototype.characters_img_path = (img_file_name, img_type = 'jpg') => {
            return 'https://f000.backblazeb2.com/file/idolmoment/characters/'.concat(img_file_name).concat('.', img_type);
        }
        Vue.prototype.img_error = function (e) {
            e.target.parentNode.children[0].remove();

            let source_len = e.target.parentNode.children.length - 1
            e.target.parentNode.children[source_len].src = e.target.parentNode.children[0].srcset;
        }
    }
}

try {
    require('../plugins');

    vue_global.variables();
    vue_global.methods();

    new Vue({
        el: '#app',
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

            this.$store.dispatch("load_my_profile").then(() => {
                this.loaded = true;

                if (store.state.IsCreated) {
                    this.unlock_character_event();
                    this.danger_event();
                }
            });
        },
        methods:{
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
            lightSwitch: function (e) {
                let d = new Date();
                d.setTime(d.getTime() + 365 * 24 * 60 * 60 * 1000);

                let link = document.getElementsByTagName('link');
                let link_swal_style = link[link.length - 1];
                let body = document.getElementsByTagName('body')[0];

                body.className = e.target.checked ? 'dark' : '';
                document.cookie = 'dark_theme='.concat(e.target.checked.toString()).concat('; expires=' + d.toString() + '; path=/');
                localStorage.dark_theme = e.target.checked;
                link_swal_style.href = '/css/sweetalert2.'.concat(e.target.checked ? 'dark' : 'default').concat('.theme.css');
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

                Echo.private('unlock-character-channel-'.concat(this.$store.state.profile.name))
                    .listen('.unlock-character-event', ({Character}) => {
                        unlock_character_message(Character);
                    });
            },
            danger_event: function () {
                let clear_prompt_msg = null;

                Echo.private('danger-channel-'.concat(this.$store.state.profile.name))
                    .listen('.danger-event', ({type, message}) => {
                        if (message) {
                            if (this.prompt_msg)
                                clearTimeout(clear_prompt_msg);

                            this.prompt_type = type;
                            this.prompt_msg = message;
                            this.$store.dispatch("load_my_profile").then(() => {
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
        }
        else {
            if (to.name !== "create-profile" && to.name !== "update-password")
                next({ name:"create-profile" });
            else
                next();
        }
    });

} catch (e) {
    console.log(e);
}
