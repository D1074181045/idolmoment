<template>
    <div class="container" style="color: var(--form-control-color)" v-on:keyup.enter="send">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: var(--form-control-bg-color)">
                    <div class="card-header">郵件驗證</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">郵件地址</label>
                            <div class="col-md-8" v-if="!$store.state.email_verify">
                                <input type="text" class="form-control" style="width: 100%" autocomplete="off" autofocus
                                       v-on:input="check_email" :class="disabled_class(illegal)" v-model="email"/>
                            </div>
                            <div class="col-md-8" v-else>
                                <input type="text" class="form-control" style="width: 100%" autocomplete="off"
                                        :value="email" disabled/>
                            </div>
                        </div>
                        <template v-if="!$store.state.email_verify">
                            <button type="button" class="btn btn-primary btn-block" style="margin: 0 0;"
                                    :class="{ 'btn-loading':sending }"
                                    :disabled="send_disable" v-on:click="send"
                                    >{{ send_btn_name }}</button>
                        </template>
                        <template v-else>
                            <button type="button" class="btn btn-primary btn-block" style="margin: 0 0;" disabled>已驗證</button>
                        </template>
                    </div>
                    <CardFooter :type="'alert-danger'" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CardFooter from "~/components/CardFooter";
import {mapGetters, mapMutations, mapState} from "vuex";
import {email_re} from "~/regex";

export default {
    data() {
        return {
            email: this.$store.state.email,
            send_btn_name: '發送',
            illegal: true,
            send_disable: true,
            check: false,
            sending: false
        }
    },
    components: {
        CardFooter
    },
    computed: {
        ...mapState([
            'api_prefix',
        ]),
        ...mapGetters([
            'disabled_class'
        ])
    },
    mounted() {
        this.check_email();
    },
    methods: {
        ...mapMutations([
            'show_error'
        ]),
        check_email:function() {
            this.illegal = !email_re.test(this.email) || this.email.length > 255;

            if (!this.check)
                this.send_disable = this.illegal;
        },
        cd: function (second) {
            let _second = second;
            this.sending = false;

            this.send_btn_name = '再次發送還需 ' + (_second--).toString() + ' 秒';
            let _cd = setInterval(() => {
                if (_second > 0) {
                    this.send_btn_name = '再次發送還需 ' + (_second--).toString() + ' 秒';
                } else {
                    this.send_btn_name = '再次發送';
                    this.send_disable = this.check = false;
                    this.check_email();
                    clearInterval(_cd);
                }
            }, 1000)
        },
        send: function () {
            if (this.send_disable)
                return

            let url = this.api_prefix.concat('email/send')
            this.send_disable = this.sending = this.check = true;

            axios.post(url, {
                email: this.email
            }).then((res) => {
                this.$store.state.email = this.email = res.email;
                this.$store.state.email_verify = res.email_verify;
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
                    this.$store.state.email = this.email = err.data.email;
                    this.$store.state.email_verify = err.data.email_verify;
                    this.show_error(err.data.message);
                }
                this.send_disable = this.sending = false;
            })
        }
    }
}
</script>
