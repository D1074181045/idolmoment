<template>
    <div class="card" v-on:keyup.enter="send_reset_password">
        <div class="card-header">
            忘記密碼
            <LightSwitch />
        </div>

        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">電子郵件</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" autocomplete="off" autofocus
                           v-model="email" :class="disabled_class(illegal)"
                           v-on:input="check_email"/>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6 offset-md-4">
                    <button type="button" class="btn btn-primary" style="margin: 0 0;"
                            :class="{ 'btn-loading':sending }"
                            v-on:click="send_reset_password" :disabled="send_disable"
                            >{{ send_btn_name }}
                    </button>
                    <button type="button" class="btn btn-dark"
                            v-on:click="back">返回</button>
                </div>
            </div>
        </div>
        <CardFooter :type="alert_type(alert_status)" />
    </div>
</template>

<script>
import CardFooter from '../../../components/CardFooter';
import LightSwitch from '../../../components/LightSwitch';

import {mapGetters, mapMutations, mapState} from "vuex";

let legalityKey = new RegExp("^\\w{1,255}@[a-zA-Z0-9]{2,63}\\.[a-zA-Z]{2,63}(\\.[a-zA-Z]{2,63})*$");

export default {
    data: function () {
        return {
            email: "",
            send_btn_name: '寄送密碼重設',
            illegal: true,
            send_disable: true,
            check: false,
            sending: false,
            alert_status: false
        }
    },
    components: {
        CardFooter,
        LightSwitch
    },
    computed: {
        ...mapState([
            'api_prefix'
        ]),
        ...mapGetters([
            'disabled_class'
        ])
    },
    methods: {
        ...mapMutations([
            'show_error'
        ]),
        alert_type: function (status) {
            return status ? 'alert-success' : 'alert-danger'
        },
        check_email:function() {
            this.illegal = !legalityKey.test(this.email);

            if (!this.check)
                this.send_disable = this.illegal;
        },
        back: function () {
            this.$router.push({name: 'login'});
        },
        cd: function (second) {
            let _second = second;
            this.sending = false;

            this.send_btn_name = '再次寄送密碼重設還需 ' + (_second--).toString() + ' 秒';
            let cd = setInterval(() => {
                this.send_btn_name = '再次寄送密碼重設還需 ' + (_second--).toString() + ' 秒';
                if (_second === -1) {
                    this.send_btn_name = '再次寄送密碼重設';
                    this.send_disable = this.check = false;
                    this.check_email();
                    clearInterval(cd);
                }
            }, 1000)
        },
        send_reset_password: function () {
            if (this.send_disable)
                return

            let url = this.api_prefix.concat('password/email')
            this.send_disable = this.sending = this.check = true;

            axios.post(url, {
                email: this.email
            }).then((res) => {
                this.alert_status = true;
                this.show_error(res.message);
                this.cd(30);
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
                this.alert_status = this.send_disable = this.sending = false;
            })
        }
    }
}
</script>
