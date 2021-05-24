<template>
    <div>
        <div class="tb">
            <h3>玩家資料</h3>
            <div class="text-center" style="margin: 12px 0 12px 0;">
                <button type="button" class="btn btn-info" style="width: 115px;"
                        v-on:click="switch_show">{{ next_name }}
                </button>
            </div>
            <div v-if="profile_type === 'details'" id="details">
                <table class="table">
                    <tbody>
                    <tr>
                        <th class="table-active">暱稱</th>
                        <td>{{ opposite_profile.nickname }}</td>
                        <td rowspan="2" style="width: 80px;">
                            <Avatar
                                :class_name="'img-big'"
                                :img_file_name="opposite_profile.game_character.img_file_name"
                                :img_name="opposite_profile.game_character.tc_name"
                            />
                        </td>
                    </tr>
                    <tr>
                        <th class="table-active">偶像</th>
                        <td>{{ opposite_profile.game_character.tc_name }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">人氣</th>
                        <td>{{ NumberFormat(opposite_profile.popularity) }}</td>
                        <td>
                            <Like :like_num="opposite_like_num"
                                  :dislike_num="opposite_dislike_num"
                                  :like_select="like_select"
                                  :like_name="opposite_profile.name"
                                  :can_seed="true"
                                  @like_event="like_event"
                            />
                        </td>
                    </tr>
                    <tr>
                        <th class="table-info">名聲</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.reputation) }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">最大生命值</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.max_vitality) }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">精力</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.energy) }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">抗壓性</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.resistance) }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">魅力</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.charm) }}</td>
                    </tr>
                    <tr>
                        <th class="table-primary">簽名檔</th>
                        <td colspan="2" style="color:#DC3545;">{{ opposite_profile.signature }}</td>
                    </tr>
                    <tr>
                        <th class="table-secondary">轉生次數</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.rebirth_counter) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="display: flex" v-else>
                <table style="width: 50%" class="table">
                    <tbody>
                    <tr>
                        <th class="table-active" colspan="2" style="text-align: center">
                            我的偶像
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 80px;">
                            <Avatar
                                :class_name="'img-big'"
                                :img_file_name="self_profile.game_character.img_file_name"
                                :img_name="self_profile.game_character.tc_name"
                            />
                        </td>
                    </tr>
                    <tr>
                        <th class="table-active">暱稱</th>
                        <td>{{ self_profile.nickname }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">偶像</th>
                        <td>{{ self_profile.game_character.tc_name }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">人氣</th>
                        <td colspan="2">{{ NumberFormat(self_profile.popularity) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">名聲</th>
                        <td colspan="2">{{ NumberFormat(self_profile.reputation) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">最大生命值</th>
                        <td colspan="2">{{ NumberFormat(self_profile.max_vitality) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">精力</th>
                        <td colspan="2">{{ NumberFormat(self_profile.energy) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">抗壓性</th>
                        <td colspan="2">{{ NumberFormat(self_profile.resistance) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">魅力</th>
                        <td colspan="2">{{ NumberFormat(self_profile.charm) }}</td>
                    </tr>
                    </tbody>
                </table>
                <table style="width: 50%" class="table">
                    <tbody>
                    <tr>
                        <th class="table-active" colspan="2" style="text-align: center">
                            對方偶像
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 80px;">
                            <Avatar
                                :class_name="'img-big'"
                                :img_file_name="opposite_profile.game_character.img_file_name"
                                :img_name="opposite_profile.game_character.tc_name"
                            />
                        </td>
                    </tr>
                    <tr>
                        <th class="table-active">暱稱</th>
                        <td>{{ opposite_profile.nickname }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">偶像</th>
                        <td>{{ opposite_profile.game_character.tc_name }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">人氣</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.popularity) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">名聲</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.reputation) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">最大生命值</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.max_vitality) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">精力</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.energy) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">抗壓性</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.resistance) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">魅力</th>
                        <td colspan="2">{{ NumberFormat(opposite_profile.charm) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tb" v-if="opposite_profile.name !== self_profile.name && opposite_loaded">
            <h3 v-if="opposite_profile.graduate">對方已畢業</h3>
            <h3 v-else-if="self_profile.graduate">你已畢業</h3>
            <h3 v-else>操作</h3>
            <div v-if="opposite_profile.graduate || self_profile.graduate">
                <div class="tb-gap" style="margin-left: -10px;">
                    <button type="button" class="btn btn-bottom btn-danger" disabled>寄刀片 👎</button>
                    <button type="button" class="btn btn-bottom btn-danger" disabled>抹黑 👎</button>
                    <button type="button" class="btn btn-bottom btn-info" disabled>聲援 👍</button>
                    <button type="button" class="btn btn-bottom btn-info" disabled>斗內 👍</button>
                </div>
            </div>
            <div v-else>
                <msg v-if="operating_ban.time">
                    剩餘時間：{{ operating_ban.time }}
                </msg>
                <div class="tb-gap" style="margin-left: -10px;">
                    <button type="button" class="btn btn-bottom btn-danger" v-on:click="operating('send-blade')"
                            :disabled="operating_disabled || teetee_info.teetee_name === $route.params.name">
                        寄刀片 👎
                    </button>
                    <button type="button" class="btn btn-bottom btn-danger" v-on:click="operating('defame')"
                            :disabled="operating_disabled || teetee_info.teetee_name === $route.params.name">
                        抹黑 👎
                    </button>
                    <button type="button" class="btn btn-bottom btn-info" v-on:click="operating('endorse')"
                            :disabled="operating_disabled">
                        聲援 👍
                    </button>
                    <button type="button" class="btn btn-bottom btn-info" v-on:click="operating('donate')"
                            :disabled="operating_disabled">
                        斗內 👍
                    </button>
                </div>
            </div>
        </div>

        <div class="tb">
            <h3>獲得情報</h3>
            <div style="display: flex" v-for="(information, index) in information_list">
                <div style="color: rgb(153, 153, 153);user-select: none;width: 30px;">{{ index + 1 }}</div>
                <div style="display: flex;" v-html="information"></div>
            </div>
        </div>
    </div>
</template>

<script>
import {msg} from '../../styles';
import {mapGetters, mapMutations, mapState} from "vuex";
import Avatar from "../../components/Avatar";
import Like from "../../components/Like";

export default {
    data: function () {
        return {
            information_list: [],
            next_name: null,
            opposite_like_num: 0,
            opposite_dislike_num: 0,
            like_select: null,
            opposite_profile: {game_character: {}},
            profile_type: localStorage.profile_type ? localStorage.profile_type : 'details',
            opposite_loaded: false,
            title: null,
        }
    },
    components: {
        msg,
        Avatar,
        Like
    },
    computed: {
        ...mapState([
            'api_prefix',
            'cool_down',
            'teetee_info'
        ]),
        ...mapState({
            self_profile: 'profile',
            operating_ban: state => state.ban_type.operating,
            operating_disabled: state => state.ban_type.operating.status
        }),
        ...mapGetters([
            'NumberFormat',
        ])
    },
    created: function () {
        switch (localStorage.profile_type) {
            case 'details':
                this.details();
                break;
            case 'comparison':
                this.comparison();
                break;
            default:
                this.details();
                break;
        }
    },
    mounted: function () {
        this.cool_down_func('operating');
    },
    activated: function () {
        if (this.title)
            document.title = this.title;

        this.get_opposite_profile();
    },
    methods: {
        ...mapMutations({
            cool_down_func: 'cool_down'
        }),
        get_opposite_profile: function () {
            const url = this.api_prefix.concat('profile/', this.$route.params.name);

            return axios.get(url)
                .then((res) => {
                    if (res.status) {
                        if (!this.title)
                            document.title = this.title = "偶像資訊".concat('-', res.opposite_profile.nickname);
                        this.opposite_like_num = res.opposite_like_num;
                        this.opposite_dislike_num = res.opposite_dislike_num;
                        this.like_select = res.like_select;
                        this.opposite_profile = res.opposite_profile;
                        this.opposite_loaded = true;
                    }
                })
        },
        switch_show: function () {
            let next_type = this.get_profile_type();
            switch (next_type) {
                case 'details':
                    this.details();
                    break;
                case 'comparison':
                    this.comparison();
                    break;
                default:
                    this.details();
                    break;
            }
        },
        get_profile_type: function () {
            switch (localStorage.profile_type) {
                case 'details':
                    return 'comparison';
                case 'comparison':
                    return 'details';
                default:
                    return 'comparison';
            }
        },
        details: function () {
            this.next_name = '查看能力比對';

            this.profile_type = localStorage.profile_type = 'details';
        },
        comparison: function () {
            this.next_name = '顯示詳細資料';

            this.profile_type = localStorage.profile_type = 'comparison';
        },
        operating: function (type) {
            if ((type === 'send-blade' || type === 'defame') && this.teetee_info.teetee_name === this.$route.params.name)
                return;
            if (this.operating_ban.time)
                return;

            const url = this.api_prefix.concat('operating');
            this.operating_ban.status = true;

            axios.patch(url, {
                opposite_name: this.$route.params.name,
                operating_type: type,
            }).then((res) => {
                if (res.operating_time) {
                    this.cool_down.operating = res.operating_time;
                    this.cool_down_func('operating');
                } else {
                    this.operating_ban.status = false;
                }

                if (res.status) {
                    Object.keys(res.ability.opposite).forEach((key) => {
                        this.opposite_profile[key] = res.ability.opposite[key];
                    });
                    Object.keys(res.ability.self).forEach((key) => {
                        this.self_profile[key] = res.ability.self[key];
                    });

                    this.information_list.push(res.information);
                }
            }).catch(() => {
                this.operating_ban.status = false;
            });
        },
        like_event: function (val) {
            this.opposite_like_num = val.opposite_like_num;
            this.opposite_dislike_num = val.opposite_dislike_num;
            this.like_select = val.like_select;
        }
    }
}
</script>

<style scoped>
button.btn.btn-bottom {
    margin-bottom: 12px;
    margin-right: 3px;
}
</style>
