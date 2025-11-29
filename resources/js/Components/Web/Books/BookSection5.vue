<template>
    <div class="zo-books-section" v-if="books.length">
        <div class="zo-title-section">
            <img src="/assets/img/site/right-primary.svg" alt="">
            <strong>{{title}}</strong>
            <img src="/assets/img/site/left-primary.svg" alt="">
        </div>
        <v-container>
            <v-row dense>
                <v-col cols="12">
                    <div class="zo-swiper">
                        <swiper class="zo-swipers" dir="rtl" :slides-per-view="4" :space-between="5" :modules="[Navigation]" :navigation="{ nextEl: '.zo-next-2', prevEl: '.zo-prev-2' }">
                            <swiper-slide v-for="(item,index) in books" :key="`section5-${index}`">
                                <BookCard :book="item"/>
                            </swiper-slide>
                        </swiper>
                        <button class="zo-prev zo-prev-2">
                            <i class="mdi mdi-chevron-left"></i>
                        </button>
                        <button class="zo-next zo-next-2">
                            <i class="mdi mdi-chevron-right"></i>
                        </button>
                    </div>
                </v-col>
                <v-col cols="12">
                    <div class="zo-more">
                        <Link :href="moreUrl">مشاهده همه</Link>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>
<script setup>
import {Navigation} from "swiper/modules";
import BookCard from "@/Components/Web/Books/BookCard.vue";
import {Swiper, SwiperSlide} from "swiper/vue";
import {Link} from "@inertiajs/vue3";

defineProps({
    books: {
        type: Object,
        required: true
    },
    title: {
        type: String,
        default: ''
    },
    moreUrl: {
        type: String,
        default: ''
    }
})
</script>
