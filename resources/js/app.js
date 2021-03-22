import Vue from 'vue';
import store from './store';
import router from './router_home.js';

let surplus_clear_cache = Date.now();

class vue_global {
    static variables() {
        Vue.prototype.api_prefix = '/api/';
        Vue.prototype.first_load = true;
        Vue.prototype.IsCreated = false;
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
            return 'img/characters/'.concat(img_file_name, '.jpg');
        }

        // Vue.prototype.load_my_profile = () => {
        //     window.axios.defaults.headers.common['Authorization'] = 'Bearer'.concat(' ', localStorage.token);
        //
        //     return axios.get(Vue.prototype.api_prefix.concat('my-profile'))
        //         .then(({status, cool_down, my_profile, teetee_info}) => {
        //             Vue.prototype.IsCreated = status;
        //
        //             if (status) {
        //                 Vue.prototype.cool_down = cool_down;
        //                 Vue.prototype.my_profile = my_profile;
        //                 Vue.prototype.teetee_info = teetee_info;
        //             }
        //         })
        // }
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
                loaded: false
            }
        },
        created() {
            this.$store.dispatch("load_my_profile").then(() => {
                this.loaded = true;
            });
        },
        router,
    });

    router.beforeEach((to, from, next) => {
        if (!store.state.IsCreated)
            next({ name: "create-profile" });
        else {
            // if (to.name === 'index') {
            //     Vue.prototype.load_my_profile().then(() => {
            //         next();
            //     });
            // } else {
            next();
            // }
        }
    });

} catch (e) {
    console.log(e);
}
