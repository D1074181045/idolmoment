{{ Html::style( mix('css/app.css')) }}

<style type="text/css">
    .show-hide-toggle-button {
        vertical-align: -11%;
        margin-top: .7rem;
        background-size: .94118rem;
        background-color: transparent;
        width: .94118rem;
        height: .94118rem;
        padding: 0;
        border: 0;
    }

    .hide-pw {
        background-image: url('{{ asset('img/password-toggle-button/hide-v0.0.1.svg') }}');
        transition: all 0.15s;
    }

    .show-pw {
        background-image: url('{{ asset('img/password-toggle-button/show-v0.0.1.svg') }}');
        transition: all 0.15s;
    }
</style>

@yield('styles')
