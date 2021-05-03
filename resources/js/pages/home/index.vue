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
                        <Avatar
                            :class_name="'img-big'"
                            :img_file_name="profile.game_character.img_file_name"
                            :img_name="profile.game_character.tc_name"
                        />
                    </td>
                </tr>
                <tr>
                    <th class="table-active">偶像</th>
                    <td>{{ profile.game_character.tc_name }}</td>
                </tr>
                <tr>
                    <th class="table-info">人氣</th>
                    <td colspan="2">{{ NumberFormat(profile.popularity) }}
                        <div style="display: inline" v-if="next_ability.popularity">
                            → {{ NumberFormat(next_ability.popularity) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">名聲</th>
                    <td colspan="2">{{ NumberFormat(profile.reputation) }}
                        <div style="display: inline" v-if="next_ability.reputation">
                            → {{ NumberFormat(next_ability.reputation) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">最大生命值</th>
                    <td colspan="2">{{ NumberFormat(profile.max_vitality) }}
                        <div style="display: inline" v-if="next_ability.max_vitality">
                            → {{ NumberFormat(next_ability.max_vitality) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">目前生命值</th>
                    <td colspan="2">{{ NumberFormat(profile.current_vitality) }}
                        <div style="display: inline" v-if="next_ability.current_vitality">
                            → {{ NumberFormat(next_ability.current_vitality) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">精力</th>
                    <td colspan="2">{{ NumberFormat(profile.energy) }}
                        <div style="display: inline" v-if="next_ability.energy">
                            → {{ NumberFormat(next_ability.energy) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">抗壓性</th>
                    <td colspan="2">{{ NumberFormat(profile.resistance) }}
                        <div style="display: inline" v-if="next_ability.resistance">
                            → {{ NumberFormat(next_ability.resistance) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-info">魅力</th>
                    <td colspan="2">{{ NumberFormat(profile.charm) }}
                        <div style="display: inline" v-if="next_ability.charm">
                            → {{ NumberFormat(next_ability.charm) }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-secondary">轉生次數</th>
                    <td colspan="2">{{ NumberFormat(profile.rebirth_counter) }}</td>
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
                    <input placeholder="最多30個字" type="text" class="form-control" maxlength="30"
                           :class="disabled_class(class_signature_disabled)"
                           v-model="signature" v-on:input="ban_signature"/>
                    <button type="button" class="btn btn-primary"
                            :disabled="signature_disabled" v-on:click="set_signature">更新
                    </button>
                </div>
                <div class="setting">
                    <label style="width: 80px;margin-bottom: 0;">貼貼</label>
                    <input type="text" maxlength="12" class="form-control" style="transition: all 0.2s ease 0s;"
                           :class="disabled_class(!teetee_info.status)" v-model="teetee" />
                    <button type="button" class="btn btn-primary" :class="{ 'btn-loading':teetee_updating }"
                            v-on:click="set_teetee">設定
                    </button>
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
import {mapState, mapGetters, mapMutations, mapActions} from "vuex";
import Avatar from "../../components/Avatar";

const legalityKey = new RegExp("^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9 ]+$");

export default {
    data: function () {
        return {
            class_signature_disabled: false,
            teetee_updating: false,
            signature: null,
            teetee: null,
            next_ability: {}
        }
    },
    components: {
        msg,
        Avatar
    },
    computed: {
        ...mapState([
            "profile",
            "teetee_info",
            "cool_down",
            "api_prefix",
            "prompt_count",
        ]),
        ...mapState({
            signature_disabled: state => state.ban_type.signature.status,
            activity_disabled: state => state.ban_type.activity.status,
            cooperation_disabled: state => state.ban_type.cooperation.status,
            signature_ban: state => state.ban_type.signature,
            activity_ban: state => state.ban_type.activity,
            cooperation_ban: state => state.ban_type.cooperation
        }),
        ...mapGetters([
            'NumberFormat',
            'disabled_class'
        ])
    },
    beforeRouteLeave: function (to, from, next) {
        Object.keys(this.next_ability).forEach((key) => {
            if (this.next_ability[key])
                this.profile[key] = this.next_ability[key];
        });

        next();
    },
    watch: {
        prompt_count: function () {
            this.next_ability = {};
        }
    },
    activated: function () {
        this.next_ability = {};

        this.signature = this.profile.signature;
        this.teetee = this.profile.teetee;

        if (!this.first_load) {
            this.load_my_profile().then(() => {
                this.signature = this.profile.signature;
                this.teetee = this.profile.teetee;

                this.cool_down_func('activity');
                this.cool_down_func('cooperation');
                this.cool_down_func('signature');
            });
        } else {
            this.cool_down_func('activity');
            this.cool_down_func('cooperation');
            this.cool_down_func('signature');
        }
    },
    methods: {
        ...mapMutations({
            cool_down_func: 'cool_down'
        }),
        ...mapActions([
            'load_my_profile'
        ]),
        set_teetee: function () {
            if (this.profile.teetee !== null) {
                if (this.profile.teetee.length > 12 || !this.profile.teetee.match(legalityKey) && this.profile.teetee.length !== 0)
                    return;
            }

            const url = this.api_prefix.concat('update-teetee');

            this.teetee_updating = true;

            axios.patch(url, {
                teetee: this.teetee,
            }).then((res) => {
                if (res.status) {
                    this.profile.teetee = this.teetee;
                    this.teetee_info.status = res.teetee_status;
                    this.teetee_info.teetee_name = res.teetee_name;
                }
            }).finally(() => {
                this.teetee_updating = false;
            })
        },
        set_signature: function () {
            if (this.signature_disabled)
                return;

            const url = this.api_prefix.concat('update-signature');
            this.signature_ban.status = true;

            axios.patch(url, {
                signature: this.signature
            }).then((res) => {
                if (res.signature_time) {
                    this.profile.signature = this.signature;
                    this.cool_down.signature = res.signature_time;
                    this.cool_down_func('signature');
                } else {
                    this.signature_ban.status = false;
                }
            }).catch(() => {
                this.signature_ban.status = false;
            });
        },
        ban_signature: function () {
            if (this.signature_ban.time)
                return;

            this.signature_ban.status = this.class_signature_disabled = this.signature && !this.signature.match(legalityKey) || this.signature.length > 30;
        },
        do_activity: function (activity_type) {
            if (this.activity_disabled)
                return;

            const url = this.api_prefix.concat('activity');
            this.activity_ban.status = true;

            axios.patch(url, {
                activity_type: activity_type,
            }).then((res) => {
                if (res.activity_time) {
                    this.cool_down.activity = res.activity_time;
                    this.cool_down_func('activity');
                } else {
                    this.activity_ban.status = false;
                }

                if (res.status) {
                    Object.keys(res.ability).forEach((key) => {
                        this.profile[key] = this.next_ability[key] ? this.next_ability[key] : this.profile[key];
                        this.next_ability[key] = this.profile[key] !== res.ability[key] ? res.ability[key] : null;
                    });
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
            }).then((res) => {
                if (res.cooperation_time) {
                    this.cool_down.cooperation = res.cooperation_time;
                    this.cool_down_func('cooperation');
                } else {
                    this.cooperation_ban.status = false;
                }

                if (res.status) {
                    Object.keys(res.ability).forEach((key) => {
                        this.profile[key] = this.next_ability[key] ? this.next_ability[key] : this.profile[key];
                        this.next_ability[key] = this.profile[key] !== res.ability[key] ? res.ability[key] : null;
                    });
                }
            }).catch(() => {
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
