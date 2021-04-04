<template>
    <div class="container" style="color: var(--form-control-color)">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: var(--form-control-bg-color)">
                    <div class="card-header">修改密碼</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">舊密碼</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" style="width: 100%" autocomplete="off"
                                       required autofocus
                                       :class="$store.getters.disabled_class(old_password_disabled)"
                                       v-model="old_password"
                                       v-on:input="ban_update_password" :type="pw_type(old_password_show)">
                            </div>
                            <div class="show-hide-toggle-button">
                                <input type="checkbox" id="old_password_toggle_button"
                                       v-on:click="old_password_toggle_button">
                                <label for="old_password_toggle_button" :title="pw_title(old_password_show)"
                                       style="margin-bottom: 0;">Toggle</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">新密碼</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" style="width: 100%" autocomplete="off"
                                       required autofocus
                                       :class="$store.getters.disabled_class(new_password_disabled)"
                                       v-model="new_password"
                                       v-on:input="ban_update_password" :type="pw_type(new_password_show)">
                                <div style="font-size: 12px;margin: 4px 8px;">請介於8到32字元之間</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">確認新密碼</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" style="width: 100%" autocomplete="off"
                                       required autofocus
                                       :class="$store.getters.disabled_class(new_password_disabled)"
                                       v-model="new_password_confirm"
                                       v-on:input="ban_update_password" :type="pw_type(new_password_show)">
                            </div>
                            <div class="show-hide-toggle-button">
                                <input type="checkbox" id="new_password_toggle_button"
                                       v-on:click="new_password_toggle_button">
                                <label for="new_password_toggle_button" :title="pw_title(new_password_show)"
                                       style="margin-bottom: 0;">Toggle</label>
                            </div>
                        </div>
                        <button type="button" disabled class="btn btn-primary btn-block"
                                :class="{ 'btn-loading':updating }"
                                style="margin: 0 0;" v-on:click="to_update_password"
                                :disabled="update_password_disabled">修改
                        </button>
                    </div>

                    <div class="card-footer" v-if="error.status">
                        <msg style="text-align: center">{{ error.message }}</msg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {msg} from '../../styles';

export default {
    data() {
        return {
            old_password: "",
            new_password: "",
            new_password_confirm: "",
            new_password_disabled: true,
            old_password_disabled: true,
            update_password_disabled: true,
            old_password_show: false,
            new_password_show: false,
            updating: false
        }
    },
    components: {
        msg
    },
    created() {
        document.title = "修改密碼";
    },
    computed: {
        error: function () {
            return this.$store.state.error;
        },
        api_prefix: function () {
            return this.$store.state.api_prefix
        },
    },
    methods: {
        pw_title: function (show) {
            return show ? '顯示密碼' : '隱藏密碼';
        },
        pw_type: function (show) {
            return show ? 'text' : 'password';
        },
        old_password_toggle_button: function (e) {
            this.old_password_show = e.target.checked;
        },
        new_password_toggle_button: function (e) {
            this.new_password_show = e.target.checked;
        },
        between: function (int, from, to) {
            return int >= from && int <= to;
        },
        ban_update_password: function () {
            this.old_password_disabled = !this.between(this.old_password.length, 8, 32);
            this.new_password_disabled = this.new_password !== this.new_password_confirm || !this.between(this.new_password.length, 8, 32);
            this.update_password_disabled = this.old_password_disabled || this.new_password_disabled;
        },
        to_update_password: function () {
            if (this.update_password_disabled)
                return;

            this.updating = true;
            const url = this.api_prefix.concat('update-password');

            axios.patch(url, {
                old_password: this.old_password,
                new_password: this.new_password,
                new_password_confirm: this.new_password_confirm,
            }).then(({status, message}) => {
                if (status) {
                    this.$router.push({name: 'index'}).catch(() => {
                    });
                } else {
                    this.$store.commit("show_error", message);
                    this.update_password_disabled = false;
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
                    this.$store.commit("show_error", "發生錯誤: " + err.status);
                }
                this.update_password_disabled = false;
            }).finally(() => {
                this.updating = false;
            });
        }
    }
}
</script>
