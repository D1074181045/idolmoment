<template>
    <div>
        <div class="tb">
            <h3>我的偶像</h3>
            <table class="table">
                <tbody>
                <tr>
                    <th class="table-active">暱稱</th>
                    <td>{{ nickname }}</td>
                    <td rowspan="2" style="width: 80px;">
                        <div class="img-big">
                            <picture>
                                <source type="image/png"
                                        :srcset="characters_img_path(use_character.img_file_name)">
                                <img
                                    :src="characters_img_path(use_character.img_file_name)"
                                    :alt="characters_img_path(use_character.tc_name)">
                            </picture>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="table-active">偶像</th>
                    <td>{{ use_character.tc_name }}</td>
                </tr>
                <tr>
                    <th class="table-info">人氣</th>
                    <td colspan="2">{{ popularity }}</td>
                </tr>
                <tr>
                    <th class="table-info">名聲</th>
                    <td colspan="2">{{ reputation }}</td>
                </tr>
                <tr>
                    <th class="table-info">最大生命值</th>
                    <td colspan="2">{{ max_vitality }}</td>
                </tr>
                <tr>
                    <th class="table-info">目前生命值</th>
                    <td colspan="2">{{ current_vitality }}</td>
                </tr>
                <tr>
                    <th class="table-info">精力</th>
                    <td colspan="2">{{ energy }}</td>
                </tr>
                <tr>
                    <th class="table-info">抗壓性</th>
                    <td colspan="2">{{ resistance }}</td>
                </tr>
                <tr>
                    <th class="table-info">魅力</th>
                    <td colspan="2">{{ charm }}</td>
                </tr>
                <tr>
                    <th class="table-secondary">轉生次數</th>
                    <td colspan="2">{{ rebirth_counter }}</td>
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
                    <input placeholder="最多30個字" type="text" name="signature" id="signature" class="form-control is-valid"
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
        data () {
            return {
                nickname: this.my_profile.nickname,
                popularity: this.my_profile.popularity,
                reputation: this.my_profile.reputation,
                max_vitality: this.my_profile.max_vitality,
                current_vitality: this.my_profile.current_vitality,
                energy: this.my_profile.energy,
                resistance: this.my_profile.resistance,
                charm: this.my_profile.charm,
                rebirth_counter: this.my_profile.rebirth_counter,
                use_character: this.my_profile.use_character,
                graduate: this.my_profile.graduate,
                signature: this.my_profile.signature,
                teetee: this.my_profile.teetee
            }
        },
        activated() {
            if (!this.first_load) {
                this.load_my_profile(true).then(() => {
                    $.each(this.my_profile, (name, value) => {
                        this[name] = value;
                    })
                });
            }
            this.first_load = false;
        },
        methods: {

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
