import Vue from 'vue';
import Router from "vue-router";

import login from '../pages/user/login.vue';
import register from '../pages/user/register.vue';
import email from '../pages/auth/password/email.vue';
import reset from '../pages/auth/password/reset.vue';
import verify from "../pages/auth/verification/verify";

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
    },
    {
        path: '/password/reset',
        component: email,
        name: 'forgot.password',
        meta: {
            title: '忘記密碼',
        }
    },
    {
        path: '/password/reset/:token',
        component: reset,
        name: 'reset.password',
        meta: {
            title: '重設密碼',
        }
    },
    {
        path: '/email/verify/:id/:hash',
        component: verify,
        name: 'verification.verify',
        meta: {
            title: '郵件驗證',
        }
    }
];

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})

export default router;
