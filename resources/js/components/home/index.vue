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
                                <source type="image/webp"
                                        :srcset="characters_img_path(profile.game_character.img_file_name, 'webp')">
                                <source type="image/jpeg"
                                        :srcset="characters_img_path(profile.game_character.img_file_name)">
                                <source type="image/png"
                                        :srcset="characters_img_path(profile.game_character.img_file_name, 'png')">
                                <img
                                    :src="characters_img_path(profile.game_character.img_file_name)"
                                    :alt="profile.game_character.tc_name"
                                    v-on:error="img_error">
                            </picture>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-active">偶像</th>
                    <td>{{ profile.game_character.tc_name }}</td>
                </tr>
                <tr>
                    <th class="table-info">人氣</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.popularity) }}
                        <div style="display: inline" v-if="next_ability.popularity">
                            → {{ $store.getters.NumberFormat(next_ability.popularity) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">名聲</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.reputation) }}
                        <div style="display: inline" v-if="next_ability.reputation">
                            → {{ $store.getters.NumberFormat(next_ability.reputation) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">最大生命值</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.max_vitality) }}
                        <div style="display: inline" v-if="next_ability.max_vitality">
                            → {{ $store.getters.NumberFormat(next_ability.max_vitality) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">目前生命值</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.current_vitality) }}
                        <div style="display: inline" v-if="next_ability.current_vitality">
                            → {{ $store.getters.NumberFormat(next_ability.current_vitality) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">精力</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.energy) }}
                        <div style="display: inline" v-if="next_ability.energy">
                            → {{ $store.getters.NumberFormat(next_ability.energy) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">抗壓性</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.resistance) }}
                        <div style="display: inline" v-if="next_ability.resistance">
                            → {{ $store.getters.NumberFormat(next_ability.resistance) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">魅力</th>
                    <td colspan="2">{{ $store.getters.NumberFormat(profile.charm) }}
                        <div style="display: inline" v-if="next_ability.charm">
                            → {{ $store.getters.NumberFormat(next_ability.charm) }}
                        </div>
                    </td>
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
            <msg v-if="signature_ban.time">剩餘時間：{{ signature_ban.time }}</msg>
            <div class="tb-gap">
                <div class="setting">
                    <label style="width: 80px;margin-bottom: 0;">簽名檔</label>
                    <input placeholder="最多30個字" type="text" class="form-control"
                           :class="$store.getters.disabled_class(class_signature_disabled)"
                           v-model="profile.signature" v-on:input="ban_signature"/>
                    <button type="button" class="btn btn-primary"
                            :disabled="signature_disabled" v-on:click="set_signature">更新
                    </button>
                </div>
                <div class="setting">
                    <label style="width: 80px;margin-bottom: 0;">貼貼</label>
                    <input type="text" maxlength="12" class="form-control" v-model="profile.teetee"
                           :class="$store.getters.disabled_class(!teetee_info.status)"/>
                    <button type="button" class="btn btn-primary" v-on:click="set_teetee">設定</button>
                </div>
            </div>
        </div>
        <div class="tb" v-if="!profile.graduate">
            <h3>個人活動</h3>
            <msg v-if="activity_ban.time">剩餘時間：{{ activity_ban.time }}</msg>
            <div class="tb-gap" style="margin-left: -10px;">
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('adult-live')">成人直播
                </button>
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('live')">直播
                </button>
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('do-good-things')">做善事
                </button>
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('go-to-sleep')">睡覺
                </button>
                <button type="button" class="btn btn-bottom btn-info" :disabled="activity_disabled"
                        v-on:click="do_activity('meditation')">打坐
                </button>
            </div>
            <h3 style="padding-top: 15px;border-top: 1px solid var(--border-color);">合作活動</h3>
            <msg v-if="cooperation_ban.time">剩餘時間：{{ cooperation_ban.time }}</msg>
            <div class="tb-gap" style="margin-left: -10px;">
                <button type="button" class="btn btn-bottom btn-info"
                        :disabled="cooperation_disabled ||　!teetee_info.status || teetee_info.teetee_graduate"
                        v-on:click="do_cooperation('play-ordinary-game')">玩普通遊戲
                </button>
                <button type="button" class="btn btn-bottom btn-info"
                        :disabled="cooperation_disabled ||　!teetee_info.status || teetee_info.teetee_graduate"
                        v-on:click="do_cooperation('play-tacit-game')">玩默契遊戲
                </button>
            </div>
        </div>
        <div class="tb" v-else>
            <h3>已畢業，無法進行任何活動</h3>
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
import {msg} from '../../styles';

const legalityKey = new RegExp("^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9 ]+$");

export default {
    data() {
        return {
            class_signature_disabled: false,
            next_ability: {}
        }
    },
    components: {
        msg
    },
    computed: {
        signature: function () {
            return this.profile.signature;
        },
        signature_disabled: function () {
            return this.$store.state.ban_type.signature.status;
        },
        activity_disabled: function () {
            return this.$store.state.ban_type.activity.status;
        },
        cooperation_disabled: function () {
            return this.$store.state.ban_type.cooperation.status;
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
        signature_ban: function () {
            return this.$store.state.ban_type.signature;
        },
        activity_ban: function () {
            return this.$store.state.ban_type.activity;
        },
        cooperation_ban: function () {
            return this.$store.state.ban_type.cooperation;
        },
        api_prefix: function () {
            return this.$store.state.api_prefix
        },
        Prompt: function () {
            return this.$store.state.prompt_count
        }
    },
    beforeRouteLeave(to, from, next) {
        Object.keys(this.next_ability).forEach((key) => {
            if (this.next_ability[key])
                this.profile[key] = this.next_ability[key];
        });

        next();
    },
    watch: {
        Prompt: function () {
            this.next_ability = {};
        }
    },
    mounted() {
        this.$store.commit('cool_down', 'activity');
        this.$store.commit('cool_down', 'cooperation');
        this.$store.commit('cool_down', 'signature');
    },
    activated() {
        document.title = "我的偶像";

        this.next_ability = {};

        if (!this.first_load)
            this.$store.dispatch('load_my_profile');
    },
    methods: {
        set_teetee: function (e) {
            if (this.profile.teetee !== null) {
                if (this.profile.teetee.length > 12 || !this.profile.teetee.match(legalityKey) && this.profile.teetee.length !== 0)
                    return;
            }

            const url = this.api_prefix.concat('update-teetee');
            e.target.className += " ".concat("btn-loading");

            axios.patch(url, {
                teetee: this.profile.teetee,
            }).then(({teetee_status, teetee_name}) => {
                this.teetee_info.status = teetee_status;
                this.teetee_info.teetee_name = teetee_name;
            }).finally(() => {
                e.target.className = "btn btn-primary";
            })
        },
        set_signature: function () {
            if (this.signature_disabled)
                return;

            const url = this.api_prefix.concat('update-signature');
            this.signature_ban.status = true;

            axios.patch(url, {
                signature: this.signature
            }).then(({status, signature_time}) => {
                if (status) {
                    this.cool_down.signature = signature_time;
                    this.$store.commit('cool_down', 'signature');
                } else {
                    this.signature_ban.status = false;
                }
            }).catch((err) => {
                this.signature_ban.status = false;
            });
        },
        ban_signature: function () {
            if (this.signature_ban.time)
                return;

            this.signature_ban.status = this.signature && !this.signature.match(legalityKey) || this.signature.length > 30;
            this.class_signature_disabled = this.signature && !this.signature.match(legalityKey) || this.signature.length > 30;
        },
        do_activity: function (activity_type) {
            if (this.activity_disabled)
                return;

            const url = this.api_prefix.concat('activity');
            this.activity_ban.status = true;

            axios.patch(url, {
                activity_type: activity_type,
            }).then(({status, ability, activity_time}) => {
                if (status) {
                    this.cool_down.activity = activity_time;
                    this.$store.commit('cool_down', 'activity');

                    Object.keys(ability).forEach((key) => {
                        this.profile[key] = this.next_ability[key] ? this.next_ability[key] : this.profile[key];
                        this.next_ability[key] = this.profile[key] !== ability[key] ? ability[key] : null;
                    });

                } else {
                    this.activity_ban.status = false;
                }
            }).catch((err) => {
                this.activity_ban.status = false;
            });
        },
        do_cooperation: function (cooperation_type) {
            if (this.cooperation_disabled || !this.teetee_info.status)
                return;

            const url = this.api_prefix.concat('cooperation');
            this.cooperation_ban.status = true;

            axios.patch(url, {
                cooperation_type: cooperation_type,
            }).then(({status, ability, cooperation_time}) => {
                if (status) {
                    this.cool_down.cooperation = cooperation_time;
                    this.$store.commit('cool_down', 'cooperation');

                    Object.keys(ability).forEach((key) => {
                        this.profile[key] = this.next_ability[key] ? this.next_ability[key] : this.profile[key];
                        this.next_ability[key] = this.profile[key] !== ability[key] ? ability[key] : null;
                    });

                } else {
                    this.cooperation_ban.status = false;
                }
            }).catch((err) => {
                this.cooperation_ban.status = false;
            });
        }
    }
}
</script>

<style scoped>
input[type="text"] {
    width: 80%;
}

button.btn.btn-bottom {
    margin-bottom: 12px;
    margin-right: 3px;
}
</style>
