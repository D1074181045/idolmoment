import Vue from 'vue';
import Router from "vue-router";

import index from '../pages/home/index.vue';
import active_idol from '../pages/home/active-idol.vue';
import profile from '../pages/home/profile.vue';
import rebirth from '../pages/home/rebirth.vue';
import chatroom from '../pages/home/chatroom.vue';
import create_profile from '../pages/home/create-profile.vue';
import update_password from '../pages/home/update-password.vue';
import verify from '../pages/auth/verify';

Vue.use(Router);

const routes = [
    {
        path: '/',
        component: index,
        name: 'index',
        meta: {
            title: '我的偶像',
            KeepAlive: false
        }
    },
    {
        path: '/active-idol',
        component: active_idol,
        name: 'active-idol',
        meta: {
            title: '活耀偶像',
            KeepAlive: true
        }
    },
    {
        path: '/profile/:name',
        component: profile,
        name: 'profile',
        meta: {
            title: '偶像資訊',
            KeepAlive: true
        }
    },
    {
        path: '/rebirth',
        component: rebirth,
        name: 'rebirth',
        meta: {
            title: '偶像轉生',
            KeepAlive: true
        }
    },
    {
        path: '/chatroom',
        component: chatroom,
        name: 'chatroom',
        meta: {
            title: '聊天室',
            KeepAlive: true
        }
    },
    {
        path: '/create-profile',
        component: create_profile,
        name: 'create-profile',
        meta: {
            title: '創建偶像',
        }
    },
    {
        path: '/update-password',
        component: update_password,
        name: 'update-password',
        meta: {
            title: '修改密碼',
        }
    },
    {
        path: '/email/verify',
        component: verify,
        name: 'verify',
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
