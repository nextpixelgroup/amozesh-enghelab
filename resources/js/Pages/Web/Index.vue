<template>

    <Head title="Welcome" />
    <WebLayout>
        <div class="zo-home-section">
            <div class="zo-slider-section">
                <div class="zo-swiper">
                    <swiper dir="rtl" :slides-per-view="1" :modules="[Navigation]" navigation>
                        <swiper-slide>
                            <a href="#">
                                <img src="/assets/img/sample/slide.png" alt="">
                                <div class="zo-caption">
                                    <strong>دوره کتاب چهت حکمت نبوی(ص)</strong>
                                    <span>استاد: علیرضا عباسی</span>
                                </div>
                            </a>
                        </swiper-slide>
                        <swiper-slide>
                            <a href="#">
                                <img src="/assets/img/sample/slide.png" alt="">
                                <div class="zo-caption">
                                    <strong>دوره کتاب چهت حکمت نبوی(ص)</strong>
                                    <span>استاد: علیرضا عباسی</span>
                                </div>
                            </a>
                        </swiper-slide>
                        <swiper-slide>
                            <a href="#">
                                <img src="/assets/img/sample/slide.png" alt="">
                                <div class="zo-caption">
                                    <strong>دوره کتاب چهت حکمت نبوی(ص)</strong>
                                    <span>استاد: علیرضا عباسی</span>
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
                </v-container>
            </div>
            <div class="zo-carousel-section">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <div class="zo-title">
                                <strong>آخرین دوره‌های برگزار شده</strong>
                                <span>توضیحات مربوط به این دوره در اینجا قرار می‌گیرد</span>
                            </div>
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
                                            <strong>79</strong>
                                            <span>دوره‌های آموزشی</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-star.svg" alt="" class="img-fluid" />
                                            <strong>79</strong>
                                            <span>میانگین امتیازات دوره‌ها</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-students.svg" alt="" class="img-fluid" />
                                            <strong>79</strong>
                                            <span>کل شرکت کنندگان</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="/assets/img/site/stat-lessons.svg" alt="" class="img-fluid" />
                                            <strong>79</strong>
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
            </div>
            <div class="zo-university-section">
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
                                    spaceBetween: 60
                                }
                            }">
                        <swiper-slide>
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/31.png" alt="">
                                </figure>
                                <span>دانشگاه امیرکبیر</span>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/32.png" alt="">
                                </figure>
                                <span>دانشگاه تهران</span>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/33.png" alt="">
                                </figure>
                                <span>دانشگاه شهید بهشتی</span>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/34.png" alt="">
                                </figure>
                                <span>دانشگاه خواجه نصیر</span>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/35.png" alt="">
                                </figure>
                                <span>دانشگاه علم و صنعت</span>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/36.png" alt="">
                                </figure>
                                <span>دانشگاه صنعتی شریف</span>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/34.png" alt="">
                                </figure>
                                <span>دانشگاه خواجه نصیر</span>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/35.png" alt="">
                                </figure>
                                <span>دانشگاه علم و صنعت</span>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/36.png" alt="">
                                </figure>
                                <span>دانشگاه صنعتی شریف</span>
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
            <div class="zo-about-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="5" cols="12">
                            <h3>درباره ما</h3>
                            <div class="zo-content">
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.
                            </div>
                            <a href="#">
                                <span>مشاهده بیشتر</span>
                                <i class="mdi mdi-chevron-left"></i>
                            </a>
                        </v-col>
                        <v-col lg="7" cols="12">
                            <figure>
                                <img src="/assets/img/sample/39.png" alt="">
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

</script>
