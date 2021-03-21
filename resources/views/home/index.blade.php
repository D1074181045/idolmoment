@extends('home.app')

@section('title', '我的偶像')

@section('styles')
    <style type="text/css">
        .tb .tb-gap {
            margin-top: 15px;
            margin-bottom: 10px;
        }

        button.btn.btn-bottom {
            margin-bottom: 12px;
            margin-right: 3px;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        const legalityKey = new RegExp("^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9 ]+$");

        const set_signature = $("#set-signature");
        const set_teetee = $("#set-teetee");
        const teetee = $("#teetee");
        const signature = $("#signature");
        const Msg = $("#Msg");
        const Msg2 = $("#Msg2");

        const adult_live = $('#adult-live');
        const live = $('#live');
        const do_good_things = $('#do-good-things');
        const go_to_sleep = $('#go-to-sleep');
        const meditation = $('#meditation');
        const activity = adult_live.add(live).add(do_good_things).add(go_to_sleep).add(meditation);

        const signature_time = StringToDate('{{ $signature_time }}');
        const activity_time = StringToDate('{{ $activity_time }}');

        var ban_signature = false;

        set_teetee.on('click', () => {
            const teetee_val = teetee.val();
            const teetee_length = teetee_val.length;

            if (teetee_length > 12 || !teetee_val.match(legalityKey) && teetee_length !== 0)
                return;

            axios.patch('{{ Route('api.update.teetee') }}', {
                teetee: teetee_val,
            }).then(({teetee_status}) => {
                if (teetee_status) {
                    teetee.switchClass('is-invalid', 'is-valid');
                } else {
                    teetee.switchClass('is-valid', 'is-invalid');
                }
            }).catch((err) => {
                show_error_msg(err, Msg);
            });
        });

        signature.on('input', () => {
            const signature_length = signature.val().length;

            if (ban_type.signature)
                return;

            if (signature.val().match(legalityKey)) {
                if (signature_length > 30) {
                    set_signature.prop("disabled", true);
                    signature.switchClass('is-valid', 'is-invalid');
                } else {
                    set_signature.prop("disabled", false);
                    signature.switchClass('is-invalid', 'is-valid');
                }
            } else {
                if (signature_length === 0) {
                    set_signature.prop("disabled", false);
                    signature.switchClass('is-invalid', 'is-valid');
                } else {
                    set_signature.prop("disabled", true);
                    signature.switchClass('is-valid', 'is-invalid');
                }
            }
        })

        set_signature.on('click', () => {
            const signature_val = signature.val();
            const signature_length = signature_val.length;

            if (ban_type.signature || signature_length > 30 || !signature_val.match(legalityKey) && signature_length !== 0)
                return;

            set_signature.prop('disabled', true);

            axios.patch('{{ Route('api.update.signature') }}', {
                signature: signature_val
            }).then(({status, signature_time}) => {
                if (status) {
                    CoolDown_Time(Msg, new Date(signature_time), set_signature, 'signature');
                } else {
                    set_signature.prop('disabled', false);
                }
            }).catch((err) => {
                set_signature.prop('disabled', false);
                show_error_msg(err, Msg);
            });
        })

        @if (!$self_game_info->graduate)
            activity.on('click', (e) => {
                if (ban_type.activity)
                    return;

                activity.prop('disabled', true);

                axios.patch("{{ Route('api.activity') }}", {
                    activity_type: e.currentTarget.id,
                }).then(({status, ability, activity_time}) => {
                    if (status) {
                        CoolDown_Time(Msg2, new Date(activity_time), activity, 'activity');
                        batch_change_value(ability);
                    } else {
                        activity.prop('disabled', false);
                    }
                }).catch((err) => {
                    activity.prop('disabled', false);
                    show_error_msg(err, Msg2);
                });
            })
        @endif

        $(function () {
            CoolDown_Time(Msg, signature_time, set_signature, 'signature');
            CoolDown_Time(Msg2, activity_time, activity, 'activity');
        })
    </script>
@endsection



@section('content')
    <div class="tb">
        <h3>我的偶像</h3>
        <table class="table">
            <tbody>
            <tr>
                <th class="table-active">暱稱</th>
                <td>{{ $self_game_info->nickname }}</td>
                <td rowspan="2" style="width: 80px;">
                    <div class="img-big">
                        <picture>
                            <source type="image/png"
                                    srcset="{{ asset('img/characters/' . $self_game_info->GameCharacter->img_file_name . '.jpg') }}">
                            <img
                                src="{{ asset('img/characters/' . $self_game_info->GameCharacter->img_file_name . '.jpg') }}"
                                alt="{{ $self_game_info->use_character }}">
                        </picture>
                    </div>
                </td>
            </tr>
            <tr>
                <th class="table-active">偶像</th>
                <td>{{ $self_game_info->GameCharacter->tc_name }}</td>
            </tr>
            <tr>
                <th class="table-info">人氣</th>
                <td colspan="2" name="popularity">{{ number_format($self_game_info->popularity) }}</td>
            </tr>
            <tr>
                <th class="table-info">名聲</th>
                <td colspan="2" name="reputation">{{ number_format($self_game_info->reputation) }}</td>
            </tr>
            <tr>
                <th class="table-info">最大生命值</th>
                <td colspan="2" name="max_vitality">{{ number_format($self_game_info->max_vitality) }}</td>
            </tr>
            <tr>
                <th class="table-info">目前生命值</th>
                <td colspan="2" name="current_vitality">{{ number_format($self_game_info->current_vitality) }}</td>
            </tr>
            <tr>
                <th class="table-info">精力</th>
                <td colspan="2" name="energy">{{ number_format($self_game_info->energy) }}</td>
            </tr>
            <tr>
                <th class="table-info">抗壓性</th>
                <td colspan="2" name="resistance">{{ number_format($self_game_info->resistance) }}</td>
            </tr>
            <tr>
                <th class="table-info">魅力</th>
                <td colspan="2" name="charm">{{ number_format($self_game_info->charm) }}</td>
            </tr>
            <tr>
                <th class="table-secondary">轉生次數</th>
                <td colspan="2">{{ number_format($self_game_info->rebirth_counter) }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="tb">
        <h3>個人設定</h3>
        <div class="Msg" style="display:none;" id="Msg"></div>
        <div class="tb-gap">
            <div class="setting">
                <label style="width: 80px;margin-bottom: 0;" for="signature">簽名檔</label>
                <input placeholder="最多30個字" type="text" name="signature" id="signature" class="form-control is-valid"
                       value="{{ $self_game_info->signature }}"/>
                <button type="button" id="set-signature" name="set-signature" class="btn btn-info">更新</button>
            </div>
            <div class="setting">
                <label style="width: 80px;margin-bottom: 0;" for="teetee">貼貼</label>
                @if ($teetee_status)
                    <input type="text" maxlength="12" name="teetee" id="teetee" class="form-control is-valid"
                           value="{{ $self_game_info->teetee }}"/>
                @else
                    <input type="text" maxlength="12" name="teetee" id="teetee" class="form-control is-invalid"
                           value="{{ $self_game_info->teetee }}"/>
                @endif
                <button type="button" id="set-teetee" name="set-teetee" class="btn btn-info">設定</button>
            </div>
        </div>
    </div>

    @if (!$self_game_info->graduate)
        <div class="tb">
            <h3>進行活動</h3>
            <div class="Msg" style="display:none;" id="Msg2"></div>
            <div class="tb-gap" style="margin-left: -10px;">
                <button type="button" id="adult-live" class="btn btn-bottom btn-info">成人直播</button>
                <button type="button" id="live" class="btn btn-bottom btn-info">直播</button>
                <button type="button" id="do-good-things" class="btn btn-bottom btn-info">做善事</button>
                <button type="button" id="go-to-sleep" class="btn btn-bottom btn-info">睡覺</button>
                <button type="button" id="meditation" class="btn btn-bottom btn-info">打坐</button>
            </div>
        </div>
    @else
        <div class="tb">
            <h3>已畢業，無法進行活動</h3>
            <div class="tb-gap" style="margin-left: -10px;">
                <button type="button" id="adult-live" class="btn btn-bottom btn-info" disabled>成人直播</button>
                <button type="button" id="live" class="btn btn-bottom btn-info" disabled>直播</button>
                <button type="button" id="do-good-things" class="btn btn-bottom btn-info" disabled>做善事</button>
                <button type="button" id="go-to-sleep" class="btn btn-bottom btn-info" disabled>睡覺</button>
                <button type="button" id="meditation" class="btn btn-bottom btn-info" disabled>打坐</button>
            </div>
        </div>
    @endif

@endsection
