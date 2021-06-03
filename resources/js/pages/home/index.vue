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
                    <td>{{ NumberFormat(profile.popularity) }}
                        <div style="display: inline" v-if="next_ability.popularity">
                            → {{ NumberFormat(next_ability.popularity) }}
                        </div>
                    </td>
                    <td>
                        <Like :like_num="like_num"
                              :dislike_num="dislike_num"
                              :can_seed="false"
                        />
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
                            :disabled="signature_ban.status" v-on:click="set_signature">更新
                    </button>
                </div>
                <div class="setting">
                    <label style="width: 80px;margin-bottom: 0;">貼貼</label>
                    <input type="text" maxlength="12" class="form-control" style="transition: all 0.2s ease 0s;"
                           :class="disabled_class(!teetee_info.teetee_status)" v-model="teetee"/>
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
                <button type="button" class="btn btn-bottom btn-info"
                        v-for="activity in activity_list" v-on:click="do_activity(activity.en)"
                        :disabled="activity_ban.status"
                >{{ activity.tc }}
                </button>
            </div>
            <h3 style="padding-top: 15px;border-top: 1px solid var(--border-color);">
                {{ teetee_info.teetee_graduate ? '合作活動 (貼貼已畢業)' : '合作活動' }}
            </h3>
            <msg v-if="cooperation_ban.time">剩餘時間：{{ cooperation_ban.time }}</msg>
            <div class="tb-gap" style="margin-left: -10px;">
                <button type="button" class="btn btn-bottom btn-info"
                        v-for="cooperation in cooperation_list" v-on:click="do_cooperation(cooperation.en)"
                        :disabled="cooperation_ban.status || !teetee_info.teetee_status || teetee_info.teetee_graduate"
                >{{ cooperation.tc }}
                </button>
            </div>
        </div>
        <div class="tb" v-else>
            <h3>已畢業，無法進行任何活動</h3>
            <div class="tb-gap" style="margin-left: -10px;">
                <button type="button" class="btn btn-bottom btn-info"
                        v-for="activity in activity_list" disabled>{{ activity.tc }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {msg} from '../../styles';
import {mapState, mapGetters, mapActions} from "vuex";
import Avatar from "../../components/Avatar";
import Like from "../../components/Like";

const legalityKey = new RegExp("^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9 ]+$");

export default {
    data: function () {
        return {
            activity_list: [
                {en: 'adult-live', tc: '成人直播'},
                {en: 'live', tc: '直播'},
                {en: 'do-good-things', tc: '做善事'},
                {en: 'go-to-sleep', tc: '睡覺'},
                {en: 'meditation', tc: '打坐'}
            ],
            cooperation_list: [
                {en: 'play-ordinary-game', tc: '玩普通遊戲'},
                {en: 'play-tacit-game', tc: '玩默契遊戲'},
            ],
            class_signature_disabled: false,
            teetee_updating: false,
            signature: null,
            teetee: null,
            next_ability: {}
        }
    },
    components: {
        msg,
        Avatar,
        Like
    },
    computed: {
        ...mapState([
            "like_num",
            'dislike_num',
            "profile",
            "teetee_info",
            "api_prefix",
            "prompt_count",
        ]),
        ...mapState({
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
    mounted: function () {
        this.signature = this.profile.signature;
        this.teetee = this.profile.teetee;

        if (!this.first_load) {
            this.load_my_profile().then(() => {
                this.signature = this.profile.signature;
                this.teetee = this.profile.teetee;

                this.cool_down_rec({name: 'activity'});
                this.cool_down_rec({name: 'cooperation'});
                this.cool_down_rec({name: 'signature'}).then(this.ban_signature);
            });
        } else {
            this.cool_down_rec({name: 'activity'});
            this.cool_down_rec({name: 'cooperation'});
            this.cool_down_rec({name: 'signature'}).then(this.ban_signature);
        }
    },
    methods: {
        ...mapActions([
            'load_my_profile',
            'cool_down_rec'
        ]),
        ban_signature: function () {
            if (this.signature) {
                this.class_signature_disabled = !legalityKey.test(this.signature) || this.signature.length > 30;
            } else {
                this.class_signature_disabled = false;
            }

            if (!this.signature_ban.time) {
                this.signature_ban.status = this.class_signature_disabled;
            }
        },
        set_teetee: function () {
            if (this.teetee !== null) {
                if (this.teetee.length > 12 || !legalityKey.test(this.teetee) && this.teetee.length !== 0)
                    return;
            }

            const url = this.api_prefix.concat('update-teetee');

            this.teetee_updating = true;

            axios.post(url, {
                teetee: this.teetee,
            }).then((res) => {
                if (res.status) {
                    this.profile.teetee = this.teetee;
                    this.teetee_info.teetee_status = res.teetee_status;
                    this.teetee_info.teetee_name = res.teetee_name;
                    this.teetee_info.teetee_graduate = res.teetee_graduate;
                }
            }).finally(() => {
                this.teetee_updating = false;
            })
        },
        set_signature: function () {
            if (this.signature_ban.status)
                return;

            const url = this.api_prefix.concat('update-signature');
            this.signature_ban.status = true;

            axios.post(url, {
                signature: this.signature
            }).then((res) => {
                if (res.status) {
                    this.profile.signature = this.signature;
                    this.cool_down_rec({name: 'signature', time: res.signature_time}).then(this.ban_signature);
                }
            }).catch(() => {
                this.load_my_profile().then(() => {
                    this.cool_down_rec({name: 'signature'}).then(this.ban_signature);
                });
            });
        },
        do_activity: function (activity_type) {
            if (this.activity_ban.status)
                return;

            const url = this.api_prefix.concat('activity');
            this.activity_ban.status = true;

            axios.post(url, {
                activity_type: activity_type,
            }).then((res) => {
                if (res.status) {
                    this.cool_down_rec({name: 'activity', time: res.activity_time});

                    Object.keys(res.ability).forEach((key) => {
                        this.profile[key] = this.next_ability[key] ? this.next_ability[key] : this.profile[key];
                        this.next_ability[key] = this.profile[key] !== res.ability[key] ? res.ability[key] : null;
                    });
                }
            }).catch(() => {
                this.load_my_profile().then(() => {
                    this.cool_down_rec({name: 'activity'});
                });
            });
        },
        do_cooperation: function (cooperation_type) {
            if (this.cooperation_ban.status || !this.teetee_info.teetee_status || this.teetee_info.teetee_graduate)
                return;

            const url = this.api_prefix.concat('cooperation');
            this.cooperation_ban.status = true;

            axios.post(url, {
                cooperation_type: cooperation_type,
            }).then((res) => {
                if (res.status) {
                    this.cool_down_rec({name: 'cooperation', time: res.cooperation_time});

                    Object.keys(res.ability).forEach((key) => {
                        this.profile[key] = this.next_ability[key] ? this.next_ability[key] : this.profile[key];
                        this.next_ability[key] = this.profile[key] !== res.ability[key] ? res.ability[key] : null;
                    });
                }
            }).catch(() => {
                this.load_my_profile().then(() => {
                    this.cool_down_rec({name: 'cooperation'});
                });
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
