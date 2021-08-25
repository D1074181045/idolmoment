<template>
    <div style="display: flex; height: 19px;flex-wrap: nowrap;justify-content: space-evenly;" class="text-center">
        <a :style="[color('like', like_select), cursor(can_seed)]"
           v-on:click="click_like('like')">
            <button class="like" :style="[fill('like', like_select), cursor(can_seed)]" >
                <svg viewBox="0 0 24 24" style="display: block; width: 19px; height: 19px;">
                    <g>
                        <path
                            d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-1.91l-.01-.01L23 10z"></path>
                    </g>
                </svg>
            </button>
            {{ NumberFormat(like_num, 'zh-TW') }}
        </a>
        <a :style="[color('dislike', like_select), cursor(can_seed)]"
           v-on:click="click_like('dislike')" style="margin-left: -10px;">
            <button class="like" :style="[fill('dislike', like_select), cursor(can_seed)]">
                <svg viewBox="0 0 24 24" style="display: block; width: 19px; height: 19px;">
                    <g>
                        <path
                            d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v1.91l.01.01L1 14c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"></path>
                    </g>
                </svg>
            </button>
            {{ NumberFormat(dislike_num, 'zh-TW') }}
        </a>
    </div>
</template>

<script>
import {mapGetters, mapState} from 'vuex';

export default {
    props: ['like_num', 'dislike_num', 'like_select', 'like_name', 'can_seed'],
    computed: {
        ...mapState([
            "api_prefix",
        ]),
        ...mapGetters([
            'NumberFormat',
        ])
    },
    methods: {
        cursor: function (can_seed) {
            return { cursor: can_seed === true ? 'pointer' : 'default' };
        },
        fill: function (type, select) {
            switch (type) {
                case "like":
                    return { fill: select === 'like' ? '#1564ff !important' : '#909090 !important' };
                case "dislike":
                    return { fill: select === 'dislike' ? '#ff4242 !important' : '#909090 !important' };
            }
        },
        color: function (type, select) {
            switch (type) {
                case "like":
                    return { color: select === 'like' ? '#1564ff !important' : '#909090 !important' };
                case "dislike":
                    return { color: select === 'dislike' ? '#ff4242 !important' : '#909090 !important' };
            }
        },
        click_like: function (type) {
            if (!this.can_seed) return;

            let url = this.api_prefix.concat('like');

            if (this.like_select === type) type = 'removelike';

            axios.post(url, {
                type: type,
                opposite_name: this.like_name
            }).then((res) => {
                this.$emit('like_event', {
                    'like_select': res.like_select,
                    'opposite_like_num': res.opposite_like_num,
                    'opposite_dislike_num': res.opposite_dislike_num
                });
            });
        },
    }
}
</script>

<style scoped>
    button.like {
        background: none;
        border: none;
        cursor: pointer;
        display: block;
        width: 19px;
        outline: none;
        margin: 0 8px;
        padding: unset;
    }

    a {
        cursor: pointer;
        display: flex;
        align-items: center;
        color: #909090 !important;
    }
</style>

