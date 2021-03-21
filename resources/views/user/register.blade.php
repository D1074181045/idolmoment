@extends('user.app')

@section('title', '註冊')

@section('scripts')
    <script type="text/JavaScript">
        const username = $("#username");
        const password = $("#password");
        const password_confirm = $("#password-confirm");
        const password_toggle_button = $("#password-toggle-button");
        const register = $("#register");
        const Msg = $("#Msg");
        const footer = $(".card-footer");

        // const start_check = new RegExp('^[0-9]');
        const legalityKey = new RegExp("^[0-9A-Za-z]+$");

        const setMsg = (msg) => {
            Msg.html(msg);
            footer.prop('style', 'display: block');

            setTimeout(() => {
                $('#Msg').html("");
                footer.prop('style', 'display: none');
                register.blur();
            }, 3000)
        }

        function between(int, from, to) {
            return int >= from && int <= to;
        }

        const ban_register = () => {
            const username_val = username.val();
            const password_val = password.val();
            const password_confirm_val = password_confirm.val();
            const username_length = username.val().length;
            const password_length = password.val().length;

            register.prop("disabled", true);

            let t = 0;

            if (!username_val.match(legalityKey)) {
                username.switchClass('is-valid', 'is-invalid');
            } else {
                if (between(username_length, 5, 15)) {
                    username.switchClass('is-invalid', 'is-valid');
                    t += 1;
                } else {
                    username.switchClass('is-valid', 'is-invalid');
                }
            }

            if (password_confirm_val === password_val) {
                if (!password_val.match(legalityKey)) {
                    password.switchClass('is-valid', 'is-invalid');

                } else {
                    if (between(password_length, 8, 32)) {
                        password_confirm.switchClass('is-invalid', 'is-valid');
                        password.switchClass('is-invalid', 'is-valid');
                        t += 1;
                    } else {
                        password.switchClass('is-valid', 'is-invalid');
                    }
                }
            } else {
                password.switchClass('is-valid', 'is-invalid');
                password_confirm.switchClass('is-valid', 'is-invalid');
            }

            if (t === 2) {
                register.prop("disabled", false);
            }
        }

        const register_click = () => {
            const username_val = username.val();
            const password_val = password.val();
            const password_confirm_val = password_confirm.val();

            if (username_val === "" || password_val === "" || password_confirm_val === "") {
                setMsg_footer(Msg, "錯誤：使用者名稱或密碼為空", footer, register);
                return;
            }
            if (password_confirm_val !== password_val) {
                setMsg_footer(Msg, "錯誤：密碼不一致", footer, register);
                return;
            }

            if (!between(password_val.length, 8, 32))
                setMsg_footer(Msg, "錯誤：密碼請介於8到32字元之間", footer, register);
            else if (!between(username_val.length, 5, 15))
                setMsg_footer(Msg, "錯誤：使用者名稱請介於5到15字元之間", footer, register);
            else if (!username_val.match(legalityKey) || !password_val.match(legalityKey))
                setMsg_footer(Msg, "錯誤：使用者名稱或密碼中含有特殊字元", footer, register);
            else {
                axios.post('{{ Route('api.register') }}', {
                    username: username_val,
                    password: password_val,
                    password_confirm: password_confirm_val
                }).then(({status, message}) => {
                    if (status)
                        document.location.href = "{{ Route('user.login') }}";
                    else {
                        setMsg_footer(Msg, message, footer, register);
                    }
                }).catch((err) => {
                    if (err.status === 422) {
                        let s = "";
                        let errors = err.data.errors;

                        Object.keys(errors).forEach((error) => {
                            s += errors[error] + '\n';
                        });
                        setMsg_footer(Msg, s, footer, register);
                    } else {
                        setMsg_footer(Msg, "發生錯誤: " + err.status, footer, register);
                    }
                });
            }
        }

        $(document).on('keyup', function (e) {
            if ((e.key === 'Enter' || e.keyCode === 13) && !register.prop("disabled")) {
                register.trigger('click').focus();
            }
        });

        register.on('click', register_click)

        password.add(username).add(password_confirm).on('input', ban_register);

        password_toggle_button.on('click', () => {
            if (password_toggle_button.hasClass('hide-pw')) {
                password_toggle_button.switchClass('hide-pw', 'show-pw').prop("title", "隱藏密碼");
                password.prop("type", "text").focus();
                password_confirm.prop("type", "text").focus();
            } else {
                password_toggle_button.switchClass('show-pw', 'hide-pw').prop("title", "顯示密碼");
                password.prop("type", "password").focus();
                password_confirm.prop("type", "password").focus();
            }
        })
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label for="username"
                                   class="col-md-4 col-form-label text-md-right">{{ __('使用者名稱') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control is-invalid" name="username"
                                       value="" autocomplete="off" required autofocus>
                                <div style="font-size: 12px;margin: 4px 8px;">請介於5到15字元之間，且無特殊字元</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('密碼') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control is-invalid" name="password"
                                       autocomplete="off" required>
                                <div style="font-size: 12px;margin: 4px 8px;">請介於8到32字元之間，且無特殊字元</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm"
                                   class="col-md-4 col-form-label text-md-right">{{ __('確認密碼') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control is-invalid"
                                       name="password-confirm" autocomplete="off" required>
                            </div>
                            <button type="button" id="password-toggle-button" class="show-hide-toggle-button hide-pw"
                                    tabindex="-1" title="顯示密碼"></button>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button id="register" name="register" type="button" disabled class="btn btn-primary">
                                    {{ __('註冊') }}
                                </button>
                                <button id="back" name="back" type="button" class="btn btn-dark"
                                        onclick=location.href="{{ route('user.login') }}">
                                    {{ __('返回') }}
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
