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

    }

    .img-small {
        margin: 0 auto;
        text-align: center;
        position: relative;
        overflow: hidden;
        max-width: 60px;
    }

    picture img {
        vertical-align: middle;
        height: 160px;
        width: 160px;
    }

    .Msg {
        color: #DC3545;
        margin-bottom: 5px;
        margin-top: -0.5rem;
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
        /*position: absolute;*/
        margin: 4px 32px;
        border-radius: 20px;
        float: right;
        cursor: pointer;
        overflow: hidden;
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
        /*display: -webkit-box;*/
        /*display: -ms-flexbox;*/
        display: flex;
        /*-webkit-box-pack: center;*/
        /*-ms-flex-pack: center;*/
        /*justify-content: center;*/
        /*-webkit-box-align: center;*/
        /*-ms-flex-align: center;*/
        /*align-items: center;*/
        width: 52px;
        height: 26px;
        text-indent: -9999px;
        background-color: #f5f5f5;
        background-image: url({{ asset('img/theme/pic_sun.svg') }});
        background-position: 28px;
        background-repeat: no-repeat;
        background-size: 18px 18px;
        border-radius: 100px;
        cursor: pointer;
    }

    .dark-swift-button > label:after {
        content: "";
        position: absolute;
        top: 4px;
        left: 6px;
        /*-webkit-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;*/
        transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
        width: 18px;
        height: 18px;
        border-radius: 100px;
        background-color: #b7b1a9;
    }

    .dark-swift-button > input:checked + label {
        background-color: #045667;
        background-image: url({{ asset('img/theme/pic_moon.svg') }});
        background-position: 8px;
        background-repeat: no-repeat;
        background-size: 18px 18px;
        border-radius: 100px;
    }

    .dark-swift-button > input:checked + label:after {
        left: calc(100% - 5px);
        /*-webkit-transform: translateX(-100%);*/
        transform: translateX(-100%);
        background-color: #0c2d33;
    }
</style>

@yield('styles')
