<template>
    <v-container v-if="books.length">
        <v-row class="align-center">
            <v-col lg="4" cols="12">
                <div class="zo-content-section">
                    <h2>{{title}}</h2>
                    <p>{{description}}</p>
                </div>
            </v-col>
            <v-col lg="8" cols="12">
                <div class="zo-swiper">
                    <swiper dir="rtl" :slides-per-view="3" :space-between="5" :modules="[Pagination]" :pagination="{ clickable: true }">
                        <swiper-slide v-for="(item,index) in books" :key="`section1-${index}`">
                            <Link :href="item.url"><img :src="item.thumbnail"></Link>
                        </swiper-slide>
                    </swiper>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>
<script setup>
import {Pagination} from "swiper/modules";
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
    description: {
        type: String,
        default: ''
    }
})
</script>
