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
                        <swiper dir="rtl" :space-between="15" :modules="[Navigation]" :navigation="{ nextEl: '.zo-next-1', prevEl: '.zo-prev-1' }" :breakpoints="{
                                  0: { slidesPerView: 1 },
                                  960: { slidesPerView: 3 },
                                  1280: { slidesPerView: 4 },
                                }">
                            <swiper-slide v-for="(item,index) in books" :key="`section3-${index}`">
                                <BookCard :book="item"/>
                            </swiper-slide>
                        </swiper>
                        <button class="zo-prev zo-prev-1 d-lg-flex d-none">
                            <i class="mdi mdi-chevron-left"></i>
                        </button>
                        <button class="zo-next zo-next-1 d-lg-flex d-none">
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
import {route} from "ziggy-js";
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
