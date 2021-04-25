import Vue from 'vue';
import Router from "vue-router";

import login from '../pages/user/login.vue';
import register from '../pages/user/register.vue';

Vue.use(Router);

const routes = [
    {
        path: '/login',
        component: login,
        name: 'login',
    },
    {
        path: '/register',
        component: register,
        name: 'register',
    }
];

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})

export default router;
