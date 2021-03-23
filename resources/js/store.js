import Vue from 'vue';
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        error: {'status': 0, 'message': null},
        ban_type: {
            signature: { status: false, time: null },
            activity: { status: false, time: null },
            operating: { status: false, time: null },
            chat: { status: false, time: null }
            },
        profile: null,
        cool_down: null,
        teetee_info: null,
        IsCreated: false
    },
    mutations: {
        cool_down: function(state, type) {
            let time = new Date(state.cool_down[type]).getTime();

            if (time > Date.now()) {
                let Remaining_time = Math.ceil((time - Date.now()) / 1000);

                state.ban_type[type].status = true;
                state.ban_type[type].time = Remaining_time;

                const timeout = setInterval(() => {
                    Remaining_time = Math.ceil((time - Date.now()) / 1000);

                    state.ban_type[type].time = Remaining_time;

                    if (Remaining_time < 1) {
                        // elem[type.concat('_', 'disabled')] = false;
                        state.ban_type[type].status = false;
                        state.ban_type[type].time = null;
                        clearInterval(timeout);
                    }
                }, 1000)
            }
        },
        show_error: function (state, message) {
            state.error.status = true;
            state.error.message = message;

            setTimeout(() => {
                state.error.status = 0;
                state.error.message = null;
            }, 3000)
        },
        load_my_profile: function (state, res) {
            state.IsCreated = res.status;

            if (res.status) {
                state.cool_down = res.cool_down;
                state.profile = res.profile;
                state.teetee_info = res.teetee_info;
            }
        },

    },
    getters: {
        disabled_class: () => (status) => {
            return status ? 'is-invalid' : 'is-valid';
        },
        NumberFormat: () => (number, type = 'en-IN') => {
            switch (type) {
               case 'zh-TW' :
                   return new Intl.NumberFormat(type, {
                       notation: 'compact',
                       compactDisplay: "long"
                   }).format(number);
                default:
                    return new Intl.NumberFormat(type).format(number);
            }
        }
    },
    actions: {
        load_my_profile: function ({commit}) {
            return new Promise(function (resolve, reject) {
                window.axios.defaults.headers.common['Authorization'] = 'Bearer'.concat(' ', localStorage.token);

                axios.get(Vue.prototype.api_prefix.concat('my-profile'))
                    .then((res) => {
                        commit('load_my_profile', res)
                        resolve()
                    }).catch(() => {
                        reject()
                    })
            })
        }
    }
})

export default store;
