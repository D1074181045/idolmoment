<template>
    <div class="container" style="color: var(--form-control-color)">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: var(--form-control-bg-color)">
                    <div class="card-header">建立暱稱</div>

                    <div class="card-body">
                        <div class="row">
                            <label for="nickname" class="col-md-3 col-form-label text-md-right">暱稱</label>
                            <div class="col-md-6">
                                <input id="nickname" type="text" class="form-control is-invalid" name="nickname"
                                       value="" style="width: 100%" autocomplete="off" required autofocus>
                                <div style="font-size: 12px;margin: 4px 8px;">最多12字元，且無特殊字元</div>
                            </div>
                            <button id="build" name="build" type="button" disabled class="btn btn-primary" style="width: 60px;height: 1%;">建立</button>
                        </div>
                    </div>

                    <div class="card-footer" style="display: none">
                        <div id="Msg" style="color: #9A0000;text-align: center;font-size:20px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {

        }
    },
    created: function () {

    },
    activated() {

    },
    methods: {

    }
}
</script>

<style scoped>
</style>
