@extends('user.app')

@section('title', '登入')

@section('scripts')
    <script type="text/JavaScript">
        const username = $("#username");
        const password = $("#password");
        const autologin = $("#autologin");
        const password_toggle_button = $("#password-toggle-button")
        const login = $("#login");
        const Msg = $("#Msg");
        const footer = $(".card-footer");

        const ban_login = () => {
            const username_length = username.val().length;
            const password_length = password.val().length;

            login.prop("disabled", true);

            if (username_length > 0)
                username.switchClass('is-invalid', 'is-valid');
            else
                username.switchClass('is-valid', 'is-invalid');

            if (password_length > 0)
                password.switchClass('is-invalid', 'is-valid');
            else
                password.switchClass('is-valid', 'is-invalid');

            if (username_length > 0 && password_length > 0)
                login.prop("disabled", false);

        }

        const login_click = () => {
            const username_val = username.val();
            const password_val = password.val();
            const autologin_checked = autologin.prop("checked");

            if (username_val.length === 0 || password_val.length === 0) {
                setMsg("錯誤：使用者名稱或密碼為空");
                return;
            }

            axios.get('{{ Route('api.login') }}', {
                params: {
                    username: username_val,
                    password: password_val,
                    autologin: autologin_checked
                }
            }).then(({status, message}) => {
                if (status)
                    document.location.href = "/";
                else {
                    setMsg_footer(Msg, message, footer, login);
                }
            }).catch((err) => {
                if (err.status === 422) {
                    let s = "";
                    let errors = err.data.errors;

                    Object.keys(errors).forEach((error) => {
                        s += errors[error] + '\n';
                    });

                    setMsg_footer(Msg, s, footer, login);
                } else {
                    setMsg_footer(Msg, "發生錯誤: " + err.statusText, footer, login);
                }
            });
        }

        $(document).on('keyup', function (e) {
            if ((e.key === 'Enter' || e.keyCode === 13) && !login.prop("disabled")) {
                login.trigger('click').focus();
            }
        });

        login.on('click', login_click);

        username.on('input', ban_login);

        password.on('input', ban_login);

        password_toggle_button.on('click', () => {
            if (password_toggle_button.hasClass('hide-pw')) {
                password_toggle_button.switchClass('hide-pw', 'show-pw').prop("title", "隱藏密碼");
                password.prop("type", "text").focus();
            } else {
                password_toggle_button.switchClass('show-pw', 'hide-pw').prop("title", "顯示密碼");
                password.prop("type", "password").focus();
            }
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}

                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label for="username"
                                   class="col-md-4 col-form-label text-md-right">{{ __('使用者名稱') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control is-invalid" name="username"
                                       value="" autocomplete="off" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{ __('使用者密碼') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password"
                                       required autocomplete="off">
                            </div>
                            <button type="button" id="password-toggle-button" class="show-hide-toggle-button hide-pw"
                                    tabindex="-1" title="顯示密碼"></button>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="autologin" id="autologin">
                                    <label class="form-check-label" for="autologin">
                                        {{ __('自動登入') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button id="login" name="login" type="button" disabled class="btn btn-primary">
                                    {{ __('登入') }}
                                </button>
                                <button id="back" name="back" type="button" class="btn btn-dark"
                                        onclick=location.href="{{ route('user.register') }}">
                                    {{ __('前往註冊') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" style="display: none">
                        <div id="Msg" style="color: #9A0000;text-align: center;font-size:20px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
