<template>
    <div class="zo-special-books-section" v-if="books.length">
        <v-container>
            <v-row>
                <v-col cols="12">
                    <swiper class="zo-swiper" dir="rtl" :slides-per-view="1" :space-between="25" :modules="[Navigation]" :navigation="{ nextEl: '.zo-next-3', prevEl: '.zo-prev-3' }">
                        <swiper-slide v-for="(item,index) in books" :key="`section4-${index}`">
                            <BookCard3 :book="item"/>
                        </swiper-slide>
                    </swiper>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>
<script setup>
import {Navigation} from "swiper/modules";
import {Swiper, SwiperSlide} from "swiper/vue";
import BookCard3 from "@/Components/Web/Books/BookCard3.vue";

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
