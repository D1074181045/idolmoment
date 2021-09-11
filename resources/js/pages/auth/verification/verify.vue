<template>
    <div class="card">
        <div class="card-header">
            郵件驗證結果
            <LightSwitch />
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 m-auto" style="text-align: center;">
                    <div class="alert" :class="alert_class(success)">
                        {{ message }}
                    </div>
                    <a class="btn btn-primary btn-block" :href="status_href(status)">
                        {{ status_str(status) }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LightSwitch from "~/components/LightSwitch";

export default {
    data: function () {
        return {
            success: false,
            status: false,
            message: null,
        }
    },
    components: {
        LightSwitch
    },
    beforeRouteEnter: function (to, from, next) {
        console.log('to_list：', to);

        let url = `/api${to.fullPath}`;

        axios.post(url).then((res) => {
            next(vm => {
                vm.success = true;
                vm.status = res.status;
                vm.message = res.message
            })
        }).catch((err) => {
            next(vm => {
                vm.success = false;
                vm.status = err.data.status;
                vm.message = err.data.message
            })
        })
    },
    methods: {
        status_href: function (status) {
            return status ? '/' : '/email/send';
        },
        status_str: function (status) {
            return status ? '返回首頁' : '返回郵件驗證';
        },
        alert_class: function (success) {
            return success ? 'alert-success' : 'alert-danger';
        },
    }
}
</script>
