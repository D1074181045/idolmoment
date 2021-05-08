<template>
    <div class="dark-swift-button">
        <input class="swift-btn_toggle" type="checkbox" id="lightSwitch"
               v-on:click="lightSwitch"
               :checked="dark_theme"
        />
        <label for="lightSwitch" style="margin-bottom: 0;">Toggle</label>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            dark_theme: this.String2Bool(localStorage.dark_theme)
        }
    },
    mounted() {
        this.switch_theme();
    },
    methods: {
        String2Bool: function (s) {
            return s === "true";
        },
        switch_theme: function () {
            let link = document.getElementsByTagName('link');
            let body = document.getElementsByTagName('body')[0];

            if (link.length) {
                let link_swal_style = link[link.length - 1];

                if (link_swal_style.href.includes('sweetalert2'))
                    link_swal_style.href = '/css/sweetalert2.'.concat(this.dark_theme ? 'dark' : 'default').concat('.theme.css');
            }

            body.className = this.dark_theme ? 'dark' : '';
        },
        lightSwitch: function (e) {
            localStorage.dark_theme = this.dark_theme = e.target.checked;

            this.switch_theme();
        },
    }
}
</script>
