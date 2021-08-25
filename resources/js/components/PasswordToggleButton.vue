<template>
    <div class="show-hide-toggle-button">
        <input type="checkbox" :id="id_name" v-on:click="click_event" />
        <label style="margin-bottom: 0;" :for="id_name" :title="pw_toggle(show).title">Toggle</label>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    props: ['click_event', 'show', 'id_name'],
    computed: mapGetters([
        'pw_toggle'
    ])
}
</script>

<style scoped lang="scss">
    .show-hide-toggle-button {
        display: flex;
        align-items: center;

        > input[type=checkbox] {
            display: none;

            &:checked + label {
                -webkit-background-image: url('https://ik.imagekit.io/7bjbvrubevy/password-toggle-button/show-v0.0.1');
                background-image: url('https://ik.imagekit.io/7bjbvrubevy/password-toggle-button/show-v0.0.1');
            }
        }

        > label {
            width: 1.2rem;
            height: 1rem;
            text-indent: -9999px;
            -webkit-background-image: url('https://ik.imagekit.io/7bjbvrubevy/password-toggle-button/hide-v0.0.1');
            background-image: url('https://ik.imagekit.io/7bjbvrubevy/password-toggle-button/hide-v0.0.1');
            background-repeat: no-repeat;
            -webkit-background-size: 18px 18px;
            background-size: 18px 18px;
            -webkit-transition: all 0.2s ease 0s;
            transition: all 0.2s ease 0s;
            cursor: pointer;
        }
    }
</style>

