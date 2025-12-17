<template>
    <div class="zo-selected-books-section" v-if="books.length">
        <div class="zo-title-section">
            <img src="/assets/img/site/right.svg" alt="">
            <strong>{{title}}</strong>
            <img src="/assets/img/site/left.svg" alt="">
        </div>
        <div class="zo-swiper">
            <swiper dir="rtl" :space-between="5" :modules="[Pagination]" :navigation="{ nextEl: '.zo-next-1', prevEl: '.zo-prev-1' }" :pagination="{ clickable: true }" :loop="true" :breakpoints="{
                                  0: { slidesPerView: 1 },
                                  960: { slidesPerView: 3 },
                                  1280: { slidesPerView: 4.5 },
                                }">
                <swiper-slide v-for="(item,index) in books" :key="`section2-${index}`">
                    <BookCard2 :book="item" />
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>
<script setup>
import { Pagination } from "swiper/modules";
import BookCard2 from "@/Components/Web/Books/BookCard2.vue";
import { Swiper, SwiperSlide } from "swiper/vue";

defineProps({
    books: {
        type: Object,
        required: true
    },
    title: {
        type: String,
        default: ''
    },
})

</script>
