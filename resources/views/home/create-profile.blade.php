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

        const build = $("#build");
        const nickname = $("#nickname");

        build.on('click', () => {
            const nickname_val = nickname.val();

            if (nickname_val === "") {
                setMsg_footer("錯誤：暱稱不能為空");
                return;
            }
            if (nickname_val.length > 12) {
                setMsg_footer("錯誤：暱稱超過12字元");
                return;
            }
            if (!nickname_val.match(legalityKey)) {
                setMsg_footer("錯誤：暱稱不能含有特殊字元");
                return;
            }

            build.prop("disabled", true);

            Axios.post('{{ Route('api.store.profile') }}', {
                nickname: nickname_val,
            }).then(({status, message}) => {
                if (status)
                    document.location.href = "{{ Route('home.index') }}";
                else {
                    setMsg_footer(message);
                    build.prop("disabled", false);
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
                build.prop("disabled", false);
            });
        })

        nickname.on('input', () => {
            const nickname_val = nickname.val();
            const nickname_length = nickname_val.length;

            if (nickname_val.match(legalityKey)) {
                if (nickname_length === 0) {
                    nickname.switchClass('is-valid', 'is-invalid');
                    build.prop("disabled", true)
                } else if (nickname_length > 12) {
                    nickname.switchClass('is-valid', 'is-invalid');
                    build.prop("disabled", true);
                } else {
                    nickname.switchClass('is-invalid', 'is-valid');
                    build.prop("disabled", false);
                }
            } else {
                nickname.switchClass('is-valid', 'is-invalid');
                build.prop("disabled", true);
            }
        })

    </script>
@endsection

@section('axios')
    <div class="container" style="color: var(--form-control-color)">
        <div class="row justify-axios-center">
            <div class="col-md-8">
                <div class="card" style="background-color: var(--form-control-bg-color)">
                    <div class="card-header">{{ __('建立暱稱') }}</div>

                    <div class="card-body">
                        <div class="row">
                            <label for="nickname" class="col-md-3 col-form-label text-md-right">{{ __('暱稱') }}</label>
                            <div class="col-md-6">
                                <input id="nickname" type="text" class="form-control is-invalid" name="nickname"
                                       value="" style="width: 100%" autocomplete="off" required autofocus>
                                <div style="font-size: 12px;margin: 4px 8px;">最多12字元，且無特殊字元</div>
                            </div>
                            <button id="build" name="build" type="button" disabled class="btn btn-primary"
                                    style="width: 60px;height: 1%;">
                                {{ __('建立') }}
                            </button>
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
