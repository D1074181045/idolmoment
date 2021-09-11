import Vue from 'vue';

import store from '~/store';
import router from '~/router/user';

import Loading from '~/components/Loading';

try {
    require('~/plugins/bootstrap');
    require('~/plugins/axios');

    router.beforeEach((to, from, next) => {
        router.app.$nextTick(() => {
            router.app.$refs.loading.start();
        })

        document.title = to.meta.title;
        store.commit('error_clear');
        next();
    });

    router.afterEach((to, from, next) => {
        router.app.$nextTick(() => {
            router.app.$refs.loading.finish(true);
        })
    })

    new Vue({
        el: '#app',
        components: {
            loading: Loading
        },
        store,
        router
    });

} catch (e) {
    console.log(e);
}
