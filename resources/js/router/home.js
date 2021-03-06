import Vue from 'vue';
import Router from "vue-router";

import index from '../pages/home/index.vue';
import active_idol from '../pages/home/active-idol.vue';
import profile from '../pages/home/profile.vue';
import rebirth from '../pages/home/rebirth.vue';
import chatroom from '../pages/home/chatroom.vue';
import create_profile from '../pages/home/create-profile.vue';
import update_password from '../pages/home/update-password.vue';
import send from '../pages/auth/verification/send.vue';

Vue.use(Router);

const routes = [
    {
        path: '/',
        component: index,
        name: 'index',
        meta: {
            title: '我的偶像 - 偶像時刻',
            KeepAlive: false
        }
    },
    {
        path: '/active-idol',
        component: active_idol,
        name: 'active-idol',
        meta: {
            title: '活耀偶像 - 偶像時刻',
            KeepAlive: true
        }
    },
    {
        path: '/profile/:name',
        component: profile,
        name: 'profile',
        meta: {
            title: '偶像資訊 - 偶像時刻',
            KeepAlive: true
        }
    },
    {
        path: '/rebirth',
        component: rebirth,
        name: 'rebirth',
        meta: {
            title: '偶像轉生 - 偶像時刻',
            KeepAlive: true
        }
    },
    {
        path: '/chatroom',
        component: chatroom,
        name: 'chatroom',
        meta: {
            title: '聊天室 - 偶像時刻',
            KeepAlive: true
        }
    },
    {
        path: '/create-profile',
        component: create_profile,
        name: 'create-profile',
        meta: {
            title: '創建偶像 - 偶像時刻',
        }
    },
    {
        path: '/password/update',
        component: update_password,
        name: 'update.password',
        meta: {
            title: '修改密碼 - 偶像時刻',
        }
    },
    {
        path: '/email/send',
        component: send,
        name: 'verification.send',
        meta: {
            title: '郵件驗證 - 偶像時刻',
        }
    },
];

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})

export default router;
