<template>

    <Head title="Welcome" />
    <WebLayout>
        <div class="zo-about-section">
            <v-container>
                <v-row dense>
                    <v-col cols="12">
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#">خانه</a>
                                    </li>
                                    <li>
                                        <span>درباره ما</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <div class="zo-excerpt-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="4" cols="12">
                            <h1>درباره ما</h1>
                            <p>
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.
                            </p>
                        </v-col>
                        <v-col lg="8" cols="12">
                            <div class="zo-swiper">
                                <swiper dir="rtl" :slides-per-view="1" :space-between="5" :modules="[Pagination]" :pagination="{ clickable: true }">
                                    <swiper-slide>
                                        <figure>
                                            <img src="/assets/img/sample/30.png" alt="">
                                        </figure>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <figure>
                                            <img src="/assets/img/sample/30.png" alt="">
                                        </figure>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <figure>
                                            <img src="/assets/img/sample/30.png" alt="">
                                        </figure>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <figure>
                                            <img src="/assets/img/sample/30.png" alt="">
                                        </figure>
                                    </swiper-slide>
                                </swiper>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-university-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col cols="12">
                            <strong>همکاری با سازمان‌ها و دانشگاه‌ها</strong>
                        </v-col>
                        <v-col lg="3" cols="12">
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/31.png" alt="">
                                </figure>
                                <span>دانشگاه امیرکبیر</span>
                            </div>
                        </v-col>
                        <v-col lg="3" cols="12">
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/32.png" alt="">
                                </figure>
                                <span>دانشگاه شهید بهشتی</span>
                            </div>
                        </v-col>
                        <v-col lg="3" cols="12">
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/33.png" alt="">
                                </figure>
                                <span>دانشگاه خواجه نصیر</span>
                            </div>
                        </v-col>
                        <v-col lg="3" cols="12">
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/34.png" alt="">
                                </figure>
                                <span>دانشگاه علم و صنعت</span>
                            </div>
                        </v-col>
                        <v-col lg="3" cols="12">
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/35.png" alt="">
                                </figure>
                                <span>دانشگاه امام صادق</span>
                            </div>
                        </v-col>
                        <v-col lg="3" cols="12">
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/36.png" alt="">
                                </figure>
                                <span>دانشگاه امام رضا</span>
                            </div>
                        </v-col>
                        <v-col lg="3" cols="12">
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/37.png" alt="">
                                </figure>
                                <span>دانشگاه اصفهان</span>
                            </div>
                        </v-col>
                        <v-col lg="3" cols="12">
                            <div class="zo-university">
                                <figure>
                                    <img src="/assets/img/sample/38.png" alt="">
                                </figure>
                                <span>دانشگاه تهران</span>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-institute-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="6" cols="12">
                            <figure>
                                <img src="/assets/img/sample/39.png" alt="">
                            </figure>
                        </v-col>
                        <v-col lg="6" cols="12">
                            <strong>معرفی موسسه</strong>
                            <p>
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است،
                            </p>
                            <a href="#">
                                <span>مشاهده بیشتر</span>
                                <i class="mdi mdi-chevron-left"></i>
                            </a>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-activity-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="6" cols="12">
                            <strong>معرفی موسسه</strong>
                            <p>
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است،
                            </p>
                            <a href="#">
                                <span>مشاهده بیشتر</span>
                                <i class="mdi mdi-chevron-left"></i>
                            </a>
                        </v-col>
                        <v-col lg="6" cols="12">
                            <figure>
                                <img src="/assets/img/sample/40.png" alt="">
                            </figure>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <v-container>
                <v-row class="justify-center">
                    <v-col lg="9" cols="12">
                        <div class="zo-title-section">
                            <img src="/assets/img/site/right-primary.svg" alt="">
                            <strong>معرفی خانه انقلاب اسلامی</strong>
                            <img src="/assets/img/site/left-primary.svg" alt="">
                        </div>
                        <p>
                            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.
                        </p>
                        <p>
                            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.
                        </p>
                    </v-col>
                </v-row>
            </v-container>
            <div class="zo-prof-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col cols="12">
                            <div class="zo-title-section">
                                <img src="/assets/img/site/right-primary.svg" alt="">
                                <strong>اساتید مجموعه</strong>
                                <img src="/assets/img/site/left-primary.svg" alt="">
                            </div>
                        </v-col>
                        <v-col lg="3" sm="6" cols="12">
                            <a href="#">
                                <figure>
                                    <img src="/assets/img/sample/41.png" alt="">
                                </figure>
                                <strong>حجت الاسلام و المسلمین آقایی</strong>
                            </a>
                            <ul>
                                <li><a href="#"><img src="/assets/img/site/social-1.svg" alt="" class="img-fluid"></a></li>
                                <li><a href="#"><img src="/assets/img/site/social-2.svg" alt="" class="img-fluid"></a></li>
                            </ul>
                        </v-col>
                        <v-col lg="3" sm="6" cols="12">
                            <a href="#">
                                <figure>
                                    <img src="/assets/img/sample/42.png" alt="">
                                </figure>
                                <strong>دکتر فائزه عظیم زاده اردبیلی</strong>
                            </a>
                            <ul>
                                <li><a href="#"><img src="/assets/img/site/social-1.svg" alt="" class="img-fluid"></a></li>
                                <li><a href="#"><img src="/assets/img/site/social-2.svg" alt="" class="img-fluid"></a></li>
                            </ul>
                        </v-col>
                        <v-col lg="3" sm="6" cols="12">
                            <a href="#">
                                <figure>
                                    <img src="/assets/img/sample/43.png" alt="">
                                </figure>
                                <strong>دکتر رحیم پور ازغدی</strong>
                            </a>
                            <ul>
                                <li><a href="#"><img src="/assets/img/site/social-1.svg" alt="" class="img-fluid"></a></li>
                                <li><a href="#"><img src="/assets/img/site/social-2.svg" alt="" class="img-fluid"></a></li>
                            </ul>
                        </v-col>
                        <v-col lg="3" sm="6" cols="12">
                            <a href="#">
                                <figure>
                                    <img src="/assets/img/sample/44.png" alt="">
                                </figure>
                                <strong>حجت السلام و المسلمین قرائتی</strong>
                            </a>
                            <ul>
                                <li><a href="#"><img src="/assets/img/site/social-1.svg" alt="" class="img-fluid"></a></li>
                                <li><a href="#"><img src="/assets/img/site/social-2.svg" alt="" class="img-fluid"></a></li>
                            </ul>
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
import { Pagination } from "swiper/modules";
import { Swiper, SwiperSlide } from "swiper/vue";

</script>
