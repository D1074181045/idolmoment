<template>
    <div class="tb">
        <h3>玩家資料</h3>
        <div class="text-center" style="margin: 12px 0 12px 0;">
            <button type="button" class="btn btn-info" style="width: 115px;"
                    v-on:click="switch_show">{{ next_name }}</button>
        </div>
        <div v-if="profile_type === 'details'">
            <table class="table">
                <tbody>
                <tr>
                    <th class="table-active">暱稱</th>
                    <td>{{ opposite_profile.nickname }}</td>
                    <td rowspan="2" style="width: 80px;">
                        <div class="img-big">
                            <picture>
                                <img :src="characters_img_path(opposite_profile.use_character.img_file_name)"
                                     :alt="opposite_profile.use_character.tc_name">
                            </picture>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-active">偶像</th>
                    <td>{{ opposite_profile.use_character.tc_name }}</td>
                </tr>
                <tr>
                    <th class="table-info">人氣</th>
                    <td colspan="2">{{ opposite_profile.popularity }}</td>
                </tr>
                <tr>
                    <th class="table-info">名聲</th>
                    <td colspan="2">{{ opposite_profile.reputation }}</td>
                </tr>
                <tr>
                    <th class="table-info">最大生命值</th>
                    <td colspan="2">{{ opposite_profile.max_vitality }}</td>
                </tr>
                <tr>
                    <th class="table-info">精力</th>
                    <td colspan="2">{{ opposite_profile.energy }}</td>
                </tr>
                <tr>
                    <th class="table-info">抗壓性</th>
                    <td colspan="2">{{ opposite_profile.resistance }}</td>
                </tr>
                <tr>
                    <th class="table-info">魅力</th>
                    <td colspan="2">{{ opposite_profile.charm }}</td>
                </tr>
                <tr>
                    <th class="table-primary">簽名檔</th>
                    <td colspan="2" style="color:#DC3545;">{{ opposite_profile.signature }}</td>
                </tr>
                <tr>
                    <th class="table-secondary">轉生次數</th>
                    <td colspan="2">{{ opposite_profile.rebirth_counter }}</td>
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
                                <img :src="characters_img_path(self_profile.use_character.img_file_name)"
                                     :alt="self_profile.use_character.tc_name">
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
                    <td>{{ self_profile.use_character.tc_name }}</td>
                </tr>
                <tr>
                    <th class="table-active">人氣</th>
                    <td colspan="2">{{ self_profile.popularity }}</td>
                </tr>
                <tr>
                    <th class="table-active">名聲</th>
                    <td colspan="2">{{ self_profile.reputation }}</td>
                </tr>
                <tr>
                    <th class="table-active">最大生命值</th>
                    <td colspan="2">{{ self_profile.max_vitality }}</td>
                </tr>
                <tr>
                    <th class="table-active">精力</th>
                    <td colspan="2">{{ self_profile.energy }}</td>
                </tr>
                <tr>
                    <th class="table-active">抗壓性</th>
                    <td colspan="2">{{ self_profile.resistance }}</td>
                </tr>
                <tr>
                    <th class="table-active">魅力</th>
                    <td colspan="2">{{ self_profile.charm }}</td>
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
                            <picture>
                                <img :src="characters_img_path(opposite_profile.use_character.img_file_name)"
                                     :alt="opposite_profile.use_character.tc_name">
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
                    <td>{{ opposite_profile.use_character.tc_name }}</td>
                </tr>
                <tr>
                    <th class="table-active">人氣</th>
                    <td colspan="2">{{ opposite_profile.popularity }}</td>
                </tr>
                <tr>
                    <th class="table-active">名聲</th>
                    <td colspan="2">{{ opposite_profile.reputation }}</td>
                </tr>
                <tr>
                    <th class="table-active">最大生命值</th>
                    <td colspan="2">{{ opposite_profile.max_vitality }}</td>
                </tr>
                <tr>
                    <th class="table-active">精力</th>
                    <td colspan="2">{{ opposite_profile.energy }}</td>
                </tr>
                <tr>
                    <th class="table-active">抗壓性</th>
                    <td colspan="2">{{ opposite_profile.resistance }}</td>
                </tr>
                <tr>
                    <th class="table-active">魅力</th>
                    <td colspan="2">{{ opposite_profile.charm }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                next_name: null,
                opposite_profile: {use_character: {}},
                profile_type: localStorage.profile_type ? localStorage.profile_type : 'details'
            }
        },
        computed: {
            self_profile: function () {
                return this.$store.state.profile;
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
        activated() {
            document.title = "玩家資訊";
            axios.get(this.api_prefix.concat('profile/', this.$route.params.name))
                .then(({status, opposite_profile, operating_time}) => {
                    if (status) {
                        document.title = "玩家資訊".concat('-', opposite_profile.nickname);
                        this.opposite_profile = opposite_profile;
                    }
            })
        },
        methods: {
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
            }
        }
    }
</script>
