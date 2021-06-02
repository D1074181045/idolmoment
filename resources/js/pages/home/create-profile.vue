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
                                       :class="disabled_class(build_disabled)" v-model="nickname"
                                       v-on:input="ban_build">
                                <div style="font-size: 12px;margin: 4px 8px;">最多12字元，且無特殊字元</div>
                            </div>
                            <button type="button" disabled class="btn btn-primary" style="width: 60px;height: 1%;"
                                    :class="{ 'btn-loading':creating }"
                                    v-on:click="build" :disabled="build_disabled">建立
                            </button>
                        </div>
                    </div>

                    <CardFooter :type="'alert-danger'" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CardFooter from "../../components/CardFooter";
import {mapGetters, mapMutations, mapState} from "vuex";

export default {
    data: function () {
        return {
            nickname: "",
            build_disabled: true,
            creating: false
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
    mounted() {
        this.loading.finish(true);
    },
    methods: {
        ...mapMutations([
            'show_error'
        ]),
        build: function () {
            if (this.build_disabled)
                return;

            this.creating = true;
            const url = this.api_prefix.concat('store-profile');

            axios.post(url, {
                nickname: this.nickname,
            }).then((res) => {
                if (res.status) {
                    document.location.href = '/';
                } else {
                    this.show_error(res.message);
                    this.build_disabled = false;
                    this.creating = false;
                }
            }).catch((err) => {
                if (err.status === 422) {
                    let s = "";
                    let errors = err.data.errors;

                    Object.keys(errors).forEach((error) => {
                        s += errors[error] + '\n';
                    });

                    this.show_error(s);
                } else {
                    this.show_error("發生錯誤: " + err.statusText);
                }
                this.build_disabled = false;
                this.creating = false;
            });

        },
        ban_build: function () {
            const legalityKey = new RegExp("^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9]+$");

            this.build_disabled = !this.nickname.match(legalityKey) || this.nickname.length === 0 || this.nickname.length > 12;
        },
    }
}
</script>
