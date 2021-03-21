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
                    <a style="font-size: 12px"
                       href=""></a>
                </td>
                <td>{{  }}</td>
                <td style="width: 160px;">{{  }}</td>
            </tr>
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
</template>

<script>
    export default {
        data() {
            return {
                selected_character: "",
                chat_messages: [],
            }
        },
        activated() {

        },
        methods: {

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
