@extends('home.app')

@section('title', '活耀偶像')

@section('styles')
    <style type="text/css">
        picture img {
            height: 60px;
            width: 60px;
        }

        .signature_td {
            border-top: 1px solid var(--border-color);
            padding: 4px 8px;
        }

        .nickname_td {
            padding: 4px 8px;
        }

        .self {
            background-color: var(--self-bg-color);
        }

        .teetee {
            background-color: var(--teetee-bg-color);
        }

        .graduate {
            color: var(--graduate-color);
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        new Vue({
            el: '#main',
            delimiters: ['${', '}'],
            data: {
                page_num: 1,
                name: null,
                total_pages: {{ $total_pages }},
                max_popularity: {{ $max_popularity }},
                current_popularity: {{ $self_popularity }},
                teetee_status: null,
                teetee_name: null,
                idol_list: [],
                profile_path: '{{ Route('home.profile', '') }}'.concat('/'),
                first_page_disabled: false,
                last_page_disabled: false,
                up_page_disabled: false,
                down_page_disabled: false,
            },
            created: function () {
                this.default_load();
            },
            methods: {
                toProfile: function (path) {
                    window.location = this.profile_path.concat(path);
                },
                get_idol_list: function (page, popularity) {
                    this.current_popularity = popularity;

                    axios.get('{{ Route('api.change_page') }}', {
                        params: {
                            page: page,
                            popularity: popularity
                        }
                    }).then(({status, idol_list, max_popularity, total_pages, teetee_status, teetee_name}) => {
                        if (status) {
                            this.teetee_name = teetee_name;
                            this.teetee_status = teetee_status;
                            this.total_pages = total_pages;
                            this.max_popularity = max_popularity;
                            this.idol_list = idol_list;

                            this.down_page_disabled = this.page_num >= total_pages;
                            this.last_page_disabled =this.page_num >= total_pages;

                            if (this.page_num <= 1) {
                                this.up_page_disabled = true;
                                this.first_page_disabled = this.current_popularity >= max_popularity;
                            } else {
                                this.first_page_disabled = false;
                                this.up_page_disabled = false;
                            }
                        }
                    })
                },
                page: function () {
                    if (this.page_num <= 0)
                        this.page_num = 1;

                    this.get_idol_list(this.page_num, this.current_popularity);
                },
                search_name: function () {
                    if (this.name) {
                        axios.get('{{ Route('api.change_page') }}', {
                            params: {
                                search_name: this.name,
                            }
                        }).then(({status, idol_list, teetee_status, teetee_name}) => {
                            if (status) {
                                this.teetee_name = teetee_name;
                                this.teetee_status = teetee_status;
                                this.idol_list = idol_list;
                            }
                        })
                    }
                },
                default_load: function () {
                    this.get_idol_list(this.page_num, this.current_popularity);
                },
                first_page: function () {
                    this.page_num = 1;
                    this.get_idol_list(this.page_num, this.max_popularity);
                },
                last_page: function () {
                    this.page_num = this.total_pages;
                    this.get_idol_list(this.page_num, this.current_popularity);
                },
                up_page: function () {
                    this.page_num -= 1;
                    this.get_idol_list(this.page_num, this.current_popularity);
                },
                down_page: function () {
                    this.page_num += 1;
                    this.get_idol_list(this.page_num, this.current_popularity);
                },
                search_popularity: function () {
                    this.page_num = 1;
                    this.get_idol_list(this.page_num, this.current_popularity);
                },
                clear_search: function () {
                    this.default_load();
                    this.name = null;
                },
                characters_img_path: function (img_file_name) {
                    return '{{ asset('img/characters') }}'.concat('/').concat(img_file_name, '.jpg');
                },
                idol_class: function (idol) {
                    return {
                        self: '{{ $self_name }}' === idol.name,
                        teetee: this.teetee_name === idol.name,
                        graduate: idol.graduate
                    };
                },
                NumberFormat: function (number) {
                    return new Intl.NumberFormat('zh-TW', {
                        notation: 'compact',
                        compactDisplay: "long"
                    }).format(number);
                }
            }
        })
    </script>
@endsection

@section('content')
    <div class="tb">
        <h3>活躍偶像</h3>
        <div class="form-group mb-0">
            <div class="offset-0" style="margin-right: 0;">
                <label class="col-md-2 col-form-label text-md-right" for="name" style="padding-top: calc(.7rem + 1px);">搜尋暱稱</label>
                <div class="col-md-6 row" style="display: inline;">
                    <input class="form-control" type="text" id="name" name="name"
                           style="width: 200px; margin: 6px 2px;display: inline;" v-model="name">
                    <button id="search-name" name="search-name" type="button" class="btn btn-info"
                            style="margin-top: -6px;height: 36px;" v-on:click="search_name">
                        {{ __('搜尋') }}
                    </button>
                    <button id="clear_search" name="clear_search" type="button" class="btn btn-info"
                            style="margin-top: -6px;height: 36px;" v-on:click="clear_search">
                        {{ __('清除搜尋') }}
                    </button>
                </div>
            </div>
            <div class="offset-0" style="margin-right: 0;">
                <label class="col-md-2 col-form-label text-md-right" for="search-popularity"
                       style="padding-top: calc(.7rem + 1px);">人氣規模</label>
                <div class="col-md-6 row" style="display: inline;">
                    <input class="form-control" type="number" id="search-popularity" min="1" name="search-popularity"
                           style="width: 100px;margin: 6px 2px;display: inline;"
                           v-model="current_popularity" v-on:change="search_popularity">
                </div>
            </div>
            <div class="offset-0">
                <button type="button" class="btn btn-info" value="1" v-on:click="first_page"
                        :disabled="first_page_disabled">
                    {{ __('第一頁') }}
                </button>
                <button type="button" disabled class="btn btn-info" value="1" v-on:click="up_page"
                        :disabled="up_page_disabled">
                    {{ __('上一頁') }}
                </button>
                <input type="number" min="1" :max="total_pages" value="1" class="form-control"
                       style="width: 70px; margin: 6px 2px;display: inline;"
                       v-model="page_num" v-on:change="page">
                <button type="button" class="btn btn-info" v-on:click="down_page" :disabled="total_pages <= 1 || down_page_disabled">
                    {{ __('下一頁') }}
                </button>
                <button type="button" class="btn btn-info" v-on:click="last_page" :disabled="total_pages <= 1 || last_page_disabled">
                    {{ __('最後一頁') }}
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
                <td style="padding: 0">
                    <div class="img-small">
                        <picture>
                            <source type="image/png" :srcset="characters_img_path(idol.img_file_name)">
                            <img :src="characters_img_path(idol.img_file_name)" :alt="idol.use_character">
                        </picture>
                    </div>
                </td>
                <td v-text="idol.popularity"></td>
                <td v-text="idol.reputation"></td>
                <td style="padding: 0;height: 50px;width: 50%;">
                    <div style="height: 100%;">
                        <div class="nickname_td" v-text="idol.nickname"></div>
                        <div class="signature_td" v-text="idol.signature"></div>
                    </div>
                </td>
                <td v-text="idol.tc_name"></td>
            </tr>
            </tbody>
        </table>
        <div class="form-group mb-0">
            <div class="offset-0">
                <button type="button" class="btn btn-info" value="1" v-on:click="first_page"
                        :disabled="first_page_disabled">
                    {{ __('第一頁') }}
                </button>
                <button type="button" disabled class="btn btn-info" value="1" v-on:click="up_page"
                        :disabled="up_page_disabled">
                    {{ __('上一頁') }}
                </button>
                <input type="number" min="1" :max="total_pages" value="1" class="form-control"
                       style="width: 70px; margin: 6px 2px;display: inline;"
                       v-model="page_num" v-on:change="page">
                <button type="button" class="btn btn-info" v-on:click="down_page" :disabled="total_pages <= 1 || down_page_disabled">
                    {{ __('下一頁') }}
                </button>
                <button type="button" class="btn btn-info" v-on:click="last_page" :disabled="total_pages <= 1 || last_page_disabled">
                    {{ __('最後一頁') }}
                </button>
            </div>
        </div>
    </div>
@endsection
