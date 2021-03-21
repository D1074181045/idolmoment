@extends('home.app')

@section('title', '聊天室')

@section('styles')
    <style type="text/css">
        table tbody {
            display: block;
            max-height: 500px;
            overflow-y: scroll;
            -webkit-appearance: none;
        }

        table thead, tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        table tbody tr td {
            word-break: break-all;
        }

        table thead {
            width: calc(100% - 1em)
        }

        @media screen and (max-width: 600px) {
            table thead {
                width: calc(100%)
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        var put_bottom = true;

        const create_message = $('#create-message');
        const message = $('#message');
        const Msg = $("#Msg");
        const tbody = $("tbody");
        const chat_time = StringToDate('{{ $chat_time }}');

        create_message.on('click', () => {
            if (ban_type.chat)
                return;

            create_message.prop('disabled', true);

            axios.post('{{ Route('api.create.message') }}', {
                message: message.val(),
            }).then(({status, chat_time}) => {
                if (status) {
                    CoolDown_Time(Msg, new Date(chat_time), create_message, 'chat');
                } else {
                    create_message.prop('disabled', false);
                }
            })

            message.val('');
        })

        $(document).on('keyup', function (e) {
            if ((e.key === 'Enter' || e.keyCode === 13) && !create_message.prop("disabled")) {
                create_message.trigger('click').focus();
            }
        });

        tbody.scroll(function () {
            let last = tbody[0].scrollHeight - tbody.scrollTop();

            put_bottom = last <= tbody.height();
        });

        $(function () {
            Echo.channel('public-chat-channel')
                .listen('.public-chat-event', ({name, nickname, message, chat_created_at}) => {
                    let profile_path = '{{ Route('home.profile', '') }}'.concat('/', name);
                    let message_t = message ? message : '';

                    let td = '<td style="width: 150px;"><a style="font-size: 12px" href="' + profile_path + '">' + nickname + '</a></td>';
                    td += '<td>' + message_t + '</td>';
                    td += '<td style="width: 160px;">' + chat_created_at + '</td>';

                    $('tbody').append('<tr>' + td + '</tr>')

                    if (put_bottom)
                        $("tbody").scrollTop(tbody[0].scrollHeight);
                });

            tbody.scrollTop(tbody[0].scrollHeight);
            CoolDown_Time(Msg, chat_time, $('#create-message'), 'chat');
        })
    </script>
@endsection

@section('content')
    <div class="tb">
        <h3>聊天室</h3>
        <table class="table table-hover" style="margin-top: 1rem;">
            <thead class="thead-light">
            <tr>
                <th style="width: 150px;">暱稱</th>
                <th>訊息</th>
                <th style="width: 160px;">時間</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($chat_messages as $chat_message)
                <tr>
                    <td style="width: 150px;">
                        <a style="font-size: 12px"
                           href="{{ Route('home.profile', $chat_message->name) }}">{{ $chat_message->GameInfo['nickname'] ?: '暱稱顯示失敗' }}</a>
                    </td>
                    <td>{{ $chat_message->message }}</td>
                    <td style="width: 160px;">{{ $chat_message->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="Msg" style="display:none;" id="Msg"></div>
        <div class="setting">
            <input type="text" name="message" id="message" class="form-control"/>
            <button type="button" id="create-message" name="create-message" class="btn btn-primary" style="width: 15%;">
                送出
            </button>
        </div>
    </div>
@endsection
