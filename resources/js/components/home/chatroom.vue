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
                    <router-link style="font-size: 12px;" :to="{ name: 'profile', params: { name: chat_message.name } }" exact>{{ chat_message.nickname }}</router-link>
                </td>
                <td>{{ chat_message.message }}</td>
                <td style="width: 160px;">{{ timeStampToDateTime(chat_message.created_at) }}</td>
            </tr>
            </tbody>
        </table>
        <msg v-if="chat_ban.time" style="margin-bottom: 10px;">剩餘時間：{{ chat_ban.time }}</msg>
        <div class="setting">
            <input type="text" class="form-control" v-model="message"/>
            <button type="button" class="btn btn-primary" style="width: 15%;" v-on:click="create_message" :disabled="create_msg_disabled">送出</button>
        </div>
    </div>
</template>

<script>
import {msg} from '../../styles';

var put_bottom = true;
var messages_updated = false;

export default {
    data() {
        return {
            message: "",
            chat_messages: [],
        }
    },
    components: {
        msg
    },
    computed: {
        chat_ban: function () {
            return this.$store.state.ban_type.chat;
        },
        create_msg_disabled: function () {
            return this.$store.state.ban_type.chat.status;
        },
        api_prefix: function () {
            return this.$store.state.api_prefix
        },
        cool_down: function () {
            return this.$store.state.cool_down;
        },
    },
    updated() {
        if (messages_updated) {
            if (put_bottom) {
                const tbody = document.getElementsByTagName('tbody')[0];

                tbody.scrollTop = tbody.scrollHeight;
            }
            messages_updated = false;
        }
    },
    mounted() {
        const tbody = document.getElementsByTagName('tbody')[0];

        tbody.onscroll = function () {
            let last = tbody.scrollHeight - tbody.scrollTop;
            put_bottom = last <= tbody.offsetHeight;
        };

        Echo.channel('public-chat-channel')
            .listen('.public-chat-event', ({name, nickname, message, chat_created_at}) => {
                if (this.$route.name === 'chatroom') {

                    this.chat_messages.push({
                        name: name,
                        nickname: nickname,
                        message: message,
                        created_at: chat_created_at
                    });

                    messages_updated = true;
                }
            });
    },
    activated() {
        document.title = "聊天室";

        const url = this.api_prefix.concat('chat');
        this.$store.commit('cool_down', 'chat');

        axios.get(url)
            .then(({status, chat_messages}) => {
                if (status) {
                    this.chat_messages = chat_messages;
                }
            })
            .then(() => {
                const tbody = document.getElementsByTagName('tbody')[0];
                tbody.scrollTop = tbody.scrollHeight;
            })
    },
    methods: {
        create_message: function () {
            if (this.create_msg_disabled)
                return;

            let url = this.api_prefix.concat('create-message');
            this.chat_ban.status = true;

            axios.post(url, {
                message: this.message,
            }).then(({status, chat_time}) => {
                if (status) {
                    this.cool_down.chat = chat_time;
                    this.$store.commit('cool_down', 'chat');
                } else {
                    this.chat_ban.status = false;
                }
            }).catch((err) => {
                this.chat_ban.status = false;
            });
            this.message = "";
        },
        timeStampToDateTime: function (time) {
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
