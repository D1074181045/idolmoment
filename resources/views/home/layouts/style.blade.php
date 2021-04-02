{{ Html::style( mix('css/app.css')) }}

@if ($dark_theme === 'true')
    {{ Html::style( asset('css/sweetalert2.dark.theme.css')) }}
@else
    {{ Html::style( asset('css/sweetalert2.default.theme.css')) }}
@endif

<style type="text/css">
    button {
        margin: 0 12px;
        width: 100px;
        background: #ddd;
        border: 1px solid rgba(0, 0, 0, 0);
        border-radius: 4px;
        color: #000;
        cursor: pointer;
        padding: 8px 20px;
        outline: 0;
        transition: all 0.16s ease-out 0s;
    }

    table {
        border: 1px solid var(--border-color);
        margin-bottom: 18px;
        overflow-x: auto;
        padding: 12px;
        width: 100%;
        border-spacing: 0;
        font-size: 14px;
    }

    input[type="text"] {
        border: 1px solid #ddd;
        padding: 4px;
        outline: 0;
        width: 80%;
    }

    .setting {
        /*-webkit-box-align: center;*/
        align-items: center;
        display: flex;
        margin-bottom: 6px;
    }

    .img-big {
        margin: 0 auto;
        text-align: center;
        position: relative;
        overflow: hidden;
        max-width: 160px;
        background-color: rgb(255, 255, 255);
    }

    .img-small {
        margin: 0 auto;
        text-align: center;
        position: relative;
        overflow: hidden;
        max-width: 60px;
        background-color: rgb(255, 255, 255);
    }

    picture img {
        vertical-align: middle;
        height: 160px;
        width: 160px;
    }

    .center {
        padding-top: 70px;
        margin: 12px auto;
        max-width: 760px;
        min-width: 500px;
        overflow: auto;
        width: 92%;
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

    th {
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        padding: 4px 8px;
        text-align: left;
        /*width: 80px;*/
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
        background-image: url('https://f000.backblazeb2.com/file/idolmoment/theme/pic_sun.svg');
        background-position: 8px;
        background-repeat: no-repeat;
        background-size: 18px 18px;
        border-radius: 100px;
        cursor: pointer;
        transition: all 0.4s ease 0s;
    }

    .dark-swift-button > input:checked + label {
        background-color: #045667;
        background-image: url('https://f000.backblazeb2.com/file/idolmoment/theme/pic_moon.svg');
        background-position: 8px;
        background-repeat: no-repeat;
        background-size: 18px 18px;
        border-radius: 100px;
        transition: all 0.4s ease 0s;
    }
</style>

@yield('styles')
