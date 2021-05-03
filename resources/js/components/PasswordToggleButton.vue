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

