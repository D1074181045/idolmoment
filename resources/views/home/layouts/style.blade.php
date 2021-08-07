{{ Html::style( mix('css/app.css')) }}
{{ Html::style( asset('css/sweetalert2.default.theme.css')) }}

<style type="text/css">
    button {
        margin: 0 12px;
        width: 100px;
        padding: 8px 20px;
    }

    table {
        border: 1px solid var(--border-color);
        font-size: 14px;
    }

    .setting {
        -webkit-align-items: center;
                align-items: center;
        display: flex;
        margin-bottom: 6px;
    }

    .center {
        padding-top: 70px;
        margin: 12px auto;
        max-width: 760px;
        min-width: 500px;
    }

    @media screen and (max-width: 768px) {
        .center {
            padding-top: 40px;
        }
    }

    .tb {
        border: 1px solid var(--border-color);
        margin-bottom: 18px;
        overflow-x: auto;
        padding: 12px;
    }

    .tb .tb-gap {
        margin-top: 15px;
        margin-bottom: 10px;
    }

    th {
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        padding: 4px 8px;
        text-align: left;
    }

    td {
        padding: 4px 8px;
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
    }

    .swal-footer {
        text-align: center;
    }

    .dark-swift-button {
        margin: 4px 32px;
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
        height: 33px;
        text-indent: -9999px;
        background-color: #f5f5f5;
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
        background-color: #045667;
        -webkit-background-image: url('https://ik.imagekit.io/7bjbvrubevy/theme/pic_moon');
                background-image: url('https://ik.imagekit.io/7bjbvrubevy/theme/pic_moon');
{{--        -webkit-background-image: url('https://f000.backblazeb2.com/file/idolmoment/theme/pic_moon.svg'); --}}
{{--                background-image: url('https://f000.backblazeb2.com/file/idolmoment/theme/pic_moon.svg'); --}}
}
</style>

@yield('styles')
