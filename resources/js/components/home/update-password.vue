<template>
    <div class="container" style="color: var(--form-control-color)">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: var(--form-control-bg-color)">
                    <div class="card-header">修改密碼</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">舊密碼</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" style="width: 100%" autocomplete="off" required autofocus
                                       :class="$store.getters.disabled_class(old_password_disabled)" v-model="old_password"
                                       v-on:input="ban_update_password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">新密碼</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" style="width: 100%" autocomplete="off" required autofocus
                                       :class="$store.getters.disabled_class(new_password_disabled)" v-model="new_password"
                                       v-on:input="ban_update_password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">確認新密碼</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" style="width: 100%" autocomplete="off" required autofocus
                                       :class="$store.getters.disabled_class(new_password_disabled)" v-model="new_password_confirm"
                                       v-on:input="ban_update_password">
                                <div style="font-size: 12px;margin: 4px 8px;">請介於8到32字元之間，且無特殊字元</div>
                            </div>
                        </div>
                        <button type="button" disabled class="btn btn-primary btn-block"
                                style="margin: 0 0;" v-on:click="to_update_password" :disabled="update_password_disabled">修改
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
import { msg } from '../../styles';
const legalityKey = new RegExp("^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9]+$");

export default {
    data() {
        return {
            old_password: "",
            new_password: "",
            new_password_confirm: "",
            new_password_disabled: true,
            old_password_disabled: true,
            update_password_disabled: true
        }
    },
    components:{
        msg
    },
    created() {
        document.title = "修改密碼";
    },
    computed:{
        error() {
            return this.$store.state.error;
        }
    },
    methods: {
        between: function (int, from, to) {
            return int >= from && int <= to;
        },
        ban_update_password: function () {
            if (!this.old_password.match(legalityKey)) {
                this.old_password_disabled = true;
            } else {
                this.old_password_disabled = !this.between(this.old_password.length, 8, 32);
            }

            if (this.new_password === this.new_password_confirm) {
                if (!this.new_password.match(legalityKey)) {
                    this.new_password_disabled = true;
                } else {
                    this.new_password_disabled = !this.between(this.new_password.length, 8, 32);
                }
            } else {
                this.new_password_disabled = true;
            }

            this.update_password_disabled = this.old_password_disabled || this.new_password_disabled;
        },
        to_update_password: function () {
            if (this.update_password_disabled)
                return;

            axios.patch(this.api_prefix.concat('update-password'), {
                old_password: this.old_password,
                new_password: this.new_password,
                new_password_confirm: this.new_password_confirm,
            }).then(({status, message}) => {
                if (status) {
                    this.$router.push({name: 'index'})
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
            });
        }
    }
}
</script>
