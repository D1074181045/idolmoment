import Vue from 'vue';
import Router from "vue-router";

import store from '../store';
import routes from '../router/user';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})

try {
    require('../plugins/bootstrap');
    require('../plugins/axios');

    new Vue({
        el: '#app',
        store,
        router
    });

} catch (e) {
    console.log(e);
}
