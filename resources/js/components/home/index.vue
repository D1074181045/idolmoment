<template>
    <div>
        <div class="tb">
            <h3>我的偶像</h3>
            <table class="table">
                <tbody>
                <tr>
                    <th class="table-active">暱稱</th>
                    <td>{{ profile.nickname }}</td>
                    <td rowspan="2" style="width: 80px;">
                        <div class="img-big">
                            <picture>
                                <source type="image/png"
                                        :srcset="characters_img_path(profile.use_character.img_file_name)">
                                <img
                                    :src="characters_img_path(profile.use_character.img_file_name)"
                                    :alt="characters_img_path(profile.use_character.tc_name)">
                            </picture>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-active">偶像</th>
                    <td>{{ profile.use_character.tc_name }}</td>
                </tr>
                <tr>
                    <th class="table-info">人氣</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.popularity) }}</td>
                </tr>
                <tr>
                    <th class="table-info">名聲</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.reputation) }}</td>
                </tr>
                <tr>
                    <th class="table-info">最大生命值</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.max_vitality) }}</td>
                </tr>
                <tr>
                    <th class="table-info">目前生命值</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.current_vitality) }}</td>
                </tr>
                <tr>
                    <th class="table-info">精力</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.energy) }}</td>
                </tr>
                <tr>
                    <th class="table-info">抗壓性</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.resistance) }}</td>
                </tr>
                <tr>
                    <th class="table-info">魅力</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.charm) }}</td>
                </tr>
                <tr>
                    <th class="table-secondary">轉生次數</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.rebirth_counter) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="tb">
            <h3>個人設定</h3>
            <msg v-if="signature_cool_down.time">剩餘時間：{{ signature_cool_down.time }}</msg>
            <div class="tb-gap">
                <div class="setting">
                    <label style="width: 80px;margin-bottom: 0;">簽名檔</label>
                    <input placeholder="最多30個字" type="text" class="form-control"
                           :class="$store.getters.disabled_class(c_signature_disabled)"
                           v-model="profile.signature" v-on:input="ban_signature"/>
                    <button type="button" class="btn btn-info"
                            :disabled="signature_disabled" v-on:click="set_signature">更新</button>
                </div>
                <div class="setting">
                    <label style="width: 80px;margin-bottom: 0;">貼貼</label>
                    <input type="text" maxlength="12" class="form-control" v-model="profile.teetee"
                            :class="$store.getters.disabled_class(!teetee_info.status)"/>
                    <button type="button" class="btn btn-info" v-on:click="set_teetee">設定</button>
                </div>
            </div>
        </div>
        <div class="tb" v-if="!profile.graduate">
            <h3>進行活動</h3>
            <msg v-if="activity_cool_down.time">剩餘時間：{{ activity_cool_down.time }}</msg>
            <div class="tb-gap" style="margin-left: -10px;">
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('adult-live')">成人直播</button>
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('live')">直播</button>
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('do-good-things')">做善事</button>
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('go-to-sleep')">睡覺</button>
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('meditation')">打坐</button>
            </div>
        </div>
        <div class="tb" v-else>
            <h3>已畢業，無法進行活動</h3>
            <div class="tb-gap" style="margin-left: -10px;">
                <button type="button" class="btn btn-bottom btn-info" disabled>成人直播</button>
                <button type="button" class="btn btn-bottom btn-info" disabled>直播</button>
                <button type="button" class="btn btn-bottom btn-info" disabled>做善事</button>
                <button type="button" class="btn btn-bottom btn-info" disabled>睡覺</button>
                <button type="button" class="btn btn-bottom btn-info" disabled>打坐</button>
            </div>
        </div>
    </div>
</template>

<script>
import { msg } from '../../styles';
const legalityKey = new RegExp("^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9 ]+$");

export default {
    data() {
        return {
            c_signature_disabled: false,
        }
    },
    components: {
      msg
    },
    computed: {
        signature: function () {
            return this.profile.signature;
        },
        signature_disabled: function() {
            return this.$store.state.ban_type.signature.status;
        },
        activity_disabled: function() {
            return this.$store.state.ban_type.activity.status;
        },
        profile: function () {
            return this.$store.state.profile;
        },
        teetee_info: function () {
            return this.$store.state.teetee_info;
        },
        cool_down: function () {
            return this.$store.state.cool_down;
        },
        signature_cool_down: function () {
            return this.$store.state.ban_type.signature;
        },
        activity_cool_down: function () {
            return this.$store.state.ban_type.activity;
        }
    },
    mounted() {
        this.$store.commit('cool_down', 'activity');
        this.$store.commit('cool_down', 'signature');
    },
    activated() {
        if (!this.first_load) {
            this.$store.dispatch('load_my_profile');
        } else {
            this.first_load = false;
        }
    },
    methods: {
        set_teetee: function () {
            if (this.profile.teetee.length > 12 || !this.profile.teetee.match(legalityKey) && this.profile.teetee.length !== 0)
                return;

            axios.patch(this.api_prefix.concat('update-teetee'), {
                teetee: this.profile.teetee,
            }).then(({teetee_status, teetee_name}) => {
                this.$store.state.teetee_info.status = teetee_status;
                this.$store.state.teetee_info.teetee_name = teetee_name;
            })
        },
        set_signature: function () {
            if (this.signature_disabled || this.signature_cool_down.status)
                return;

            this.$store.state.ban_type.signature.status = true;

            axios.patch(this.api_prefix.concat('update-signature'), {
                signature: this.signature
            }).then(({status, signature_time}) => {
                if (status) {
                    this.$store.state.cool_down.signature = signature_time;
                    this.$store.commit('cool_down', 'signature');
                } else {
                    this.$store.state.ban_type.signature.status = false;
                }
            }).catch((err) => {
                this.$store.state.ban_type.signature.status = false;
            });
        },
        ban_signature: function () {
            if (this.$store.state.ban_type.signature.time)
                return;

            if (this.profile.signature.match(legalityKey)) {
                this.$store.state.ban_type.signature.status = this.profile.signature.length > 30;
                this.c_signature_disabled = this.profile.signature.length > 30;
            } else {
                this.$store.state.ban_type.signature.status  = this.profile.signature.length !== 0;
                this.c_signature_disabled = this.profile.signature.length !== 0;
            }
        },
        do_activity: function (activity_type) {
            if (this.$store.state.ban_type.activity.time)
                return;

            this.$store.state.ban_type.activity.status = true;

            axios.patch(this.api_prefix.concat('activity'), {
                activity_type: activity_type,
            }).then(({status, ability, activity_time}) => {
                if (status) {
                    this.$store.state.cool_down.activity = activity_time;
                    this.$store.commit('cool_down', 'activity');

                    Object.keys(ability).forEach((key) => {
                        console.log(value);

                        this.profile[key] = ability[key];
                    });
                } else {
                    this.$store.state.ban_type.activity.status = false;
                }
            }).catch((err) => {
                this.$store.state.ban_type.activity.status = false;
            });
        },
    }
}
</script>

<style scoped>
.tb .tb-gap {
    margin-top: 15px;
    margin-bottom: 10px;
}

button.btn.btn-bottom {
    margin-bottom: 12px;
    margin-right: 3px;
}
</style>
