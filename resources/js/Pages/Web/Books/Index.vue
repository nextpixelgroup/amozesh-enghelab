<template>
    <WebLayout>
        <div class="zo-home-books-section">
            <v-container>
                <v-row dense>
                    <v-col cols=12>
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#">خانه</a>
                                    </li>
                                    <li>
                                        <span>فروشگاه کتاب</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <v-container v-if="section1.data.length">
                <v-row class="align-center">
                    <v-col lg="4" cols="12">
                        <div class="zo-content-section">
                            <h2>فروش ویژه آثار آیت الله خامنه‌ای</h2>
                            <p>
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.
                            </p>
                        </div>
                    </v-col>
                    <v-col lg="8" cols="12">
                        <div class="zo-swiper">
                            <swiper dir="rtl" :slides-per-view="3" :space-between="5" :modules="[Pagination]" :pagination="{ clickable: true }">
                                <swiper-slide v-for="(item,index) in section1.data" :key="`section1-${index}`">
                                    <Link :href="item.url"><img :src="item.thumbnail"></Link>
                                </swiper-slide>
                            </swiper>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <div class="zo-selected-books-section" v-if="section2.data.length">
                <div class="zo-title-section">
                    <img src="/assets/img/site/right.svg" alt="">
                    <strong>آثار برگزیده</strong>
                    <img src="/assets/img/site/left.svg" alt="">
                </div>
                <div class="zo-swiper">
                    <swiper dir="rtl" :space-between="5" :modules="[Pagination]" :navigation="{ nextEl: '.zo-next-1', prevEl: '.zo-prev-1' }" :pagination="{ clickable: true }" :loop="true" :breakpoints="{
                                  0: { slidesPerView: 1 },
                                  755: { slidesPerView: 2 },
                                  990: { slidesPerView: 3 },
                                  1100: { slidesPerView: 4.5 },
                                }">
                        <swiper-slide v-for="(item,index) in section2.data" :key="`section2-${index}`">
                            <BookCard2 :book="item"/>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
            <div class="zo-books-section" v-if="section3.data.length">
                <div class="zo-title-section">
                    <img src="/assets/img/site/right-primary.svg" alt="">
                    <strong>آثار برگزیده</strong>
                    <img src="/assets/img/site/left-primary.svg" alt="">
                </div>
                <v-container>
                    <v-row dense>
                        <v-col cols="12">
                            <div class="zo-swiper">
                                <swiper dir="rtl" :space-between="5" :modules="[Navigation]" :navigation="{ nextEl: '.zo-next-1', prevEl: '.zo-prev-1' }" :breakpoints="{
                                  0: { slidesPerView: 1 },
                                  755: { slidesPerView: 2 },
                                  990: { slidesPerView: 3 },
                                  1100: { slidesPerView: 4 },
                                }">
                                    <swiper-slide v-for="(item,index) in section3.data" :key="`section3-${index}`">
                                        <BookCard :book="item"/>
                                    </swiper-slide>
                                </swiper>
                                <button class="zo-prev zo-prev-1">
                                    <i class="mdi mdi-chevron-left"></i>
                                </button>
                                <button class="zo-next zo-next-1">
                                    <i class="mdi mdi-chevron-right"></i>
                                </button>
                            </div>
                        </v-col>
                        <v-col cols="12">
                            <div class="zo-more">
                                <Link :href="route('web.books.archives',{category: 'popular'})">مشاهده همه</Link>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-special-books-section" v-if="section4.data.length">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <swiper class="zo-swiper" dir="rtl" :slides-per-view="1" :space-between="5" :modules="[Navigation]" :navigation="{ nextEl: '.zo-next-3', prevEl: '.zo-prev-3' }">
                                <swiper-slide v-for="(item,index) in section4.data" :key="`section4-${index}`">
                                    <BookCard3 :book="item"/>
                                </swiper-slide>
                            </swiper>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-books-section">
                <div class="zo-title-section">
                    <img src="/assets/img/site/right-primary.svg" alt="">
                    <strong>کتب معارف اسلامی</strong>
                    <img src="/assets/img/site/left-primary.svg" alt="">
                </div>
                <v-container>
                    <v-row dense>
                        <v-col cols="12">
                            <div class="zo-swiper">
                                <swiper class="zo-swipers" dir="rtl" :slides-per-view="4" :space-between="5" :modules="[Navigation]" :navigation="{ nextEl: '.zo-next-2', prevEl: '.zo-prev-2' }">
                                    <swiper-slide v-for="(item,index) in section5.data" :key="`section5-${index}`">
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
                    </v-row>
                </v-container>
            </div>
        </div>
    </WebLayout>
</template>
<script setup>
import { ref } from 'vue'
import WebLayout from "@/Layouts/WebLayout.vue";
import { Pagination, Navigation } from 'swiper/modules';
import { Swiper, SwiperSlide } from "swiper/vue";
import {Link} from "@inertiajs/vue3";
import BookCard2 from "@/Components/Web/Books/BookCard2.vue";
import BookCard from "@/Components/Web/Books/BookCard.vue";
import {route} from "ziggy-js";
import BookCard3 from "@/Components/Web/Books/BookCard3.vue";

defineProps({
    section1: Object,
    section2: Object,
    section3: Object,
    section4: Object,
    section5: Object,
})

</script>
