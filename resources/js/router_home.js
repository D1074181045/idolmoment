import Vue from 'vue';
import Router from "vue-router";

Vue.use(Router);

import index from './components/home/index.vue';
import active_idol from './components/home/active-idol.vue';
import rebirth from './components/home/rebirth.vue';
import chatroom  from './components/home/chatroom.vue';
import create_profile  from './components/home/create-profile.vue';

export const routes = [
    {
        path: '/',
        component: index,
        name: 'index',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/active-idol',
        component: active_idol,
        name: 'active-idol',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/rebirth',
        component: rebirth,
        name: 'rebirth',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/chatroom',
        component: chatroom,
        name: 'chatroom',
        meta: {
            KeepAlive: true
        }
    },
    {
        path: '/create-profile',
        component: create_profile,
        name: 'create-profile',
    }
];

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})

export default router;
