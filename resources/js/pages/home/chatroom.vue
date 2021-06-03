<template>
    <div class="tb" v-on:keyup.enter="create_message">
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
                    <router-link style="font-size: 12px;" :to="{ name: 'profile', params: { name: chat_message.name } }"
                                 exact>
                        {{ chat_message.nickname }}
                    </router-link>
                </td>
                <td>{{ chat_message.message }}</td>
                <td style="width: 160px;">{{ TimeStamp2DateTime(chat_message.created_at) }}</td>
            </tr>
            </tbody>
        </table>
        <msg v-if="chat_ban.time" style="margin-bottom: 10px;">剩餘時間：{{ chat_ban.time }}</msg>
        <div class="setting">
            <input type="text" class="form-control" v-model="message"/>
            <button type="button" class="btn btn-primary" style="width: 15%;" v-on:click="create_message"
                    :disabled="chat_ban.status">送出
            </button>
        </div>
    </div>
</template>

<script>
import {msg} from '../../styles';
import {mapActions, mapState} from "vuex";

var put_bottom = true;
var messages_updated = false;

export default {
    data: function () {
        return {
            message: "",
            chat_messages: [],
        }
    },
    components: {
        msg
    },
    computed: {
        ...mapState([
            'api_prefix',
        ]),
        ...mapState({
            chat_ban: state => state.ban_type.chat
        })
    },
    updated: function () {
        this.tbody_scroll_bottom();
    },
    mounted: function () {
        this.tbody_scroll_position();
    },
    activated: function () {
        this.cool_down_rec({name: 'chat'});
        this.get_chats();
        this.websocket_chat_event();
    },
    deactivated: function () {
        Echo.leave('public-chat-channel');
    },
    methods: {
        ...mapActions([
            'load_my_profile',
            'cool_down_rec'
        ]),
        tbody_scroll_bottom: function () {
            if (messages_updated) {
                if (put_bottom) {
                    const tbody = document.getElementsByTagName('tbody')[0];

                    tbody.scrollTop = tbody.scrollHeight;
                }
                messages_updated = false;
            }
        },
        tbody_scroll_position: function () {
            const tbody = document.getElementsByTagName('tbody')[0];

            tbody.onscroll = function () {
                let last = tbody.scrollHeight - tbody.scrollTop;
                put_bottom = last <= tbody.offsetHeight;
            };
        },
        get_chats: function () {
            const url = this.api_prefix.concat('get-chats');
            axios.get(url)
                .then((res) => {
                    if (res.status) {
                        this.chat_messages = res.chat_messages;
                    }
                })
                .then(() => {
                    const tbody = document.getElementsByTagName('tbody')[0];
                    tbody.scrollTop = tbody.scrollHeight;
                })
        },
        websocket_chat_event: function () {
            Echo.channel('public-chat-channel')
                .listen('.public-chat-event', ({name, nickname, message, chat_created_at}) => {
                    console.log("DEBUG", "新訊息", {
                        name: name,
                        nickname: nickname,
                        message: message,
                        created_at: chat_created_at
                    })

                    this.chat_messages.push({
                        name: name,
                        nickname: nickname,
                        message: message,
                        created_at: chat_created_at
                    });

                    if (this.chat_messages.length > 100)
                        this.chat_messages.shift();

                    messages_updated = true;
                });
        },
        create_message: function () {
            if (this.chat_ban.status)
                return;

            let url = this.api_prefix.concat('create-message');
            this.chat_ban.status = true;

            axios.post(url, {
                message: this.message,
            }).then((res) => {
                if (res.status) {
                    this.cool_down_rec({name: 'chat', time: res.chat_time});
                }
            }).catch(() => {
                this.load_my_profile().then(() => {
                    this.cool_down_rec({name: 'chat'});
                });
            });
            this.message = "";
        },
        TimeStamp2DateTime: function (time) {
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
}

table thead, tbody tr {
    display: table;
    width: 100%;
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
