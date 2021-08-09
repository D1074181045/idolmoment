<!-- Bootstrap CSS -->
{{ Html::style( mix('css/app.css')) }}

<style type="text/css">
    .dark-swift-button {
        margin: 0 32px;
        -webkit-border-radius: 20px;
                border-radius: 20px;
        float: right;
        cursor: pointer;
    }

    @media screen and (max-width: 768px) {
        .dark-swift-button {
            position: inherit;
        }
    }

    .dark-swift-button > input[type=checkbox] {
        display: none;
    }

    .dark-swift-button > label {
        position: relative;
        width: 33px;
        height: 24px;
        text-indent: -9999px;
        background-color: #04566700;
        -webkit-background-image: url('https://ik.imagekit.io/7bjbvrubevy/theme/pic_sun');
                background-image: url('https://ik.imagekit.io/7bjbvrubevy/theme/pic_sun');
{{--        -webkit-background-image: url('https://f000.backblazeb2.com/file/idolmoment/theme/pic_sun.svg'); --}}
{{--                background-image: url('https://f000.backblazeb2.com/file/idolmoment/theme/pic_sun.svg'); --}}
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

    .dark-swift-button > input:checked + label {
        background-color: #04566700;
        -webkit-background-image: url('https://ik.imagekit.io/7bjbvrubevy/theme/pic_moon');
                background-image: url('https://ik.imagekit.io/7bjbvrubevy/theme/pic_moon');
{{--        -webkit-background-image: url('https://f000.backblazeb2.com/file/idolmoment/theme/pic_moon.svg'); --}}
{{--                background-image: url('https://f000.backblazeb2.com/file/idolmoment/theme/pic_moon.svg'); --}}
}
</style>

@yield('styles')
