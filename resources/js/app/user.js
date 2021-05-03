import Vue from 'vue';

import store from '../store';
import router from '../router/user';

try {
    require('../plugins/bootstrap');
    require('../plugins/axios');

    router.beforeEach((to, from, next) => {
        document.title = to.meta.title;
        store.commit('error_clear');
        next();
    });

    new Vue({
        el: '#app',
        store,
        router
    });

} catch (e) {
    console.log(e);
}
