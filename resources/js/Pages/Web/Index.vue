<template>
    <Head title="Welcome" />
    <WebLayout>
        <div class="zo-home-section" v-if="slider">
            <div class="zo-slider-section">
                <div class="zo-swiper">
                    <swiper dir="rtl" :slides-per-view="1" :modules="[Navigation]" navigation>
                        <swiper-slide v-for="(slide,index) in slider">
                            <a :href="slide.url" :key="index">
                                <img :src="slide.image" alt="">
                                <div class="zo-caption">
                                    <strong>{{slide.title}}</strong>
                                    <span>{{slide.subtitle}}</span>
                                </div>
                            </a>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
            <div class="zo-carousel-section">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <div class="zo-title">
                                <strong>دوره‌های پربازدید</strong>
                                <span>توضیحات مربوط به این دوره در اینجا قرار می‌گیرد</span>
                            </div>
                        </v-col>
                    </v-row>
                    <v-row class="align-center zo-courses-section">
                        <v-col
                            v-for="(course, index) in popularCourses"
                            :key="index"
                            cols="12"
                            sm="6"
                            md="4"
                            lg="3"
                        >
                            <CourseCard :course="course"/>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-carousel-section">
                <v-container v-if="latestCourses">
                    <v-row>
                        <v-col cols="12">
                            <div class="zo-title">
                                <strong>آخرین دوره‌های برگزار شده</strong>
                                <span>توضیحات مربوط به این دوره در اینجا قرار می‌گیرد</span>
                            </div>
                        </v-col>
                    </v-row>
                    <v-row class="align-center zo-courses-section">
                        <v-col
                            v-for="(course, index) in latestCourses"
                            :key="index"
                            cols="12"
                            sm="6"
                            md="4"
                            lg="3"
                        >
                            <CourseCard :course="course"/>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-stats-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="6" cols="12">
                            <div class="zo-content">
                                <h3>دوره‌های آموزشی</h3>
                                <p>
                                    در این بخش توضیحات مربوط به دوره‌ها یا اطلاعاتی کلی که اعتماد ساز در زمینه دوره‌ها
                                    باشد قرار می‌گیرد. همچنین می‌توانید متن توضیحاتی مربوط به اساتید یا دوره‌هایی که در
                                    گذشته برگزار شده قرار دهید که اعداد و آماری که در مقابل نمایش داده شده است، بهتر درک
                                    شود.
                                </p>
                            </div>
                        </v-col>
                        <v-col lg="6" cols="12">
                            <div class="zo-stats">
                                <v-row class="align-center">
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-course.svg" alt="" class="img-fluid" />
                                            <strong>{{stats.courses}}</strong>
                                            <span>دوره‌های آموزشی</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-star.svg" alt="" class="img-fluid" />
                                            <strong>{{ stats.ratings }}</strong>
                                            <span>میانگین امتیازات دوره‌ها</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-students.svg" alt="" class="img-fluid" />
                                            <strong>{{ stats.students }}</strong>
                                            <span>کل شرکت کنندگان</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-lessons.svg" alt="" class="img-fluid" />
                                            <strong>{{ stats.seasons }}</strong>
                                            <span>سرفصل آموزشی</span>
                                        </div>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div>
                <div class="zo-title-section">
                    <img src="/assets/img/site/right-green.svg" alt="">
                    <strong>کتب پرفروش</strong>
                    <img src="/assets/img/site/left-green.svg" alt="">
                </div>
                <v-row class="align-center zo-books-section">
                    <v-col
                        v-for="(book, index) in bestsellerBooks"
                        :key="index"
                        cols="12"
                        sm="6"
                        md="4"
                        lg="3"
                    >
                        <BookCard :book="book"/>
                    </v-col>
                </v-row>
            </div>
            <div class="zo-university-section" v-if="institutions">
                <v-container>
                    <v-row class="align-center">
                        <v-col cols="12">
                            <div class="zo-title-section">
                                <img src="/assets/img/site/right-green.svg" alt="">
                                <strong>همکاری با سازمان‌ها و دانشگاه‌ها</strong>
                                <img src="/assets/img/site/left-green.svg" alt="">
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
                <div class="zo-swiper">
                    <swiper dir="rtl" :space-between="60" :breakpoints="{
                                0: {
                                    slidesPerView: 1.5,
                                    spaceBetween: 60
                                },
                                990: {
                                    slidesPerView: 3.5,
                                    spaceBetween: 60
                                },
                                1260: {
                                    slidesPerView: 5.5,
                                    spaceBetween: 30
                                }
                            }">
                        <swiper-slide v-for="(institution,index) in institutions">
                            <div class="zo-university" :key="index">
                                <figure>
                                    <img :src="institution.avatar" alt="">
                                </figure>
                                <span>{{institution.title}}</span>
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
            <div class="zo-about-section" v-if="about">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="5" cols="12">
                            <h3>{{about.title}}</h3>
                            <div class="zo-content" v-html="about.content"></div>
                            <a :href="about.url">
                                <span>مشاهده بیشتر</span>
                                <i class="mdi mdi-chevron-left"></i>
                            </a>
                        </v-col>
                        <v-col lg="7" cols="12">
                            <figure>
                                <img :src="about.thumbnail" alt="">
                            </figure>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>
    </WebLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3'
import WebLayout from "@/Layouts/WebLayout.vue";
import { Navigation } from "swiper/modules";
import { Swiper, SwiperSlide } from "swiper/vue";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";
import {ref} from "vue";
import BookCard from "@/Components/Web/Books/BookCard.vue";
const props = defineProps({
    slider: Object,
    popularCourses: Object,
    latestCourses: Object,
    stats: Array,
    bestsellerBooks: Object,
    institutions: Array,
    about: Array,
})
const popularCourses = ref(props.popularCourses.data)
const latestCourses = ref(props.latestCourses.data)
const bestsellerBooks = ref(props.bestsellerBooks.data)
</script>
