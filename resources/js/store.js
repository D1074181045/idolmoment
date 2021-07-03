import Vue from 'vue';
import Vuex from 'vuex'

Vue.use(Vuex)

function String2DateTime(String) {
    const arr = String.split(/[- :]/);
    return new Date(
        parseInt(arr[0]),
        parseInt(arr[1]) - 1,
        parseInt(arr[2]),
        parseInt(arr[3]),
        parseInt(arr[4]),
        parseInt(arr[5])
    );
}

const store = new Vuex.Store({
    state: {
        error: {'status': 0, 'message': null},
        ban_type: {
            signature: {status: false, time: null},
            activity: {status: false, time: null},
            cooperation: {status: false, time: null},
            operating: {status: false, time: null},
            chat: {status: false, time: null},
        },
        like_num: null,
        dislike_num: null,
        email: null,
        email_verify: null,
        profile: null,
        cool_down: null,
        teetee_info: null,
        personal_profile_status: false,
        api_prefix: '/api/',
        prompt_count: 0,
        loading: null
    },
    mutations: {
        error_clear: function (state) {
            state.error.status = 0;
            state.error.message = null;
        },
        show_error: function (state, message) {
            state.error.status = true;
            state.error.message = message;

            if (this.error_clear_timeout)
                clearTimeout(this.error_clear_timeout);

            this.error_clear_timeout = setTimeout(() => {
                store.commit('error_clear');
            }, 3000)
        },
        load_my_profile: function (state, res) {
            state.personal_profile_status = res.status;

            if (res.status) {
                state.like_num = res.like_num;
                state.dislike_num = res.dislike_num;
                state.email = res.email;
                state.email_verify = res.email_verify;
                state.cool_down = res.cool_down;
                state.profile = res.profile;
                state.teetee_info = res.teetee_info;
            }
        }
    },
    getters: {
        characters_img_path: () => (img_file_name, img_type = 'jpg') => 'https://f000.backblazeb2.com/file/idolmoment/characters/'.concat(img_file_name).concat('.', img_type),
        disabled_class: () => status => status ? 'is-invalid' : 'is-valid',
        pw_toggle: () => show => show ? {title: '顯示密碼', type: 'text'} : {title: '隱藏密碼', type: 'password'},
        NumberFormat: () => (number, type = 'en-IN') => {
            if (number === undefined || number === null)
                return null;

            switch (type) {
                case 'zh-TW' :
                    return new Intl.NumberFormat(type, {
                        notation: 'compact',
                        compactDisplay: "long"
                    }).format(number);
                default:
                    return new Intl.NumberFormat().format(number);
            }
        },
        between: () => (int, from, to) => int >= from && int <= to
    },
    actions: {
        load_my_profile: function ({commit, state}) {
            const url = this.state.api_prefix.concat('my-profile');

            return new Promise(function (resolve, reject) {
                axios.get(url)
                    .then((res) => {
                        commit('load_my_profile', res);
                        resolve();
                    }).catch(() => {
                        state.personal_profile_status = false;
                        reject();
                    })
            })
        },
        cool_down_rec: function ({state}, type) {
            return new Promise(function (resolve) {
                let type_name = type.name;

                if (type.time)
                    state.cool_down[type_name] = type.time;

                if (state.cool_down[type_name]) {
                    let time = new Date(state.cool_down[type_name]).getTime();
                    if (!time)
                        time = new Date(String2DateTime(state.cool_down[type_name])).getTime();

                    if (time > Date.now()) {
                        let Remaining_time = Math.ceil((time - Date.now()) / 1000);

                        state.ban_type[type_name].status = true;
                        state.ban_type[type_name].time = Remaining_time;
                        console.log("DEBUG", type_name.concat("冷卻開始"), state.ban_type[type_name]);

                        const timeout = setInterval(() => {
                            Remaining_time = Math.ceil((time - Date.now()) / 1000);
                            state.ban_type[type_name].time = Remaining_time;

                            if (Remaining_time < 1) {
                                state.ban_type[type_name].status = false;
                                state.ban_type[type_name].time = null;
                                console.log("DEBUG", type_name.concat("冷卻結束"));
                                clearInterval(timeout);
                                resolve();
                            }
                        }, 1000)
                    } else {
                        resolve();
                    }
                } else {
                    resolve();
                }
            })
        }
    }
})

export default store;
