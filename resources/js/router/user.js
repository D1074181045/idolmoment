import login from '../components/user/login.vue';
import register from '../components/user/register.vue';

export const routes = [
    {
        path: '/login',
        component: login,
        name: 'login',
    },
    {
        path: '/register',
        component: register,
        name: 'register',
    }
];

export default routes;
