<template>
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
            <tr v-for="chat_message in chat_messages">
                <td style="width: 150px;">
                    <a style="font-size: 12px" href="">{{ chat_message.nickname }}</a>
                </td>
                <td>{{ chat_message.message }}</td>
                <td style="width: 160px;">{{ timeStamp2String(chat_message.created_at) }}</td>
            </tr>
            </tbody>
        </table>
        <msg v-if="chat_cool_down.time">剩餘時間：{{ chat_cool_down.time }}</msg>
        <div class="setting">
            <input type="text" class="form-control" v-model="message"/>
            <button type="button" class="btn btn-primary" style="width: 15%;" v-on:click="create_message"
                    :disabled="create_msg_disabled">
                送出
            </button>
        </div>
    </div>
</template>

<script>
import {msg} from '../../styles';

export default {
    data() {
        return {
            message: "",
            chat_messages: [],
        }
    },
    mounted() {
    },
    components: {
        msg
    },
    computed: {
        chat_cool_down: function () {
            return this.$store.state.ban_type.chat;
        },
        create_msg_disabled: function () {
            return this.$store.state.ban_type.chat.status;
        },
    },
    activated() {
        this.$store.commit('cool_down', 'chat');

        const tbody = $("tbody");
        var put_bottom = true;

        Echo.channel('public-chat-channel')
            .listen('.public-chat-event', ({name, nickname, message, chat_created_at}) => {
                if (this.$route.name === 'chatroom') {
                    let profile_path = '/profile/'.concat(name);
                    let message_t = message ? message : '';

                    let td = '<td style="width: 150px;"><a style="font-size: 12px" href="' + profile_path + '">' + nickname + '</a></td>';
                        td += '<td>' + message_t + '</td>';
                        td += '<td style="width: 160px;">' + chat_created_at + '</td>';

                    $('tbody').append('<tr style="display: table;width: 100%;table-layout: fixed">' + td + '</tr>')

                    if (put_bottom)
                        $("tbody").scrollTop(tbody.get(0).scrollHeight);
                }
            });

        tbody.scroll(function () {
            let last = tbody[0].scrollHeight - tbody.scrollTop();

            put_bottom = last <= tbody.height();
        });

        tbody.scrollTop(tbody.get(0).scrollHeight);

        axios.get(this.api_prefix.concat('chat'))
            .then(({status, chat_messages}) => {
                if (status) {
                    this.chat_messages = chat_messages;
                }
            })
    },
    methods: {
        create_message: function () {
            if (this.create_msg_disabled)
                return;

            let url = this.api_prefix.concat('create-message');

            this.$store.state.ban_type.chat.status = true;

            axios.post(url, {
                message: this.message,
            }).then(({status, chat_time}) => {
                if (status) {
                    this.$store.state.cool_down.chat = chat_time;
                    this.$store.commit('cool_down', 'chat');
                } else {
                    this.$store.state.ban_type.chat.status = false;
                }
            })
            this.message = "";
        },
        timeStamp2String: function (time) {
            let datetime = new Date(time);

            let year = datetime.getFullYear();
            let month = datetime.getMonth() + 1;
            month = month < 10 ? '0' + month.toString() : month;
            let date = datetime.getDate();
            date = date < 10 ? '0' + date.toString() : date;
            let hour = datetime.getHours();
            hour = hour < 10 ? '0' + hour.toString() : hour;
            let minute = datetime.getMinutes();
            minute = minute < 10 ? '0' + minute.toString() : minute;
            let second = datetime.getSeconds();
            second = second < 10 ? '0' + second.toString() : second;

            return year + "-" + month + "-" + date + " " + hour + ":" + minute + ":" + second;
        }
    }
}
</script>

<style scoped>
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
