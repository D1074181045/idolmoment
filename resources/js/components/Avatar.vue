<template>
    <div :class="class_name" v-if="img_file_name">
        <picture>
            <template v-if="lazy">
                <div class="mockup"></div>
                <source type="image/webp" :data-srcset="characters_img_path(img_file_name, 'webp')">
                <source type="image/jpeg" :data-srcset="characters_img_path(img_file_name, 'jpg')">
                <source type="image/png" :data-srcset="characters_img_path(img_file_name, 'png')">
                <img
                    :data-src="characters_img_path(img_file_name, 'jpg')"
                    :alt="img_name"
                    class="lazyload"
                    v-on:error="img_error"
                >
            </template>
            <template v-else>
                <source type="image/webp" :srcset="characters_img_path(img_file_name, 'webp')">
                <source type="image/jpeg" :srcset="characters_img_path(img_file_name, 'jpg')">
                <source type="image/png" :srcset="characters_img_path(img_file_name, 'png')">
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

<style scoped>
.img-big {
    margin: 0 auto;
    text-align: center;
    position: relative;
    overflow: hidden;
    max-width: 160px;
    background-color: rgb(255, 255, 255);
}

.img-small {
    margin: 0 auto;
    text-align: center;
    position: relative;
    overflow: hidden;
    max-width: 60px;
    background-color: rgb(255, 255, 255);
}

.img-big > picture img {
    height: 160px;
    width: 160px;
}

.img-small > picture img {
    height: 60px;
    width: 60px;
}

.mockup {
    position: absolute;
    width: 100%;
    height: 100%;
    background: #fff;
    transition: opacity 550ms ease-in;
    opacity: 1;
}

.fade-out {
    opacity: 0;
}

</style>

