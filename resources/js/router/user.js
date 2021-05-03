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
        meta: {
            title: '登入',
        }
    },
    {
        path: '/register',
        component: register,
        name: 'register',
        meta: {
            title: '註冊',
        }
    }
];

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})

export default router;
