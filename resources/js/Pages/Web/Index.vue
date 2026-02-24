<template>
    <WebLayout>
        <div class="zo-home-section" v-if="slider">
            <div class="zo-slider-section">
                <div class="zo-swiper">
                    <swiper dir="rtl" :slides-per-view="1" :space-between="15" :modules="[Navigation]" navigation>
                        <swiper-slide v-for="(slide,index) in slider">
                            <a :href="slide.link" :target="slide.target" :key="index">
                                <img :src="slide.image.url" alt="">
                                <div class="zo-caption">
                                    <strong>{{slide.title}}</strong>
                                    <span>{{slide.description}}</span>
                                </div>
                            </a>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
            <div class="zo-carousel-section">
                <div class="zo-courses-section">
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <div class="zo-title">
                                    <strong>دوره‌های پربازدید</strong>
                                    <span>توضیحات مربوط به این دوره در اینجا قرار می‌گیرد</span>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row class="align-center">
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
            </div>
            <div class="zo-carousel-section">
                <div class="zo-courses-section">
                    <v-container v-if="latestCourses">
                        <v-row>
                            <v-col cols="12">
                                <div class="zo-title">
                                    <strong>آخرین دوره‌های برگزار شده</strong>
                                    <span>توضیحات مربوط به این دوره در اینجا قرار می‌گیرد</span>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row class="align-center">
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
            </div>
            <div class="zo-stats-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="6" cols="12">
                            <div class="zo-content">
                                <strong>دوره‌های آموزشی</strong>
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
                                <v-row dense class="align-center">
                                    <v-col cols="6">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-course.svg" alt="" class="img-fluid" />
                                            <strong>{{stats.courses}}</strong>
                                            <span>دوره‌های آموزشی</span>
                                        </div>
                                    </v-col>
                                    <v-col cols="6">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-star.svg" alt="" class="img-fluid" />
                                            <strong>{{ stats.ratings }}</strong>
                                            <span>میانگین امتیازات دوره‌ها</span>
                                        </div>
                                    </v-col>
                                    <v-col cols="6">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-students.svg" alt="" class="img-fluid" />
                                            <strong>{{ stats.students }}</strong>
                                            <span>کل شرکت کنندگان</span>
                                        </div>
                                    </v-col>
                                    <v-col cols="6">
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
            <div class="zo-books-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col cols="12">
                             <div class="zo-title-section">
                                <img src="/assets/img/site/right-green.svg" alt="">
                                <strong>کتب پرفروش</strong>
                                <img src="/assets/img/site/left-green.svg" alt="">
                            </div>
                            <v-row class="align-center">
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
                        </v-col>
                    </v-row>
                </v-container>
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
                                    spaceBetween: 10
                                },
                                990: {
                                    slidesPerView: 3.5,
                                    spaceBetween: 15
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
                    <v-row class="zo-row align-center">
                        <v-col lg="5" cols="12">
                            <h3>{{about.title}}</h3>
                            <p class="zo-content" v-html="about.content"></p>
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
    about: {
        type: Object,
        default: {}
    },
})
const popularCourses = ref(props.popularCourses.data)
const latestCourses = ref(props.latestCourses.data)
const bestsellerBooks = ref(props.bestsellerBooks.data)
console.log(props.slider)
</script>
<style scoped>

.zo-home-section .zo-slider-section .zo-swiper :deep(.swiper-slide) {
    position: relative;
}

.zo-home-section .zo-slider-section .zo-swiper :deep(.swiper-slide) img {
    width: 100%;
    object-fit: cover
}

@media (max-width: 990px) {

    .zo-home-section .zo-slider-section .zo-swiper :deep(.swiper-slide) img {
        height: 450px
    }
}

.zo-home-section .zo-slider-section .zo-swiper .zo-caption {
    position: absolute;
    bottom: 60px;
    right: 60px;
    color: var(--White);
    z-index: 15
}

.zo-home-section .zo-slider-section .zo-swiper .zo-caption strong {
    display: block;
    padding: 0 15px 0 0;
    position: relative;
    font-size: 1.75rem
}

.zo-home-section .zo-slider-section .zo-swiper .zo-caption strong:before {
    content: '';
    width: 2.5px;
    height: 75%;
    position: absolute;
    top: 7.5px;
    right: 0;
    background: var(--Secondary)
}

.zo-home-section .zo-slider-section .zo-swiper .zo-caption span {
    display: block;
    padding: 0 15px 0 0;
    font-size: 1.25rem
}

@media (max-width: 960px) {

    .zo-home-section .zo-slider-section .zo-swiper .zo-caption {
        display: none
    }
}

.zo-home-section .zo-slider-section .zo-swiper :deep(.swiper.swiper-rtl .swiper-button-prev) {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 50%;
    left: 15px;
    right: auto;
    background: var(--Secondary);
    transform: translateY(-50%) rotate(180deg);
    border: 1px solid var(--Secondary);
    border-top-right-radius: 300px;
    border-bottom-right-radius: 300px;
    cursor: pointer;
    z-index: 15
}

.zo-home-section .zo-slider-section .zo-swiper :deep(.swiper.swiper-rtl .swiper-button-next) {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 50%;
    right: 15px;
    background: var(--Secondary);
    border: 1px solid var(--Secondary);
    border-top-right-radius: 300px;
    border-bottom-right-radius: 300px;
    transform: translateY(-50%);
    cursor: pointer;
    z-index: 15
}

.zo-home-section .zo-slider-section .zo-swiper :deep(.swiper.swiper-rtl .swiper-button-prev.swiper-button-disabled),
.zo-home-section .zo-slider-section .zo-swiper :deep(.swiper.swiper-rtl .swiper-button-next.swiper-button-disabled) {
    opacity: .5;
}

.zo-home-section .zo-slider-section .zo-swiper :deep(.swiper.swiper-rtl .swiper-button-prev svg),
.zo-home-section .zo-slider-section .zo-swiper :deep(.swiper.swiper-rtl .swiper-button-next svg) {
    width: 10px;
    color: var(--White);
    transform: rotate(0)
}

.zo-home-section .zo-carousel-section .zo-title {
    width: 100%;
    display: inline-block;
    position: relative
}

.zo-home-section .zo-carousel-section .zo-title strong {
    display: block;
    margin: 0 0 2.5px;
    padding: 0 15px 0 0;
    position: relative;
    font-size: 1.25rem
}

.zo-home-section .zo-carousel-section .zo-title strong:before {
    content: '';
    width: 2.5px;
    height: 75%;
    position: absolute;
    top: 5px;
    right: 0;
    background: var(--Primary)
}

.zo-home-section .zo-carousel-section .zo-title span {
    display: block;
    padding: 0 15px 0 0;
    color: var(--Sub)
}

.zo-home-section .zo-stats-section {
    width: 100%;
    display: inline-block;
    padding: 60px 0
}

.zo-home-section .zo-stats-section .zo-content {
    font-size: .95rem
}

.zo-home-section .zo-stats-section .zo-content strong {
    display: block;
    margin: 0 0 10px;
    font-size: 1.25rem;
}

.zo-home-section .zo-stats-section .zo-content p {
    text-align: justify
}

.zo-home-section .zo-stats-section .zo-stats .zo-stat {
    width: 100%;
    display: inline-block;
    padding: 15px;
    background: var(--Primary) url(/public/assets/img/site/footer-pattern.png);
    background-size: cover;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, .075)
}

.zo-home-section .zo-stats-section .zo-stats .zo-stat img {
    display: block;
    margin: 0 0 10px
}

.zo-home-section .zo-stats .zo-stat strong {
    font-size: 1.25rem;
    color: var(--White)
}

.zo-home-section .zo-stats .zo-stat span {
    display: block;
    color: var(--White)
}

@media (max-width: 575px) {

    .zo-home-section .zo-stats .v-col-6 {
        flex: 0 0 100%;
        max-width: 100%
    }
}

.zo-university-section {
    width: 100%;
    display: inline-block;
    padding: 60px 0
}

.zo-university-section .zo-swiper {
    margin: -15px 0 0
}

.zo-university-section .zo-swiper :deep(.swiper-wrapper) {
    padding: 30px 0
}

.zo-university-section .zo-university {
    width: 100%;
    height: 80px;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 15px;
    background: var(--White);
    border-radius: 1rem;
    box-shadow: 0 5px 30px rgba(0, 0, 0, .085)
}

@media (max-width: 960px) {

    .zo-university-section {
        padding: 30px 0
    }
}

.zo-home-section .zo-about-section h3 {
    display: block;
    margin: 0 0 10px;
    font-size: 1.5rem
}

.zo-home-section .zo-about-section p {
    padding: 0 0 0 15px;
    text-align: justify
}

.zo-home-section .zo-about-section a {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin: 15px 0;
    padding: 10px 25px;
    background: var(--Primary);
    color: var(--White);
    border-radius: .5rem
}

.zo-home-section .zo-about-section figure img {
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 5px 30px rgba(0, 0, 0, .085)
}

@media (max-width: 1280px) {

    .zo-about-section .zo-row {
        flex-direction: column-reverse
    }
}

@media (max-width: 960px) {

    .zo-home-section .zo-about-section p {
        padding: 0
    }
}
</style>
