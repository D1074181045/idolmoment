<template>
    <div class="container" style="color: var(--form-control-color)" v-on:keyup.enter="update_password">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: var(--form-control-bg-color)">
                    <div class="card-header">修改密碼</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">舊密碼</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" style="width: 100%" autocomplete="off" maxlength="32"
                                       required autofocus
                                       :class="disabled_class(old_password_disabled)"
                                       v-model="old_password"
                                       v-on:input="ban_update_password" :type="pw_toggle(old_password_show).type"
                                />
                            </div>
                            <PasswordToggleButton
                                :click_event="old_password_toggle_button"
                                :show="old_password_show"
                                :id_name="'old_password_toggle_button'"
                            />
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">新密碼</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" style="width: 100%" autocomplete="off" maxlength="32"
                                       required autofocus
                                       :class="disabled_class(new_password_disabled)"
                                       v-model="new_password"
                                       v-on:input="ban_update_password" :type="pw_toggle(new_password_show).type"
                                />
                                <div style="font-size: 12px;margin: 4px 8px;">請介於8到32字元之間</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">確認新密碼</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" style="width: 100%" autocomplete="off" maxlength="32"
                                       required autofocus
                                       :class="disabled_class(new_password_disabled)"
                                       v-model="new_password_confirmation"
                                       v-on:input="ban_update_password" :type="pw_toggle(new_password_show).type">
                            </div>
                            <PasswordToggleButton
                                :click_event="new_password_toggle_button"
                                :show="new_password_show"
                                :id_name="'new_password_toggle_button'"
                            />
                        </div>
                        <button type="button" disabled class="btn btn-primary btn-block"
                                :class="{ 'btn-loading':updating }"
                                style="margin: 0 0;" v-on:click="update_password"
                                :disabled="update_password_disabled">修改
                        </button>
                    </div>

                    <CardFooter :type="'alert-danger'" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CardFooter from "~/components/CardFooter";
import PasswordToggleButton from "~/components/PasswordToggleButton";

import {mapGetters, mapState} from "vuex";

export default {
    data: function () {
        return {
            old_password: "",
            new_password: "",
            new_password_confirmation: "",
            new_password_disabled: true,
            old_password_disabled: true,
            update_password_disabled: true,
            old_password_show: false,
            new_password_show: false,
            updating: false
        }
    },
    components: {
        CardFooter,
        PasswordToggleButton
    },
    computed: {
        ...mapState([
            'api_prefix',
            'loading'
        ]),
        ...mapGetters([
            'between',
            'pw_toggle',
            'disabled_class'
        ])
    },
    methods: {
        old_password_toggle_button: function (e) {
            this.old_password_show = e.target.checked;
        },
        new_password_toggle_button: function (e) {
            this.new_password_show = e.target.checked;
        },
        ban_update_password: function () {
            this.old_password_disabled = !this.between(this.old_password.length, 8, 32);
            this.new_password_disabled = this.new_password !== this.new_password_confirmation || !this.between(this.new_password.length, 8, 32) || this.old_password === this.new_password;
            this.update_password_disabled = this.old_password_disabled || this.new_password_disabled;
        },
        update_password: function () {
            if (this.update_password_disabled || this.old_password === this.new_password)
                return;

            const url = this.api_prefix.concat('update-password');
            this.updating = true;

            axios.post(url, {
                old_password: this.old_password,
                new_password: this.new_password,
                new_password_confirmation: this.new_password_confirmation,
            }).then((res) => {
                if (res.status) {
                    this.$router.push({name: 'index'}).catch(() => {});
                }
            }).catch(() => {
                this.new_password = this.new_password_confirmation = "";
                this.updating = this.update_password_disabled = false;
                this.ban_update_password();
            });
        }
    }
}
</script>
