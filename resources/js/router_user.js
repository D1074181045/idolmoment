import Vue from 'vue';
import Router from "vue-router";

Vue.use(Router);

export const routes = [
    {
        path: '/login',
        component: () => import('./components/user/login.vue'),
        name: 'login',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/register',
        component: () => import('./components/user/register.vue'),
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
