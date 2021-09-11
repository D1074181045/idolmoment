<template>
    <div class="card" v-on:keyup.enter="login_click">
        <div class="card-header">
            Login
            <LightSwitch />
        </div>

        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">使用者名稱</label>

                <div class="col-md-6">
                    <input type="text" class="form-control" autocomplete="off" autofocus maxlength="15"
                           :class="disabled_class(username_disabled)" v-on:input="ban_login"
                           v-model="username"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">使用者密碼</label>

                <div class="col-md-6">
                    <input class="form-control" required autocomplete="off" maxlength="32"
                           :class="disabled_class(password_disabled)" v-on:input="ban_login"
                           :type="pw_toggle(password_show).type" v-model="password">
                </div>

                <PasswordToggleButton
                    :click_event="password_toggle_button"
                    :show="password_show"
                    :id_name="'password_toggle_button'"
                />
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
                    <button type="button" disabled class="btn btn-primary" :class="{ 'btn-loading':logging }"
                            :disabled="login_disabled" v-on:click="login_click" ref="login">登入
                    </button>
                    <button type="button" class="btn btn-dark" v-on:click="to_register">前往註冊</button>
                    <button type="button" class="btn btn-link" v-on:click="to_forgot_pwd">忘記密碼</button>
                </div>
            </div>


        </div>

        <CardFooter :type="'alert-danger'" />
    </div>
</template>

<script>
import CardFooter from '~/components/CardFooter';
import LightSwitch from '~/components/LightSwitch';
import PasswordToggleButton from "~/components/PasswordToggleButton";

import {mapGetters, mapMutations, mapState} from "vuex";

export default {
    data: function () {
        return {
            username: "",
            password: "",
            autologin: false,
            password_show: false,
            username_disabled: true,
            password_disabled: true,
            login_disabled: true,
            logging: false
        }
    },
    components: {
        CardFooter,
        LightSwitch,
        PasswordToggleButton
    },
    computed: {
        ...mapState([
            'api_prefix'
        ]),
        ...mapGetters([
            'pw_toggle',
            'disabled_class'
        ])
    },
    methods: {
        ...mapMutations([
            'show_error'
        ]),
        password_toggle_button: function (e) {
            this.password_show = e.target.checked;
        },
        ban_login: function () {
            this.username_disabled = this.username.length <= 0;
            this.password_disabled = this.password.length <= 0;
            this.login_disabled = this.username_disabled || this.password_disabled;
        },
        to_register: function () {
            this.$router.push({name: 'register'});
        },
        to_forgot_pwd: function () {
            this.$router.push({name: 'forgot.password'});
        },
        login_click: function () {
            if (this.login_disabled)
                return

            this.logging = this.login_disabled = true;
            const url = this.api_prefix.concat('login');
            this.$refs.login.focus();

            axios.get(url, {
                params: {
                    username: this.username,
                    password: this.password,
                    autologin: this.autologin
                }
            }).then((res) => {
                if (res.status) {
                    localStorage.token = res.token;
                    document.location.href = "/";
                }
            }).catch((err) => {
                if (err.status === 422) {
                    let s = "";
                    let errors = err.data.errors;

                    Object.keys(errors).forEach((error) => {
                        s += errors[error] + '\n';
                    });

                    this.show_error(s);
                } else {
                    this.show_error(err.data.message);
                }
                this.password = "";
                this.autologin = this.logging = false;
                this.ban_login();
            });
        }
    }
}
</script>
