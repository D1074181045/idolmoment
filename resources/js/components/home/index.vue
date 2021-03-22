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
                    <td colspan="2">{{ profile.popularity }}</td>
                </tr>
                <tr>
                    <th class="table-info">名聲</th>
                    <td colspan="2">{{ profile.reputation }}</td>
                </tr>
                <tr>
                    <th class="table-info">最大生命值</th>
                    <td colspan="2">{{ profile.max_vitality }}</td>
                </tr>
                <tr>
                    <th class="table-info">目前生命值</th>
                    <td colspan="2">{{ profile.current_vitality }}</td>
                </tr>
                <tr>
                    <th class="table-info">精力</th>
                    <td colspan="2">{{ profile.energy }}</td>
                </tr>
                <tr>
                    <th class="table-info">抗壓性</th>
                    <td colspan="2">{{ profile.resistance }}</td>
                </tr>
                <tr>
                    <th class="table-info">魅力</th>
                    <td colspan="2">{{ profile.charm }}</td>
                </tr>
                <tr>
                    <th class="table-secondary">轉生次數</th>
                    <td colspan="2">{{ profile.rebirth_counter }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="tb">
            <h3>個人設定</h3>
            <div class="Msg" style="display:none;" id="Msg"></div>
            <div class="tb-gap">
                <div class="setting">
                    <label style="width: 80px;margin-bottom: 0;" for="signature">簽名檔</label>
                    <input placeholder="最多30個字" type="text" name="signature" id="signature"
                           class="form-control is-valid"
                           value=""/>
                    <button type="button" id="set-signature" name="set-signature" class="btn btn-info">更新</button>
                </div>
                <div class="setting">
                    <label style="width: 80px;margin-bottom: 0;">貼貼</label>
                    <input type="text" maxlength="12" class="form-control is-invalid"
                           value=""/>
                    <button type="button" id="set-teetee" name="set-teetee" class="btn btn-info">設定</button>
                </div>
            </div>
        </div>
        <div class="tb" v-if="!graduate">
            <h3>進行活動</h3>
            <div class="Msg" style="display:none;" id="Msg2"></div>
            <div class="tb-gap" style="margin-left: -10px;">
                <button type="button" class="btn btn-bottom btn-info">成人直播</button>
                <button type="button" class="btn btn-bottom btn-info">直播</button>
                <button type="button" class="btn btn-bottom btn-info">做善事</button>
                <button type="button" class="btn btn-bottom btn-info">睡覺</button>
                <button type="button" class="btn btn-bottom btn-info">打坐</button>
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
export default {
    data() {
        return {
        }
    },
    computed: {
        profile: function () {
            return this.$store.state.profile;
        }
    },
    activated() {
        if (!this.first_load) {
            this.$store.dispatch('load_my_profile');
        }
        this.first_load = false;
    },
    methods: {}
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
