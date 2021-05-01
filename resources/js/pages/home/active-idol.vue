<template>
    <div class="tb">
        <h3>活躍偶像</h3>
        <div class="form-group mb-0">
            <div class="offset-0" style="margin-right: 0;">
                <label class="col-md-2 col-form-label text-md-right" for="name" style="padding-top: calc(.7rem + 1px);">搜尋暱稱</label>
                <div class="col-md-6 row" style="display: inline;">
                    <input class="form-control" type="text" id="name" name="name" autocomplete="off"
                           style="width: 200px; margin: 6px 2px;display: inline;" v-model="search_name">
                    <button id="search-name" name="search-name" type="button" class="btn btn-info"
                            style="margin-top: -6px;height: 36px;" v-on:click="to_search_name">搜尋
                    </button>
                    <button id="clear_search" name="clear_search" type="button" class="btn btn-info"
                            style="margin-top: -6px;height: 36px;" v-on:click="clear_search">清除搜尋
                    </button>
                </div>
            </div>
            <div class="offset-0" style="margin-right: 0;">
                <label class="col-md-2 col-form-label text-md-right" for="search-popularity"
                       style="padding-top: calc(.7rem + 1px);">人氣規模</label>
                <div class="col-md-6 row" style="display: inline;">
                    <input class="form-control" type="number" id="search-popularity" min="1" name="search-popularity"
                           style="width: 100px;margin: 6px 2px;display: inline;"
                           v-model="current_popularity" v-on:change="switch_page(page_num = 1, current_popularity)">
                </div>
            </div>
            <div class="offset-0">
                <button type="button" class="btn btn-info" v-on:click="switch_page(page_num = 1, max_popularity)"
                        :disabled="first_page_disabled">第一頁
                </button>
                <button type="button" disabled class="btn btn-info" v-on:click="switch_page(--page_num, current_popularity)"
                        :disabled="up_page_disabled">上一頁
                </button>
                <input type="number" min="1" :max="total_pages" value="1" class="form-control"
                       style="width: 70px; margin: 6px 2px;display: inline;"
                       v-model="page_num" v-on:change="switch_page(page_num, current_popularity)">
                <button type="button" class="btn btn-info" v-on:click="switch_page(++page_num, current_popularity)"
                        :disabled="down_page_disabled">下一頁
                </button>
                <button type="button" class="btn btn-info" v-on:click="switch_page(page_num = total_pages, current_popularity)"
                        :disabled="last_page_disabled">最後一頁
                </button>
            </div>
        </div>

        <table class="table table-hover" style="min-width: 560px;margin-top: 1rem;">
            <thead class="thead-light">
            <tr>
                <th style="width: 60px;">頭像</th>
                <th style="width: 60px;">人氣</th>
                <th style="width: 60px;">名聲</th>
                <th>暱稱</th>
                <th>偶像</th>
            </tr>
            </thead>
            <tbody>
            <tr style="cursor: pointer;" :class="idol_class(idol)"
                v-for="idol in idol_list" v-on:click="toProfile(idol.name)" :key="idol.name">
                <td style="padding: 0;">
                    <div class="img-small">
                        <picture>
                            <source type="image/webp"
                                    :srcset="characters_img_path(idol.img_file_name, 'webp')">
                            <source type="image/jpeg"
                                    :srcset="characters_img_path(idol.img_file_name)">
                            <source type="image/png"
                                    :srcset="characters_img_path(idol.img_file_name, 'png')">
                            <img :src="characters_img_path(idol.img_file_name)"
                                 :alt="idol.use_character"
                                 v-on:error="img_error">
                        </picture>
                    </div>
                </td>
                <td>{{ $store.getters.NumberFormat(idol.popularity, 'zh-TW') }}</td>
                <td>{{ $store.getters.NumberFormat(idol.reputation, 'zh-TW') }}</td>
                <td style="padding: 0;height: 50px;width: 50%;">
                    <div style="height: 100%;">
                        <div style="padding: 4px 8px;">{{ idol.nickname }}</div>
                        <div style="border-top: 1px solid var(--border-color);padding: 4px 8px;">{{ idol.signature }}</div>
                    </div>
                </td>
                <td>{{ idol.tc_name }}</td>
            </tr>
            </tbody>
        </table>
        <div class="form-group mb-0">
            <div class="offset-0">
                <button type="button" class="btn btn-info" v-on:click="switch_page(page_num = 1, max_popularity)"
                        :disabled="first_page_disabled">第一頁
                </button>
                <button type="button" disabled class="btn btn-info" v-on:click="switch_page(--page_num, current_popularity)"
                        :disabled="up_page_disabled">上一頁
                </button>
                <input type="number" min="1" :max="total_pages" value="1" class="form-control"
                       style="width: 70px; margin: 6px 2px;display: inline;"
                       v-model="page_num" v-on:change="switch_page(page_num, current_popularity)">
                <button type="button" class="btn btn-info" v-on:click="switch_page(++page_num, current_popularity)"
                        :disabled="total_pages <= 1 || down_page_disabled">下一頁
                </button>
                <button type="button" class="btn btn-info" v-on:click="switch_page(page_num = total_pages, current_popularity)"
                        :disabled="total_pages <= 1 || last_page_disabled">最後一頁
                </button>
            </div>
        </div>
    </div>
</template>

<script>

import {mapState} from "vuex";

export default {
    data: function () {
        return {
            search_name: null,
            page_num: 1,
            total_pages: 1,
            max_popularity: 1,
            current_popularity: this.$store.state.profile.popularity,
            idol_list: [],
            profile_path: '/profile/',
            first_page_disabled: false,
            last_page_disabled: false,
            up_page_disabled: false,
            down_page_disabled: false,
        }
    },
    computed: mapState({
        name: state => state.profile.name,
        teetee_info: 'teetee_info',
        api_prefix: 'api_prefix',
    }),
    activated: function () {
        document.title = "活耀偶像";

        if (this.reload()) {
            this.current_popularity = this.$store.state.profile.popularity;
            this.page_num = 1;
            this.default_load();
        }
    },
    methods: {
        toProfile: function (path) {
            this.$router.push({name: 'profile', params: {name: path}});
        },
        check_page_num: function (page) {
            if (page > this.total_pages)
                return this.page_num = this.total_pages;
            else if (page < 1)
                return this.page_num = 1;
            else
                return page;
        },
        get_idol_list: function (page, popularity) {
            page = this.check_page_num(page);

            const url = this.api_prefix.concat('change-page');
            this.current_popularity = popularity;

            axios.get(url, {
                params: {
                    page: page,
                    popularity: popularity
                }
            }).then((res) => {
                if (res.status) {
                    this.total_pages = res.total_pages;
                    this.max_popularity = res.max_popularity;
                    this.idol_list = res.idol_list;

                    this.down_page_disabled = this.page_num >= res.total_pages;
                    this.last_page_disabled = this.page_num >= res.total_pages;

                    if (this.page_num <= 1) {
                        this.up_page_disabled = true;
                        this.first_page_disabled = this.current_popularity >= res.max_popularity;
                    } else {
                        this.first_page_disabled = false;
                        this.up_page_disabled = false;
                    }
                }
            })
        },
        to_search_name: function () {
            if (!this.search_name)
                return;

            const url = this.api_prefix.concat('change-page');

            axios.get(url, {
                params: {
                    search_name: this.search_name,
                }
            }).then((res) => {
                if (res.status) {
                    this.idol_list = res.idol_list;
                }
            })
        },
        default_load: function () {
            this.get_idol_list(this.page_num, this.current_popularity);
        },
        switch_page: function (page, popularity) {
            if (popularity <= 0)
                popularity = this.current_popularity = 1;
            if (page <= 0)
                page = this.page_num = 1;

            this.get_idol_list(page, popularity);
        },
        clear_search: function () {
            this.default_load();
            this.search_name = null;
        },
        idol_class: function (idol) {
            return {
                self: this.name === idol.name,
                teetee: this.teetee_info.status && this.teetee_info.teetee_name === idol.name && this.teetee_info.status,
                graduate: idol.graduate
            };
        }
    }
}
</script>

<style scoped>
.self {
    background-color: var(--self-bg-color) !important;
}

.teetee {
    background-color: var(--teetee-bg-color) !important;
}

.graduate {
    color: var(--graduate-color) !important;
}
</style>
