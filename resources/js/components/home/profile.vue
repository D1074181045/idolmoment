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
                            <div class="img-big">
                                <picture v-if="opposite_loaded">
                                    <source type="image/webp" :srcset="characters_img_path(opposite_profile.game_character.img_file_name, 'webp')">
                                    <source type="image/jpeg" :srcset="characters_img_path(opposite_profile.game_character.img_file_name)">
                                    <source type="image/png" :srcset="characters_img_path(opposite_profile.game_character.img_file_name, 'png')">
                                    <img :src="characters_img_path(opposite_profile.game_character.img_file_name)"
                                         :alt="opposite_profile.game_character.tc_name">
                                </picture>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-active">偶像</th>
                        <td>{{ opposite_profile.game_character.tc_name }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">人氣</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.popularity) }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">名聲</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.reputation) }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">最大生命值</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.max_vitality) }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">精力</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.energy) }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">抗壓性</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.resistance) }}</td>
                    </tr>
                    <tr>
                        <th class="table-info">魅力</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.charm) }}</td>
                    </tr>
                    <tr>
                        <th class="table-primary">簽名檔</th>
                        <td colspan="2" style="color:#DC3545;">{{ opposite_profile.signature }}</td>
                    </tr>
                    <tr>
                        <th class="table-secondary">轉生次數</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.rebirth_counter) }}</td>
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
                            <div class="img-big">
                                <picture>
                                    <source type="image/webp" :srcset="characters_img_path(self_profile.game_character.img_file_name, 'webp')">
                                    <source type="image/jpeg" :srcset="characters_img_path(self_profile.game_character.img_file_name)">
                                    <source type="image/png" :srcset="characters_img_path(self_profile.game_character.img_file_name, 'png')">
                                    <img :src="characters_img_path(self_profile.game_character.img_file_name)"
                                         :alt="self_profile.game_character.tc_name">
                                </picture>
                            </div>
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
                        <td colspan="2">{{ $store.getters.NumberFormat(self_profile.popularity) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">名聲</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(self_profile.reputation) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">最大生命值</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(self_profile.max_vitality) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">精力</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(self_profile.energy) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">抗壓性</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(self_profile.resistance) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">魅力</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(self_profile.charm) }}</td>
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
                            <div class="img-big">
                                <picture v-if="opposite_loaded">
                                    <source type="image/webp" :srcset="characters_img_path(opposite_profile.game_character.img_file_name, 'webp')">
                                    <source type="image/jpeg" :srcset="characters_img_path(opposite_profile.game_character.img_file_name)">
                                    <source type="image/png" :srcset="characters_img_path(opposite_profile.game_character.img_file_name, 'png')">
                                    <img :src="characters_img_path(opposite_profile.game_character.img_file_name)"
                                         :alt="opposite_profile.game_character.tc_name">
                                </picture>
                            </div>
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
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.popularity) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">名聲</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.reputation) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">最大生命值</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.max_vitality) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">精力</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.energy) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">抗壓性</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.resistance) }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">魅力</th>
                        <td colspan="2">{{ $store.getters.NumberFormat(opposite_profile.charm) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tb" v-if="opposite_profile.name !== self_profile.name && opposite_loaded">
            <div v-if="opposite_profile.graduate">
                <h3>對方已畢業</h3>
                <div class="tb-gap" style="margin-left: -10px;">
                    <button type="button" class="btn btn-bottom btn-info" disabled>寄刀片</button>
                </div>
            </div>
            <div v-else-if="self_profile.graduate">
                <h3>你已畢業</h3>
                <div class="tb-gap" style="margin-left: -10px;">
                    <button type="button" class="btn btn-bottom btn-info" disabled>寄刀片</button>
                </div>
            </div>
            <div v-else>
                <h3>操作</h3>
                <msg v-if="operating_ban.time">剩餘時間：{{ operating_ban.time }}</msg>
                <div class="tb-gap" style="margin-left: -10px;">
                    <button type="button" class="btn btn-bottom btn-info" v-on:click="operating('send-blade')"
                            :disabled="operating_disabled || $store.state.teetee_info.teetee_name === $route.params.name">寄刀片
                    </button>
                    <button type="button" class="btn btn-bottom btn-info" v-on:click="operating('endorse')"
                            :disabled="operating_disabled">聲援
                    </button>
                    <button type="button" class="btn btn-bottom btn-info" v-on:click="operating('donate')"
                            :disabled="operating_disabled">斗內
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

export default {
    data() {
        return {
            information_list: [],
            next_name: null,
            opposite_profile: {game_character: {}},
            profile_type: localStorage.profile_type ? localStorage.profile_type : 'details',
            opposite_loaded: false,
            title: null,
        }
    },
    components: {
        msg
    },
    computed: {
        self_profile: function () {
            return this.$store.state.profile;
        },
        operating_ban: function () {
            return this.$store.state.ban_type.operating;
        },
        operating_disabled: function () {
            return this.$store.state.ban_type.operating.status;
        },
        api_prefix: function () {
            return this.$store.state.api_prefix
        },
        cool_down: function () {
            return this.$store.state.cool_down;
        },
    },
    created() {
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
    mounted() {
        this.$store.commit('cool_down', 'operating');
    },
    activated() {
        document.title = this.title ? this.title : "玩家資訊";

        this.get_opposite_profile();
    },
    methods: {
        get_opposite_profile: function() {
            const url = this.api_prefix.concat('profile/', this.$route.params.name);

            axios.get(url)
                .then(({status, opposite_profile}) => {
                    if (status) {
                        if (!this.title)
                            document.title = this.title = "玩家資訊".concat('-', opposite_profile.nickname);
                        this.opposite_profile = opposite_profile;
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
            if (type === 'send-blade' && this.$store.state.teetee_info.teetee_name === this.$route.params.name)
                return;
            if (this.operating_ban.time)
                return;

            const url = this.api_prefix.concat('operating');
            this.operating_ban.status = true;

            axios.patch(url, {
                opposite_name: this.$route.params.name,
                operating_type: type,
            }).then(({status, opposite_ability, self_ability, operating_time, information}) => {
                if (status) {
                    this.cool_down.operating = operating_time;
                    this.$store.commit('cool_down', 'operating');

                    Object.keys(opposite_ability).forEach((key) => {
                        this.opposite_profile[key] = opposite_ability[key];
                    });
                    Object.keys(self_ability).forEach((key) => {
                        this.self_profile[key] = self_ability[key];
                    });

                    this.information_list.push(information);
                } else {
                    this.operating_ban.status = false;
                }
            }).catch((err) => {
                this.operating_ban.status = false;
            });
        }
    }
}
</script>

<style scoped>
    .tb .tb-gap {
        margin-top: 15px;
        margin-bottom: 10px;
    }
</style>
