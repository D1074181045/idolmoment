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
            let link = document.querySelector('link[href*="sweetalert2"]');
            if (link) link.href = '/css/sweetalert2.'.concat(this.dark_theme ? 'dark' : 'default').concat('.theme.css');

            let body = document.querySelector('body');
            body.className = this.dark_theme ? 'dark' : '';
        },
        lightSwitch: function (e) {
            localStorage.dark_theme = this.dark_theme = e.target.checked;

            this.switch_theme();
        },
    }
}
</script>

<style lang="scss">
$pic-moon-url: 'https://ik.imagekit.io/7bjbvrubevy/theme/pic_moon';
$pic-sun-url: 'https://ik.imagekit.io/7bjbvrubevy/theme/pic_sun';

@mixin phone {
    @media screen and (max-width: 768px) {
        @content;
    }
}

.dark-swift-button {
    margin: 4px 32px;
    -webkit-border-radius: 20px;
    border-radius: 20px;
    float: right;
    cursor: pointer;

    @include phone {
        position: absolute;
        right: 0.75rem;
        margin: 0.5rem 32px;
    }

    > input[type=checkbox] {
        display: none;

        &:checked + label {
            background-color: #045667;
            -webkit-background-image: url($pic-moon-url);
            background-image: url($pic-moon-url);
        }
    }

    > label {
        position: relative;
        width: 33px;
        height: 33px;
        text-indent: -9999px;
        background-color: #f5f5f5;
        -webkit-background-image: url($pic-sun-url);
        background-image: url($pic-sun-url);
        background-position: 8px;
        background-repeat: no-repeat;
        -webkit-background-size: 18px 18px;
        background-size: 18px 18px;
        -webkit-border-radius: 100px;
        border-radius: 100px;
        cursor: pointer;
        -webkit-transition: all 0.2s ease 0s;
        transition: all 0.2s ease 0s;
    }
}
</style>
