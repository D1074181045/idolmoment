<template>
    <div class="card" v-on:keyup.enter="register_click">
        <div class="card-header">
            Register
            <LightSwitch />
        </div>

        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">使用者名稱</label>

                <div class="col-md-6">
                    <input type="text" class="form-control" autocomplete="off" required autofocus maxlength="15"
                           :class="disabled_class(username_disabled)" v-on:input="ban_register"
                           v-model="username"/>
                    <div style="font-size: 12px;margin: 4px 8px;">請介於5到15字元之間</div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">密碼</label>

                <div class="col-md-6">
                    <input type="password" class="form-control" autocomplete="off" required maxlength="32"
                           :class="disabled_class(password_disabled)" v-on:input="ban_register"
                           :type="pw_toggle(password_show).type" v-model="password"/>
                    <div style="font-size: 12px;margin: 4px 8px;">請介於8到32字元之間</div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">確認密碼</label>

                <div class="col-md-6">
                    <input type="password" class="form-control" autocomplete="off" required maxlength="32"
                           :class="disabled_class(password_disabled)" v-on:input="ban_register"
                           :type="pw_toggle(password_show).type" v-model="password_confirmation"/>
                </div>

                <PasswordToggleButton
                    :click_event="password_toggle_button"
                    :show="password_show"
                    :id_name="'password_toggle_button'"
                />
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button id="register" name="register" type="button" disabled class="btn btn-primary"
                            :class="{ 'btn-loading':registering }"
                            :disabled="register_disabled" v-on:click="register_click" ref="register">註冊
                    </button>
                    <button type="button" class="btn btn-dark" v-on:click="back">返回</button>
                </div>
            </div>
        </div>

        <CardFooter :type="'alert-danger'" />
    </div>
</template>

<script>
import CardFooter from "../../components/CardFooter";
import LightSwitch from "../../components/LightSwitch";
import PasswordToggleButton from "../../components/PasswordToggleButton";

import {mapGetters, mapMutations, mapState} from "vuex";

export default {
    data: function () {
        return {
            username: "",
            password: "",
            password_confirmation: "",
            password_show: false,
            username_disabled: true,
            password_disabled: true,
            register_disabled: true,
            registering: false
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
            'between',
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
        ban_register: function () {
            this.username_disabled = !this.between(this.username.length, 5, 15);
            this.password_disabled = this.password !== this.password_confirmation || !this.between(this.password.length, 8, 32);
            this.register_disabled = this.username_disabled || this.password_disabled;
        },
        back: function () {
            this.$router.push({name: 'login'});
        },
        register_click: function () {
            if (this.register_disabled)
                return

            this.registering = this.register_disabled = true;
            const url = this.api_prefix.concat('register');
            this.$refs.register.focus();

            axios.post(url, {
                username: this.username,
                password: this.password,
                password_confirmation: this.password_confirmation
            }).then((res) => {
                if (res.status) {
                    this.$router.push({name: 'login'});
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
                this.username = this.password = this.password_confirmation = "";
                this.registering = false;
                this.ban_register();
            })
        },
    }
}
</script>
