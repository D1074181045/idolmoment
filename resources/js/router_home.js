import Vue from 'vue';
import Router from "vue-router";

Vue.use(Router);

export const routes = [
    {
        path: '/',
        component: () => import('./components/home/index.vue'),
        name: 'index',
        meta: {
            title: '我的偶像',
            KeepAlive: true
        }
    },
    {
        path: '/active-idol',
        component: () => import('./components/home/active-idol.vue'),
        name: 'active-idol',
        meta: {
            title: '活耀偶像',
            KeepAlive: true
        }
    },
    {
        path: '/profile/:name',
        component: () => import('./components/home/profile.vue'),
        name: 'profile',
        meta: {
            title: '偶像個人資料',
            KeepAlive: true
        }
    },
    {
        path: '/rebirth',
        component: () => import('./components/home/rebirth.vue'),
        name: 'rebirth',
        meta: {
            title: '偶像轉生',
            KeepAlive: true
        }
    },
    {
        path: '/chatroom',
        component: () => import('./components/home/chatroom.vue'),
        name: 'chatroom',
        meta: {
            title: '聊天室',
            KeepAlive: true
        }
    },
    {
        path: '/create-profile',
        component: () => import('./components/home/create-profile.vue'),
        name: 'create-profile',
        meta: {
            title: '創建偶像',
        }
    },
    {
        path: '/update-password',
        component: () => import('./components/home/update-password.vue'),
        name: 'update-password',
        meta: {
            title: '修改密碼',
        }
    }
];

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes
})

export default router;
