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
                    <swiper dir="rtl" :space-between="10" :modules="[Pagination]" :pagination="{ clickable: true }" :breakpoints="{
                                  0: { slidesPerView: 1 },
                                  960: { slidesPerView: 2 },
                                  1260: { slidesPerView: 3 },
                                }">
                        >
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
import { Pagination } from "swiper/modules";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Link } from "@inertiajs/vue3";

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
<style scoped>
.swiper-slide a {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 10px 45px;
    background: rgb(240, 240, 240);
    border-radius: 300px 300px 0 0
}

.swiper-slide.swiper-slide-next a {
    padding: 60px 10px;
    border-radius: 0 0 300px 300px
}

.swiper-slide.swiper-slide-active a img {
    box-shadow: -10px 10px 20px 0 rgba(0, 0, 0, .25)
}

</style>
