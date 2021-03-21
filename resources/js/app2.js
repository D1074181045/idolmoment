import Vue from 'vue';
import router from './router_user.js';

class vue_global {
    static variables() {
        Vue.prototype.api_prefix = '/api/';
    };

    static methods() {

    }
}

try {
    require('./bootstrap');
    require('./axios');

    vue_global.variables();

    new Vue({
        el: '#app',
        router
    });

} catch (e) {
    console.log(e);
}
