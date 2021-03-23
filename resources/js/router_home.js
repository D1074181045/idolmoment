import Vue from 'vue';
import Router from "vue-router";

Vue.use(Router);

export const routes = [
    {
        path: '/',
        component: () => import('./components/home/index.vue'),
        name: 'index',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/active-idol',
        component: () => import('./components/home/active-idol.vue'),
        name: 'active-idol',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/profile/:name',
        component: () => import('./components/home/profile.vue'),
        name: 'profile',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/rebirth',
        component: () => import('./components/home/rebirth.vue'),
        name: 'rebirth',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/chatroom',
        component: () => import('./components/home/chatroom.vue'),
        name: 'chatroom',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/create-profile',
        component: () => import('./components/home/create-profile.vue'),
        name: 'create-profile',
    }
];

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})

export default router;
