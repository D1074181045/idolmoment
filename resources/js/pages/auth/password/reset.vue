<template>
    <div class="card" v-on:keyup.enter="reset_password">
        <div class="card-header">
            重設密碼
            <LightSwitch />
        </div>

        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">電子郵件</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" autocomplete="off" required autofocus disabled
                            :value="email"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">密碼</label>

                <div class="col-md-6">
                    <input type="password" class="form-control" autocomplete="off" required maxlength="32"
                           :class="disabled_class(password_disabled)" v-model="password"
                           :type="pw_toggle(password_show).type" v-on:input="ban"
                           />
                    <div style="font-size: 12px;margin: 4px 8px;">請介於8到32字元之間</div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">確認密碼</label>

                <div class="col-md-6">
                    <input type="password" class="form-control" autocomplete="off" required maxlength="32"
                           :class="disabled_class(password_disabled)" v-model="password_confirmation"
                           :type="pw_toggle(password_show).type" v-on:input="ban"
                           />
                </div>

                <PasswordToggleButton
                    :click_event="password_toggle_button"
                    :show="password_show"
                    :id_name="'password_toggle_button'"
                />
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="button" disabled class="btn btn-primary"
                            :class="{ 'btn-loading':sending }"
                            v-on:click="reset_password" :disabled="send_reset_pwd_disabled"
                            >重設密碼
                    </button>
                    <button type="button" class="btn btn-dark"
                            v-on:click="back">返回</button>
                </div>
            </div>
        </div>

        <CardFooter :type="'alert-danger'" />
    </div>
</template>

<script>
import CardFooter from "../../../components/CardFooter";
import LightSwitch from "../../../components/LightSwitch";
import PasswordToggleButton from "../../../components/PasswordToggleButton";

import {mapGetters, mapMutations, mapState} from "vuex";

export default {
    data: function () {
        return {
            token: null,
            email: "",
            password: "",
            password_confirmation: "",
            password_show: false,
            password_disabled: true,
            send_reset_pwd_disabled: true,
            sending: false
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
    mounted() {
        let route = this.$route;
        this.email = route.query.email;
        this.token = route.params.token;
    },
    methods: {
        ...mapMutations([
            'show_error'
        ]),
        back: function () {
            this.$router.push({name: 'login'});
        },
        password_toggle_button: function (e) {
            this.password_show = e.target.checked;
        },
        ban: function () {
            this.password_disabled = this.password !== this.password_confirmation || !this.between(this.password.length, 8, 32);
            this.send_reset_pwd_disabled = this.password_disabled;
        },
        reset_password: function () {
            if (this.send_reset_pwd_disabled)
                return

            let url = this.api_prefix.concat('password/reset')
            this.send_reset_pwd_disabled = this.sending = true;

            axios.post(url, {
                token : this.token,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation
            }).then(() => {
                this.$router.push({name: 'login'});
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
                this.send_reset_pwd_disabled = false;
            }).finally(() => {
                this.sending = false;
            })
        }
    }
}
</script>
