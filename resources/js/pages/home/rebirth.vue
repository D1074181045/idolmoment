<template>
    <div>
        <div class="tb">
            <h3>選擇轉生偶像</h3>
            <div style="display: flex;flex-wrap: wrap;">
                <div class="character-frame" v-for="own_character in own_character_list">
                    <div :class="character_select_status(own_character.character_name)"
                         v-on:click="select_character(own_character.character_name)"
                    >
                        <Avatar
                            :class_name="'img-big'"
                            :img_file_name="own_character.game_character.img_file_name"
                            :img_name="own_character.character_name"
                            :lazy="true"
                        />
                        <div style="flex: auto;">
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
                :disabled="rebirth_disabled" v-on:click="to_rebirth">轉生
        </button>
    </div>
</template>

<script>
import { mapState } from "vuex";
import Avatar from "../../components/Avatar";

export default {
    data: function () {
        return {
            rebirth_disabled: true,
            selected_character: "",
            own_character_list: null,
            observer: null,
        }
    },
    computed: mapState([
        'api_prefix'
    ]),
    components: {
        Avatar
    },
    created() {
        this.observer = new IntersectionObserver(this.onElementObserved);
    },
    activated: function () {
        this.get_own_character().then(() => {
            const lazyImages = document.querySelectorAll('img.lazyload');
            lazyImages.forEach((image) => {
                this.observer.observe(image)
            });
        });

        if (this.own_character_list) this.default_select();
    },
    methods: {
        onElementObserved: function (entries, observer) {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                const sources = entry.target.parentNode.querySelectorAll('source');

                sources.forEach((source) => {
                    if (source.dataset.srcset) {
                        source.setAttribute('srcset', source.dataset.srcset);
                        source.removeAttribute('data-srcset');
                    }
                })

                const img = entry.target;

                if (img.dataset.src) {
                    img.setAttribute('src', img.dataset.src);
                    img.removeAttribute('data-src');
                    img.addEventListener('load', () => {
                        img.classList.remove('lazy-hidden');
                        img.classList.add('lazy-show');
                    })
                }

                observer.unobserve(img);
            });
        },
        default_select: function (item = 0) {
            this.select_character(this.own_character_list[item].character_name);
        },
        character_select_status: function (character_name) {
            return this.selected_character === character_name ? 'current-select' : 'not-select';
        },
        get_own_character: function () {
            const url = this.api_prefix.concat('own-character');

            return axios.get(url)
                .then((res) => {
                    if (res.status) {
                        this.own_character_list = res.own_character_list;
                        this.default_select();
                    }
                })
        },
        select_character: function (character_name) {
            console.log("DEBUG", "選取偶像", character_name);
            this.selected_character = character_name;
            this.rebirth_disabled = false;
        },
        to_rebirth: function () {
            this.rebirth_disabled = true;

            const url = this.api_prefix.concat('rebirth');

            axios.post(url, {
                character_name: this.selected_character,
            }).then((res) => {
                if (res.status) {
                    this.$router.push({name: 'index'})
                }
            }).catch(() => {
                this.rebirth_disabled = false;
            });
        }
    }
}
</script>

<style scoped lang="scss">
.table {
    margin-bottom: 1px;

    th {
        width: 120px;
    }
}

.current-select {
    border: 3px solid var(--primary-bg-color);
    display: flex;
    -webkit-flex-direction: column;
            flex-direction: column;
    height: 100%;
    padding: 2px 4px;
    -webkit-transition: all 0.2s ease 0s;
            transition: all 0.2s ease 0s;
    cursor: default;
}

.not-select {
    border: 3px solid transparent;
    display: flex;
    -webkit-flex-direction: column;
            flex-direction: column;
    height: 100%;
    padding: 2px 4px;
    -webkit-transition: all 0.2s ease 0s;
            transition: all 0.2s ease 0s;
    cursor: pointer;
    &:hover {
        border-color: rgb(119, 119, 119);
    }
}

.character-frame {
    width: 50%;

    &:first-child {
        width: 50%;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        border-left: 1px solid var(--border-color);
    }

    &:nth-child(2) {
        width: 50%;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
    }

    &:nth-child(odd) {
        width: 50%;
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        border-left: 1px solid var(--border-color);
    }

    &:nth-child(even) {
        width: 50%;
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
    }

    @media screen and (max-width: 640px) {
        & {
            width: 100%;
            border-right: 1px solid var(--border-color);
            border-left: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);

            &:nth-child(odd) {
                width: 100%;
            }

            &:nth-child(even) {
                width: 100%;
            }

            &:last-child {
                width: 100%;
            }
        }
    }
}
</style>
