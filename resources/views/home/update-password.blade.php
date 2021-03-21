@extends('home.app')

@section('title', '建立暱稱')

@section('scripts')
    <script type="text/JavaScript">
        const legalityKey = new RegExp("^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9]+$");

        const setMsg_footer = (msg) => {
            const Msg = $("#Msg");
            const footer = $(".card-footer");

            Msg.html(msg);
            footer.prop('style', 'display: block');
            setTimeout(() => {
                Msg.html("");
                footer.prop('style', 'display: none');
            }, 3000);
        }

        const update = $("#update");
        const old_password = $("#old_password");
        const new_password = $("#new_password");
        const new_password_confirm = $("#new_password_confirm");

        function between(int, from, to) {
            return int >= from && int <= to;
        }

        const ban_update_password = () => {
            let old_password_val = old_password.val();
            let old_password_length = old_password.val().length;
            let new_password_val = new_password.val();
            let new_password_length = new_password.val().length;
            let new_password_confirm_val = new_password_confirm.val();

            update.prop("disabled", true);

            let t = 0;

            if (!old_password_val.match(legalityKey)) {
                old_password.switchClass('is-valid', 'is-invalid');
            } else {
                if (between(old_password_length, 8, 32)) {
                    old_password.switchClass('is-invalid', 'is-valid');
                    t += 1;
                } else {
                    old_password.switchClass('is-valid', 'is-invalid');
                }
            }

            if (new_password_val === new_password_confirm_val) {
                if (!new_password_val.match(legalityKey)) {
                    new_password.switchClass('is-valid', 'is-invalid');

                } else {
                    if (between(new_password_length, 8, 32)) {
                        new_password.switchClass('is-invalid', 'is-valid');
                        new_password_confirm.switchClass('is-invalid', 'is-valid');
                        t += 1;
                    } else {
                        new_password.switchClass('is-valid', 'is-invalid');
                    }
                }
            } else {
                new_password.switchClass('is-valid', 'is-invalid');
                new_password_confirm.switchClass('is-valid', 'is-invalid');
            }

            if (t === 2) {
                update.prop("disabled", false);
            }
        }

        update.on('click', () => {
            if (old_password.val() === "" || new_password.val() === "" || new_password_confirm.val() === "") {
                setMsg_footer("錯誤：密碼為空");
                return;
            }
            if (!old_password.val().match(legalityKey) || !new_password.val().match(legalityKey) || !new_password_confirm.val().match(legalityKey)) {
                setMsg_footer("錯誤：密碼含有特殊字元");
                return;
            }
            if (!between(old_password.val().length, 8, 32) || !between(new_password.val().length, 8, 32) || !between(new_password_confirm.val().length, 8, 32)) {
                setMsg_footer("錯誤：密碼請介於8到32字元之間");
                return;
            }
            if (new_password.val() !== new_password_confirm.val()) {
                setMsg_footer("錯誤：二次密碼不相同");
                return;
            }

            update.prop("disabled", true);

            axios.patch('{{ Route('api.update.password') }}', {
                old_password: old_password.val(),
                new_password: new_password.val(),
                new_password_confirm: new_password_confirm.val(),
            }).then(({status, message}) => {
                if (status)
                    document.location.href = "{{ Route('home.index') }}";
                else {
                    setMsg_footer(message);
                    update.prop("disabled", false);
                }
            }).catch((err) => {
                if (err.status === 422) {
                    let s = "";
                    let errors = err.data.errors;

                    Object.keys(errors).forEach((error) => {
                        s += errors[error] + '\n';
                    });

                    setMsg_footer(s);
                } else {
                    setMsg_footer("發生錯誤: " + err.status);
                }
                update.prop("disabled", false);
            });
        })

        old_password.add(new_password).add(new_password_confirm).on('input', ban_update_password);
    </script>
@endsection

@section('content')
    <div class="container" style="color: var(--form-control-color)">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: var(--form-control-bg-color)">
                    <div class="card-header">{{ __('修改密碼') }}</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label for="old_password"
                                   class="col-md-3 col-form-label text-md-right">{{ __('舊密碼') }}</label>
                            <div class="col-md-6">
                                <input id="old_password" name="old_password" type="password" class="form-control is-invalid"
                                       value="" style="width: 100%" autocomplete="off" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_password"
                                   class="col-md-3 col-form-label text-md-right">{{ __('新密碼') }}</label>
                            <div class="col-md-6">
                                <input id="new_password" name="new_password" type="password" class="form-control is-invalid"
                                       value="" style="width: 100%" autocomplete="off" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_password_confirm"
                                   class="col-md-3 col-form-label text-md-right">{{ __('確認新密碼') }}</label>
                            <div class="col-md-6">
                                <input id="new_password_confirm" name="new_password_confirm" type="password"
                                       class="form-control is-invalid"
                                       value="" style="width: 100%" autocomplete="off" required autofocus>
                                <div style="font-size: 12px;margin: 4px 8px;">請介於8到32字元之間，且無特殊字元</div>
                            </div>
                        </div>
                        <button id="update" name="update" type="button" disabled class="btn btn-primary btn-block"
                                style="margin: 0 0;">
                            {{ __('修改') }}
                        </button>
                    </div>

                    <div class="card-footer" style="display: none">
                        <div id="Msg" style="color: #9A0000;text-align: center;font-size:20px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
