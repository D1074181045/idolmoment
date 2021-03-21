@extends('home.app')

@section('title', '玩家資料')

@section('scripts')
    <script type="text/javascript">
        const Msg = $("#Msg");

        const send_blade = $('#send-blade');
        const operating = send_blade;

        const operating_time = StringToDate('{{ $operating_time }}');

        var information_count = 1;

        const get_profile_type = () => {
            switch (localStorage.profile_type) {
                case 'details':
                    details();
                    return 'comparison';
                case 'comparison':
                    comparison();
                    return 'details';
                default:
                    details();
                    return 'comparison';
            }
        }

        const details = () => {
            $('#switch_show').html('查看能力比對');
            $('#details').prop('style', 'display: block');
            $('#comparison_ability').prop('style', 'display: none');

            localStorage.setItem('profile_type', 'details');
        }

        const comparison = () => {
            $('#switch_show').html('顯示詳細資料');
            $('#comparison_ability').prop('style', 'display: flex');
            $('#details').prop('style', 'display: none');

            localStorage.setItem('profile_type', 'comparison');
        }

        $('#show_details').on('click', details);

        $('#view_comparison_ability').on('click', comparison);

        $('#switch_show').on('click', () => {
            let next_type = get_profile_type();
            switch (next_type) {
                case 'details':
                    details();
                    break;
                case 'comparison':
                    comparison();
                    break;
                default:
                    details();
                    break;
            }
        });

        @if ($opposite_game_info->name != $self_name && !$opposite_game_info->graduate)
            operating.on('click', (e) => {
                if (ban_type.operating)
                    return;

                operating.prop('disabled', true);

                axios.patch("{{ Route('api.operating') }}", {
                    opposite_name: '{{ $opposite_name }}',
                    operating_type: e.currentTarget.id,
                }).then(({status, operating_time, information, message}) => {
                    if (status) {
                        CoolDown_Time(Msg, new Date(operating_time), operating, 'operating');

                        if (information) {
                            let information_count_div = '<div style="color: rgb(153, 153, 153);user-select: none;width: 30px;">' +
                                (information_count++) +
                                '</div>';

                            $('#get_information').append('<div style="display: flex">' + information_count_div + information + '</div>');
                        }
                    } else {
                        operating.prop('disabled', false);
                        show_error_msg(message, Msg);
                    }
                }).catch((err) => {
                    operating.prop('disabled', false);
                    show_error_msg(err, Msg);
                });
            })
        @endif

        $(function () {
            get_profile_type();
            CoolDown_Time(Msg, operating_time, operating, 'operating');
        })
    </script>
@endsection

@section('content')
    <div class="tb">
        <h3>玩家資料</h3>
        <div class="text-center" style="margin: 12px 0 12px 0;">
            <button id="switch_show" name="switch_show" type="button" class="btn btn-info" style="width: 115px;">
                {{ __('查看能力比對') }}
            </button>
        </div>
        <div id="details">
            <table class="table">
                <tbody>
                <tr>
                    <th class="table-active">暱稱</th>
                    <td>{{ $opposite_game_info->nickname }}</td>
                    <td rowspan="2" style="width: 80px;">
                        <div class="img-big">
                            <picture>
                                <img src="{{ asset('img/characters/' . $opposite_game_info->GameCharacter->img_file_name . '.jpg') }}" alt="{{ $opposite_game_info->use_character }}">
                            </picture>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-active">偶像</th>
                    <td>{{ $opposite_game_info->GameCharacter->tc_name }}</td>
                </tr>
                <tr>
                    <th class="table-info">人氣</th>
                    <td colspan="2">{{ number_format($opposite_game_info->popularity) }}</td>
                </tr>
                <tr>
                    <th class="table-info">名聲</th>
                    <td colspan="2">{{ number_format($opposite_game_info->reputation) }}</td>
                </tr>
                <tr>
                    <th class="table-info">最大生命值</th>
                    <td colspan="2">{{ number_format($opposite_game_info->max_vitality) }}</td>
                </tr>
                <tr>
                    <th class="table-info">精力</th>
                    <td colspan="2">{{ number_format($opposite_game_info->energy) }}</td>
                </tr>
                <tr>
                    <th class="table-info">抗壓性</th>
                    <td colspan="2">{{ number_format($opposite_game_info->resistance) }}</td>
                </tr>
                <tr>
                    <th class="table-info">魅力</th>
                    <td colspan="2">{{ number_format($opposite_game_info->charm) }}</td>
                </tr>
                <tr>
                    <th class="table-primary">簽名檔</th>
                    <td colspan="2" style="color:#DC3545;">{{ $opposite_game_info->signature }}</td>
                </tr>
                <tr>
                    <th class="table-secondary">轉生次數</th>
                    <td colspan="2">{{ number_format($opposite_game_info->rebirth_counter) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="comparison_ability" style="display: none">
            <table style="width: 50%" class="table">
                <tbody>
                    <tr>
                        <th class="table-active" colspan="2" style="text-align: center">
                            我的偶像
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 80px;">
                            <div class="img-big">
                                <picture>
                                    <img src="{{ asset('img/characters/' . $self_game_info->GameCharacter->img_file_name . '.jpg') }}" alt="{{ $opposite_game_info->use_character }}">
                                </picture>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-active">暱稱</th>
                        <td>{{ $self_game_info->nickname }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">偶像</th>
                        <td>{{ $self_game_info->GameCharacter->tc_name }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">人氣</th>
                        <td colspan="2">{{ number_format($self_game_info->popularity) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">名聲</th>
                        <td colspan="2">{{ number_format($self_game_info->reputation) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">最大生命值</th>
                        <td colspan="2">{{ number_format($self_game_info->max_vitality) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">精力</th>
                        <td colspan="2">{{ number_format($self_game_info->energy) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">抗壓性</th>
                        <td colspan="2">{{ number_format($self_game_info->resistance) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">魅力</th>
                        <td colspan="2">{{ number_format($self_game_info->charm) }}</td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 50%" class="table">
                <tbody>
                    <tr>
                        <th class="table-active" colspan="2" style="text-align: center">
                            對方偶像
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 80px;">
                            <div class="img-big">
                                <picture>
                                    <img src="{{ asset('img/characters/' . $opposite_game_info->GameCharacter->img_file_name . '.jpg') }}" alt="{{ $opposite_game_info->use_character }}">
                                </picture>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-active">暱稱</th>
                        <td>{{ $opposite_game_info->nickname }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">偶像</th>
                        <td>{{ $opposite_game_info->GameCharacter->tc_name }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">人氣</th>
                        <td colspan="2">{{ number_format($opposite_game_info->popularity) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">名聲</th>
                        <td colspan="2">{{ number_format($opposite_game_info->reputation) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">最大生命值</th>
                        <td colspan="2">{{ number_format($opposite_game_info->max_vitality) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">精力</th>
                        <td colspan="2">{{ number_format($opposite_game_info->energy) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">抗壓性</th>
                        <td colspan="2">{{ number_format($opposite_game_info->resistance) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">魅力</th>
                        <td colspan="2">{{ number_format($opposite_game_info->charm) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @if ($opposite_game_info->name != $self_name)
        <div class="tb">
            @if ($opposite_game_info->graduate)
                    <h3>對方已畢業</h3>
                    <div class="tb-gap" style="margin-left: -10px;">
                        <button type="button" id="send-blade" class="btn btn-bottom btn-info" disabled>寄刀片</button>
                    </div>
            @else
                    <h3>操作</h3>
                    <div class="Msg" style="display:none;" id="Msg"></div>
                    <div class="tb-gap" style="margin-left: -10px;">
                        <button type="button" id="send-blade" class="btn btn-bottom btn-info">寄刀片</button>
                    </div>
            @endif
        </div>

        <div class="tb" id="get_information">
            <h3>獲得情報</h3>
        </div>
    @endif
@endsection
