import Vue from 'vue';
import router from './router_home.js';

let surplus_clear_cache = Date.now();
let IsCreated = false;

class vue_global {
    static variables() {
        Vue.prototype.api_prefix = '/api/';
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
            return 'img/characters/'.concat(img_file_name, '.jpg');
        }

        Vue.prototype.load_my_profile = (async_type = false) => {
            return $.get({
                url: 'api/'.concat('my-profile'),
                async: async_type,
                headers: {
                    'Authorization': 'Bearer'.concat(' ', localStorage.token)
                },
                success: ({status, cool_down, my_profile, teetee_info}) => {
                    if (status) {
                        Vue.prototype.cool_down = cool_down;
                        Vue.prototype.my_profile = my_profile;
                        Vue.prototype.teetee_info = teetee_info;

                        window.axios.defaults.headers.common['Authorization'] = 'Bearer'.concat(' ', localStorage.token);
                        IsCreated = true;
                    }
                }
            });
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
        created() {
            this.load_my_profile();
        },
        router,
    });

    router.beforeEach((to, from, next)=>{
        if (!IsCreated)
            next({ name:"create-profile" });
        else
            next();
    });

} catch (e) {
    console.log(e);
}
