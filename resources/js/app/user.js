import Vue from 'vue';

import store from '../store';
import router from '../router/user';

try {
    require('../plugins/bootstrap');
    require('../plugins/axios');

    new Vue({
        el: '#app',
        store,
        router
    });

    router.beforeEach((to, from, next) => {
        store.commit('error_clear');

        next();
    });

} catch (e) {
    console.log(e);
}
