import Vue from 'vue';
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        error: {'status': 0, 'message': null},
        profile: null,
        cool_down: null,
        teetee_info: null,
        IsCreated: false
    },
    mutations: {
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
        }
    },
    actions: {
        load_my_profile({commit}) {
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
