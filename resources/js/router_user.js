import Vue from 'vue';
import Router from "vue-router";

Vue.use(Router);

import login from './components/user/login.vue';
import register from './components/user/register.vue';

export const routes = [
    {
        path: '/login',
        component: login,
        name: 'login',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/register',
        component: register,
        name: 'register',
        meta: {
            KeepAlive: true
        }
    }
];

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})

export default router;
