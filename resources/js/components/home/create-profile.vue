<template>
    <div class="container" style="color: var(--form-control-color)">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: var(--form-control-bg-color)">
                    <div class="card-header">建立暱稱</div>

                    <div class="card-body">
                        <div class="row">
                            <label class="col-md-3 col-form-label text-md-right">暱稱</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" style="width: 100%" autocomplete="off" autofocus
                                       :class="$store.getters.disabled_class(build_disabled)" v-model="nickname" v-on:input="ban_build">
                                <div style="font-size: 12px;margin: 4px 8px;">最多12字元，且無特殊字元</div>
                            </div>
                            <button type="button" disabled class="btn btn-primary" style="width: 60px;height: 1%;"
                                    v-on:click="build" :disabled="build_disabled">建立</button>
                        </div>
                    </div>

                    <div class="card-footer" v-if="error.status">
                        <msg>{{ error.message }}</msg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { msg } from '../../styles';

export default {
    data() {
        return {
            nickname: "",
            build_disabled: true
        }
    },
    components:{
        msg
    },
    created() {
        document.title = "創建偶像";
    },
    computed:{
        error() {
            return this.$store.state.error;
        }
    },
    methods: {
        build: function () {
            if (this.build_disabled)
                return;

            axios.post(this.api_prefix.concat('store-profile'), {
                nickname: this.nickname,
            }).then(({status, message}) => {
                if (status) {
                    this.$store.dispatch('load_my_profile').then(() => {
                        this.$router.push({name: 'index'});
                    })
                }
                else {
                    this.$store.commit("show_error", message);
                    this.build_disabled = false;
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
                    this.$store.commit("show_error","發生錯誤: " + err.statusText);
                }
                this.build_disabled = false;
            });

        },
        ban_build: function () {
            const legalityKey = new RegExp("^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9]+$");

            if (this.nickname.match(legalityKey)) {
                this.build_disabled = this.nickname.length === 0 || this.nickname.length > 12;
            } else {
                this.build_disabled = true;
            }
        },
    }
}
</script>

<style scoped>
</style>
