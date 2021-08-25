<template>
    <div :class="class_name" v-if="img_file_name">
        <picture>
            <template v-if="lazy">
                <source v-for="{type, ext} in source_list"
                        :type="type" :data-srcset="characters_img_path(img_file_name, ext)">
                <img
                    :data-src="characters_img_path(img_file_name, 'jpg')"
                    :alt="img_name" class="lazyload lazy-hidden"
                    v-on:error="img_error"
                >
            </template>
            <template v-else>
                <source v-for="{type, ext} in source_list"
                        :type="type" :srcset="characters_img_path(img_file_name, ext)">
                <img
                    :src="characters_img_path(img_file_name, 'jpg')"
                    :alt="img_name"
                    v-on:error="img_error"
                >
            </template>
        </picture>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    props: ['class_name', 'img_file_name', 'img_name', 'lazy'],
    data: function () {
        return {
            source_list: [
                {type: 'image/webp', ext: 'webp'},
                {type: 'image/jpeg', ext: 'jpg'},
                {type: 'image/png', ext: 'png'},
            ]
        }
    },
    computed: mapGetters([
        'characters_img_path'
    ]),
    methods: {
        img_error: function (e) {
            e.target.parentNode.children[0].remove();

            let source_len = e.target.parentNode.children.length - 1;
            e.target.parentNode.children[source_len].src = e.target.parentNode.children[0].srcset;
        }
    }
}
</script>

<style scoped lang="scss">
    @mixin img_def {
        margin: 0 auto;
        text-align: center;
        position: relative;
        overflow: hidden;
        background-color: rgb(255, 255, 255);
    }

    .img-big {
        max-width: 160px;
        @include img_def;

        > picture img {
            height: 160px;
            width: 160px;
        }
    }

    .img-small {
        max-width: 60px;
        @include img_def;

        > picture img {
            height: 60px;
            width: 60px;
        }
    }

    img.lazyload {
        transition: opacity 550ms ease-in;
        &.lazy-hidden {
            opacity: 0;
        }
        &.lazy-show {
            opacity: 1;
        }
    }
</style>

