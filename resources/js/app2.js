import Vue from 'vue';
import store from './store';
import router from './router_user.js';

try {
    require('./bootstrap');
    require('./axios');

    new Vue({
        el: '#app',
        store,
        router
    });

} catch (e) {
    console.log(e);
}
