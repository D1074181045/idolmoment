<template>
    <div>
        <div class="tb">
            <h3>選擇轉生偶像</h3>
            <div style="display: flex;flex-wrap: wrap;">
                <div class="character-frame" v-for="own_character in own_character_list">
                    <div :class="character_select_status(own_character.character_name)"
                         v-on:click="select_character(own_character.character_name)">
                        <div class="img-big">
                            <picture>
                                <source type="image/png" :srcset="characters_img_path(own_character.game_character.img_file_name)">
                                <img
                                    :src="characters_img_path(own_character.game_character.img_file_name)"
                                    :alt="own_character.character_name">
                            </picture>
                        </div>
                        <div style="flex: 1 1;">
                            <p style="margin-top: 1rem;">
                                {{ own_character.game_character.introduction }}
                            </p>
                        </div>
                        <table class="table">
                            <tbody class="thead-light">
                            <tr>
                                <th class="text-center">英文名稱</th>
                                <td>{{ own_character.game_character.en_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">中文名稱</th>
                                <td>{{ own_character.game_character.tc_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">生命值</th>
                                <td>{{ own_character.game_character.vitality }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">精力</th>
                                <td>{{ own_character.game_character.energy }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">抗壓性</th>
                                <td>{{ own_character.game_character.resistance }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">魅力</th>
                                <td>{{ own_character.game_character.charm }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-primary btn-block" style="margin: 0 0;"
                :disabled="rebirth_disabled" v-on:click="to_rebirth">轉生</button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                rebirth_disabled: true,
                selected_character: "",
                own_character_list: [],
            }
        },
        activated() {
            this.get_own_character().then(() => {
                document.getElementsByClassName('character-frame')[0].firstChild.click();
                // $('div.character-frame').first().children().trigger('click');
            });
        },
        methods: {
            character_select_status: function (character_name) {
                return this.selected_character === character_name ? 'current-select' : 'not-select';
            },
            get_own_character: function () {
                return axios.get(this.api_prefix.concat('own-character'))
                    .then(({status, own_character_list}) => {
                        if (status) {
                            this.own_character_list = own_character_list;
                        }
                    })
            },
            select_character: function (character_name) {
                this.selected_character = character_name;
                this.rebirth_disabled = false;
            },
            to_rebirth: function () {
                this.rebirth_disabled = true;

                axios.patch(this.api_prefix.concat('rebirth'), {
                    character_name: this.selected_character,
                }).then(({status}) => {
                    if (status) {
                        this.$router.push({ name:'index' })
                    } else {
                        this.rebirth_disabled = false;
                    }
                }).catch((err) => {
                    this.rebirth_disabled = false;
                });
            }
        }
    }
</script>

<style scoped>
    .table {
        margin-bottom: 1px;
    }

    .current-select {
        border-width: 3px;
        border-style: solid;
        border-image: initial;
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 2px 4px;
        transition: all 0.2s ease 0s;
        cursor: default;
        border-color: var(--primary-bg-color);
    }

    .not-select {
        border: 3px solid transparent;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 2px 4px;
        transition: all 0.2s ease 0s;
    }

    .not-select:hover {
        border-color: rgb(119, 119, 119);
    }

    .character-frame {
        width: 50%;
    }

    .character-frame:first-child {
        width: 50%;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        border-left: 1px solid var(--border-color);
    }

    .character-frame:nth-child(2) {
        width: 50%;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
    }

    .character-frame:nth-child(odd) {
        width: 50%;
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        border-left: 1px solid var(--border-color);
    }

    .character-frame:nth-child(even) {
        width: 50%;
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
    }

    @media screen and (max-width: 640px) {
        .character-frame {
            width: 100%;
            border-right: 1px solid var(--border-color);
            border-left: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .character-frame:nth-child(odd) {
            width: 100%;
        }

        .character-frame:nth-child(even) {
            width: 100%;
        }

        .character-frame:last-child {
            width: 100%;
        }
    }
</style>
