<template>
    <div class="col-md-8" v-on:keyup.enter="register_click">
        <div class="card">
            <div class="card-header">Register</div>

            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">使用者名稱</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" autocomplete="off" required autofocus
                               :class="$store.getters.disabled_class(username_disabled)" v-on:input="ban_register"
                               v-model="username"/>
                        <div style="font-size: 12px;margin: 4px 8px;">請介於5到15字元之間</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">密碼</label>

                    <div class="col-md-6">
                        <input type="password" class="form-control" autocomplete="off" required
                               :class="$store.getters.disabled_class(password_disabled)" v-on:input="ban_register"
                               :type="pw_type()" v-model="password"/>
                        <div style="font-size: 12px;margin: 4px 8px;">請介於8到32字元之間</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">確認密碼</label>

                    <div class="col-md-6">
                        <input type="password" class="form-control" autocomplete="off" required
                               :class="$store.getters.disabled_class(password_disabled)" v-on:input="ban_register"
                               :type="pw_type()" v-model="password_confirmation"/>
                    </div>
                    <div class="show-hide-toggle-button">
                        <input type="checkbox" id="password-toggle-button" v-on:click="password_toggle_button">
                        <label for="password-toggle-button" :title="pw_title()" style="margin-bottom: 0;">Toggle</label>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button id="register" name="register" type="button" disabled class="btn btn-primary"
                                :class="{ 'btn-loading':registering }"
                                :disabled="register_disabled" v-on:click="register_click" ref="register">註冊
                        </button>
                        <button id="back" name="back" type="button" class="btn btn-dark" v-on:click="back">返回</button>
                    </div>
                </div>
            </div>

            <CardFooter :error="error" :type="'alert-danger'"></CardFooter>
        </div>
    </div>
</template>

<script>
import CardFooter from "../../components/CardFooter";

export default {
    data() {
        return {
            username: "",
            password: "",
            password_confirmation: "",
            pw_is_show: false,
            username_disabled: true,
            password_disabled: true,
            register_disabled: true,
            registering: false
        }
    },
    components: {
        CardFooter
    },
    computed: {
        error: function () {
            return this.$store.state.error;
        },
        api_prefix: function () {
            return this.$store.state.api_prefix
        },
    },
    activated() {
        document.title = "註冊";
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

            this.registering = true;
            const url = this.api_prefix.concat('register');
            this.$refs.register.focus();

            axios.post(url, {
                username: this.username,
                password: this.password,
                password_confirmation: this.password_confirmation
            }).then(({status, message}) => {
                if (status) {
                    this.username = "";
                    this.password = "";
                    this.password_confirmation = "";
                    this.username_disabled = true;
                    this.password_disabled = true;
                    this.$router.push({name: 'login'});
                } else {
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
            }).finally(() => {
                this.registering = false;
            });
        },
    }
}
</script>
