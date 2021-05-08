<template>
    <div style="height: 2px; background-color: #ffe277"
         :style="{
            width: `${percent}%`,
            opacity: show ? 1 : 0,
            'background-color': status_color()
         }"
         class="progress"
    />
</template>

<script>
export default {
    data: function () {
        return {
            percent: 0,
            show: false,
            duration: 3000,
            status: null
        }
    },
    methods: {
        start: function () {
            this.show = true;

            if (this._timer) {
                clearInterval(this._timer);
                this.percent = 0;
            }

            this._cut = 10000 / Math.floor(this.duration);

            this._timer = setInterval(() => {
                if (this.percent > 95)
                    this.decrease(this._cut * Math.random());
                else
                    this.increase(this._cut * Math.random());
            }, 100);
        },
        status_color: function () {
            if (this.status === null)
                return '#ffe277';
            else if (this.status)
                return '#77ebff';
            else
                return '#f65353';
        },
        increase: function (num) {
            this.percent = this.percent + Math.floor(num);
        },
        decrease: function (num) {
            this.percent = this.percent - Math.floor(num);
        },
        finish: function (status) {
            this.status = status;
            this.percent = 100;
            this.hide();
        },
        hide: function () {
            clearInterval(this._timer)
            this._timer = null;

            setTimeout(() => {
                this.show = false;
                setTimeout(() => {
                    this.status = null;
                    this.percent = 0;
                }, 200)
            }, 500)
        }
    }
}
</script>

<style scoped>
.progress {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    width: 0%;
    transition: width 0.4s, opacity 0.4s;
    opacity: 1;
    background-color: #efc14e;
    z-index: 999999;
}
</style>
