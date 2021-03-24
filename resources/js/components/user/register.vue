<template>
    <div class="col-md-8" v-on:keyup.enter="register_click">
        <div class="card">
            <div class="card-header">Register</div>

            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">使用者名稱</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" autocomplete="off" required autofocus
                               :class="$store.getters.disabled_class(username_disabled)" v-on:input="ban_register" v-model="username" />
                        <div style="font-size: 12px;margin: 4px 8px;">請介於5到15字元之間，且無特殊字元</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">密碼</label>

                    <div class="col-md-6">
                        <input type="password" class="form-control" autocomplete="off" required
                               :class="$store.getters.disabled_class(password_disabled)" v-on:input="ban_register" :type="pw_type()" v-model="password" />
                        <div style="font-size: 12px;margin: 4px 8px;">請介於8到32字元之間，且無特殊字元</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">確認密碼</label>

                    <div class="col-md-6">
                        <input type="password" class="form-control" autocomplete="off" required
                               :class="$store.getters.disabled_class(password_disabled)" v-on:input="ban_register" :type="pw_type()" v-model="password_confirm"/>
                    </div>
                    <div class="show-hide-toggle-button">
                        <input type="checkbox" id="password-toggle-button" v-on:click="password_toggle_button">
                        <label for="password-toggle-button" :title="pw_title()" style="margin-bottom: 0;">Toggle</label>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button id="register" name="register" type="button" disabled class="btn btn-primary"
                                :disabled="register_disabled" v-on:click="register_click" ref="register">註冊</button>
                        <button id="back" name="back" type="button" class="btn btn-dark" v-on:click="back">返回</button>
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
            password_confirm: "",
            pw_is_show: false,
            username_disabled: true,
            password_disabled: true,
            register_disabled: true,
        }
    },
    components:{
        msg
    },
    computed:{
        error() {
            return this.$store.state.error;
        },
    },
    methods: {
        pw_title: function () {
            return this.pw_is_show ? '顯示密碼' : '隱藏密碼';
        },
        pw_type: function () {
            return this.pw_is_show ? 'text' : 'password';
        },
        password_toggle_button: function (e) {
            this.pw_is_show = e.target.checked;
        },
        between: function (int, from, to) {
            return int >= from && int <= to;
        },
        ban_register: function () {
            const legalityKey = new RegExp("^[0-9A-Za-z]+$");

            if (!this.username.match(legalityKey)) {
                this.username_disabled = true;
            } else {
                this.username_disabled = !this.between(this.username.length, 5, 15);
            }

            if (this.password === this.password_confirm) {
                if (!this.password.match(legalityKey)) {
                    this.password_disabled = true;
                } else {
                    this.password_disabled = !this.between(this.password.length, 8, 32);
                }
            } else {
                this.password_disabled = true;
            }

            this.register_disabled = this.username_disabled || this.password_disabled;
        },
        back: function (){
            this.$router.push({ name:'login' });
        },
        register_click: function () {
            if (this.register_disabled)
                return

            this.$refs.register.focus();

            axios.post(this.api_prefix.concat('register'), {
                username: this.username,
                password: this.password,
                password_confirm: this.password_confirm
            }).then(({status, message}) => {
                if (status) {
                    this.username = "";
                    this.password = "";
                    this.password_confirm = "";
                    this.username_disabled = true;
                    this.password_disabled = true;
                    this.$router.push({name: 'login'});
                }
                else {
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
    }
}
</script>
