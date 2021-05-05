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

<style scoped>
.show-hide-toggle-button {
    margin-top: .7rem;
}

.show-hide-toggle-button > input[type=checkbox] {
    display: none;
}

.show-hide-toggle-button > label{
    width: 1.2rem;
    height: 1rem;
    text-indent: -9999px;
    -webkit-background-image: url('https://f000.backblazeb2.com/file/idolmoment/password-toggle-button/hide-v0.0.1.svg');
            background-image: url('https://f000.backblazeb2.com/file/idolmoment/password-toggle-button/hide-v0.0.1.svg');
    background-repeat: no-repeat;
    -webkit-background-size: 18px 18px;
            background-size: 18px 18px;
    -webkit-transition: all 0.2s ease 0s;
            transition: all 0.2s ease 0s;
    cursor: pointer;
}

.show-hide-toggle-button > input:checked + label {
    -webkit-background-image: url('https://f000.backblazeb2.com/file/idolmoment/password-toggle-button/show-v0.0.1.svg');
            background-image: url('https://f000.backblazeb2.com/file/idolmoment/password-toggle-button/show-v0.0.1.svg');
}
</style>

