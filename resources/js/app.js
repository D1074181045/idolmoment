import Vue from 'vue';
import store from './store';
import router from './router_home.js';

let surplus_clear_cache = Date.now();

class vue_global {
    static variables() {
        // Vue.prototype.api_prefix = '/api/';
        Vue.prototype.first_load = true;
    };

    static methods() {
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
        Vue.prototype.characters_img_path = (img_file_name) => {
            return '/img/characters/'.concat(img_file_name, '.jpg');
        }
    }
}

try {
    require('./bootstrap');
    require('./pusher');
    require('./axios');

    window.Swal = require('sweetalert2');

    vue_global.variables();
    vue_global.methods();

    new Vue({
        el: '#app',
        store,
        data() {
            return {
                loaded: false,
                danger_msg: null,
            }
        },
        created() {
            this.$store.dispatch("load_my_profile").then(() => {
                this.loaded = true;

                let clear_danger_msg = null;

                if (this.$store.state.profile) {
                    Echo.private('danger-channel-'.concat(this.$store.state.profile.name))
                        .listen('.danger-event', ({message}) => {
                            if (message) {
                                if (this.danger_msg)
                                    clearTimeout(clear_danger_msg);

                                this.danger_msg = message;

                                clear_danger_msg = setTimeout(() => {
                                    this.danger_msg = null;
                                }, 10000);
                            }
                        });
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

                if (e.target.checked) {
                    body.className = 'dark';
                    document.cookie = 'dark_theme=true; expires=' + d.toString() + '; path=/';
                    localStorage.dark_theme = true;
                    link_swal_style.href = '/css/sweetalert2.dark.theme.css';
                } else {
                    body.className = '';
                    document.cookie = 'dark_theme=false; expires=' + d.toString() + '; path=/';
                    localStorage.dark_theme = false;
                    link_swal_style.href = '/css/sweetalert2.default.theme.css';
                }
            }
        },
        router,
    });

    router.beforeEach((to, from, next) => {
        if (store.state.IsCreated)
            next();
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
