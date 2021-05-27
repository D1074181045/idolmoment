<template>
    <div class="container" style="color: var(--form-control-color)">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: var(--form-control-bg-color)">
                    <div class="card-header">郵件驗證</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">郵件地址</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" style="width: 100%" autocomplete="off"
                                       v-on:input="check_email" :class="disabled_class(illegal)" v-model="email"/>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-block" style="margin: 0 0;"
                                :disabled="send_disable" v-on:click="send">{{ send_btn_name }}</button>
                    </div>
                    <CardFooter :type="'alert-danger'" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CardFooter from "../../../components/CardFooter";
import {mapGetters, mapMutations, mapState} from "vuex";

let legalityKey = new RegExp("^\\w{1,255}@[a-zA-Z0-9]{2,63}\\.[a-zA-Z]{2,63}(\\.[a-zA-Z]{2,63})*$");

export default {
    data() {
        return {
            email: null,
            send_btn_name: '寄送',
            illegal: true,
            send_disable: true,
            isCd: false
        }
    },
    components: {
        CardFooter
    },
    computed: {
        ...mapState([
            'api_prefix',
            'loading'
        ]),
        ...mapGetters([
            'disabled_class'
        ])
    },
    methods: {
        ...mapMutations([
            'show_error'
        ]),
        check_email:function() {
            if (!this.isCd)
                this.send_disable = this.illegal = !legalityKey.test(this.email);
        },
        cd: function (second) {
            let _second = second;
            this.isCd = true;
            let cd = setInterval(() => {
                this.send_btn_name = '再次寄送還需 ' + (_second--).toString() + ' 秒';
                if (_second === 0) {
                    this.send_btn_name = '再次寄送';
                    this.send_disable = false;
                    this.isCd = false;
                    this.check_email();
                    clearInterval(cd);
                }
            }, 1000)
        },
        send: function () {
            if (this.send_disable)
                return

            let url = this.api_prefix.concat('email/send')
            this.send_disable = true;

            axios.post(url, {
                email: this.email
            }).then((res) => {
                if (res.status !== 0)
                    this.cd(30);
            }).catch((err) => {
                this.show_error(err.data.message);
                this.send_disable = false;
            })
        }
    }
}
</script>

<style scoped>

</style>
