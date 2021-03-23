<template>
    <div class="col-md-8" v-on:keyup.enter="login_click">
        <div class="card">
            <div class="card-header">Login</div>

            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">使用者名稱</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" autocomplete="off" autofocus
                               :class="$store.getters.disabled_class(username_disabled)" v-on:input="ban_login" v-model="username" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">使用者密碼</label>

                    <div class="col-md-6">
                        <input class="form-control" required autocomplete="off"
                               :class="$store.getters.disabled_class(password_disabled)" v-on:input="ban_login" :type="pw_type()" v-model="password">
                    </div>
                    <button type="button" class="show-hide-toggle-button" title="顯示密碼" tabindex="-1"
                            :class="pw_class()" :title="pw_title()" v-on:click="password_toggle_button">
                    </button>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="autologin">
                            <label class="form-check-label">自動登入</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="button" disabled class="btn btn-primary"
                                :disabled="login_disabled" v-on:click="login_click" ref="login">登入</button>
                        <button type="button" class="btn btn-dark" v-on:click="to_register">前往註冊</button>
                    </div>
                </div>
            </div>

            <div class="card-footer" v-if="error.status">
                <msg style="text-align: center">{{ error.message }}</msg>
            </div>
        </div>
    </div>
</template>

<script>
import { msg } from '../../styles';

export default {
    data() {
        return {
            username: "",
            password: "",
            autologin: false,
            pw_is_show: false,
            username_disabled: true,
            password_disabled: true,
            login_disabled: true,
        }
    },
    components:{
        msg
    },
    computed:{
        error() {
            return this.$store.state.error;
        }
    },
    methods: {
        pw_class: function () {
            return this.pw_is_show ? 'show-pw' : 'hide-pw';
        },
        pw_title: function () {
            return this.pw_is_show ? '顯示密碼' : '隱藏密碼';
        },
        pw_type: function () {
            return this.pw_is_show ? 'text' : 'password';
        },
        password_toggle_button: function () {
            this.pw_is_show = !this.pw_is_show;
        },
        ban_login: function () {
            this.username_disabled = this.username.length <= 0;
            this.password_disabled = this.password.length <= 0;
            this.login_disabled = this.username_disabled || this.password_disabled;
        },
        to_register: function (){
            this.$router.push({ name:'register' });
        },
        login_click: function () {
            if (this.login_disabled)
                return

            this.$refs.login.focus();

            axios.get(this.api_prefix.concat('login'), {
                params: {
                    username: this.username,
                    password: this.password,
                    autologin: this.autologin
                }
            }).then(({status, message, token}) => {
                if (status) {
                    localStorage.token = token;
                    document.location.href = "/";
                }
                else {
                    this.password = "";
                    this.password_disabled = true;
                    this.autologin = false;
                    this.login_disabled = true;

                    this.$store.commit("show_error", message);
                }
            }).catch((err) => {
                if (err.status === 422) {
                    let s = "";
                    let errors = err.data.errors;

                    Object.keys(errors).forEach((error) => {
                        s += errors[error] + '\n';
                    });

                    this.$store.commit("show_error", s);
                } else {
                    this.$store.commit("show_error", "發生錯誤: " + err.statusText);
                }
            });
        },
        login_enter: function () {
            document.onkeydown = (e) => {
                console.log(e);

                if (e.keyCode === 13)
                    this.login_click();
            }
        }
    }
}
</script>
