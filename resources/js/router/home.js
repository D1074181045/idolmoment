import index from '../components/home/index.vue';
import active_idol from '../components/home/active-idol.vue';
import profile from '../components/home/profile.vue';
import rebirth from '../components/home/rebirth.vue';
import chatroom from '../components/home/chatroom.vue';
import create_profile from '../components/home/create-profile.vue';
import update_password from '../components/home/update-password.vue';

export const routes = [
    {
        path: '/',
        component: index,
        name: 'index',
        meta: {
            title: '我的偶像',
            KeepAlive: true
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
            title: '偶像個人資料',
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
    }
];

export default routes;
