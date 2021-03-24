{{ Html::style( mix('css/app.css')) }}

<style type="text/css">
    .show-hide-toggle-button > input[type=checkbox] {
        display: none;
    }

    .show-hide-toggle-button > label{
        width: 1.2rem;
        height: 1rem;
        text-indent: -9999px;
        background-image: url('{{ asset('img/password-toggle-button/hide-v0.0.1.svg') }}');
        background-repeat: no-repeat;
        background-size: 18px 18px;
        cursor: pointer;
    }

    .show-hide-toggle-button > input:checked + label{
        background-image: url('{{ asset('img/password-toggle-button/show-v0.0.1.svg') }}');
    }

    .show-hide-toggle-button {
        margin-top: .7rem;
    }

    {{--.show-hide-toggle-button {--}}
    {{--    vertical-align: -11%;--}}
    {{--    margin-top: .7rem;--}}
    {{--    background-size: .94118rem;--}}
    {{--    background-color: transparent;--}}
    {{--    width: .94118rem;--}}
    {{--    height: .94118rem;--}}
    {{--    padding: 0;--}}
    {{--    border: 0;--}}
    {{--}--}}

    {{--.hide-pw {--}}
    {{--    background-image: url('{{ asset('img/password-toggle-button/hide-v0.0.1.svg') }}');--}}
    {{--    transition: all 0.15s;--}}
    {{--}--}}

    {{--.show-pw {--}}
    {{--    background-image: url('{{ asset('img/password-toggle-button/show-v0.0.1.svg') }}');--}}
    {{--    transition: all 0.15s;--}}
    {{--}--}}
</style>

@yield('styles')
