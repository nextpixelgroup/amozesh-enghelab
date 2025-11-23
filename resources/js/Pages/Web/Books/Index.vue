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
            <v-container>
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
                                <swiper-slide>
                                    <img src="/assets/img/sample/25.png">
                                </swiper-slide>
                                <swiper-slide>
                                    <img src="/assets/img/sample/26.png">
                                </swiper-slide>
                                <swiper-slide>
                                    <img src="/assets/img/sample/27.png">
                                </swiper-slide>
                                <swiper-slide>
                                    <img src="/assets/img/sample/25.png">
                                </swiper-slide>
                                <swiper-slide>
                                    <img src="/assets/img/sample/26.png">
                                </swiper-slide>
                                <swiper-slide>
                                    <img src="/assets/img/sample/27.png">
                                </swiper-slide>
                            </swiper>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <div class="zo-selected-books-section">
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
                        <swiper-slide>
                            <a href="#" class="zo-book">
                                <v-row dense class="align-end">
                                    <v-col md="5" cols="12">
                                        <img src="assets/img/sample/18.png" alt="">
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-content">
                                            <h2>تربیت کودک</h2>
                                            <div class="zo-meta">
                                                <div class="zo-star">
                                                    <i class="mdi mdi-star-outline"></i>
                                                    <span>۴.۹</span>
                                                </div>
                                                <div class="zo-price">
                                                    <strong>2.450.000</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                            <div class="zo-add">
                                                <i class="mdi mdi-cart-outline"></i>
                                                <span>خرید آنلاین</span>
                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </a>
                        </swiper-slide>
                        <swiper-slide>
                            <a href="#" class="zo-book">
                                <v-row dense class="align-end">
                                    <v-col md="5" cols="12">
                                        <img src="assets/img/sample/19.png" alt="">
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-content">
                                            <h2>تربیت کودک</h2>
                                            <div class="zo-meta">
                                                <div class="zo-star">
                                                    <i class="mdi mdi-star-outline"></i>
                                                    <span>۴.۹</span>
                                                </div>
                                                <div class="zo-price">
                                                    <strong>2.450.000</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                            <div class="zo-add">
                                                <i class="mdi mdi-cart-outline"></i>
                                                <span>خرید آنلاین</span>
                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </a>
                        </swiper-slide>
                        <swiper-slide>
                            <a href="#" class="zo-book">
                                <v-row dense class="align-end">
                                    <v-col md="5" cols="12">
                                        <img src="assets/img/sample/20.png" alt="">
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-content">
                                            <h2>تربیت کودک</h2>
                                            <div class="zo-meta">
                                                <div class="zo-star">
                                                    <i class="mdi mdi-star-outline"></i>
                                                    <span>۴.۹</span>
                                                </div>
                                                <div class="zo-price">
                                                    <strong>2.450.000</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                            <div class="zo-add">
                                                <i class="mdi mdi-cart-outline"></i>
                                                <span>خرید آنلاین</span>
                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </a>
                        </swiper-slide>
                        <swiper-slide>
                            <a href="#" class="zo-book">
                                <v-row dense class="align-end">
                                    <v-col md="5" cols="12">
                                        <img src="assets/img/sample/21.png" alt="">
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-content">
                                            <h2>تربیت کودک</h2>
                                            <div class="zo-meta">
                                                <div class="zo-star">
                                                    <i class="mdi mdi-star-outline"></i>
                                                    <span>۴.۹</span>
                                                </div>
                                                <div class="zo-price">
                                                    <strong>2.450.000</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                            <div class="zo-add">
                                                <i class="mdi mdi-cart-outline"></i>
                                                <span>خرید آنلاین</span>
                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </a>
                        </swiper-slide>
                        <swiper-slide>
                            <a href="#" class="zo-book">
                                <v-row dense class="align-end">
                                    <v-col md="5" cols="12">
                                        <img src="assets/img/sample/22.png" alt="">
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-content">
                                            <h2>تربیت کودک</h2>
                                            <div class="zo-meta">
                                                <div class="zo-star">
                                                    <i class="mdi mdi-star-outline"></i>
                                                    <span>۴.۹</span>
                                                </div>
                                                <div class="zo-price">
                                                    <strong>2.450.000</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                            <div class="zo-add">
                                                <i class="mdi mdi-cart-outline"></i>
                                                <span>خرید آنلاین</span>
                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </a>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
            <div class="zo-books-section">
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
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/16.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/17.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/18.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/19.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/18.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
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
                                <a href="#">مشاهده همه</a>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-special-books-section">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <swiper class="zo-swiper" dir="rtl" :slides-per-view="1" :space-between="5" :modules="[Navigation]" :navigation="{ nextEl: '.zo-next-3', prevEl: '.zo-prev-3' }">
                                <swiper-slide>
                                    <a href="#" class="zo-book">
                                        <div class="zo-content">
                                            <img src="/assets/img/sample/28.png" alt="" class="img-fluid">
                                            <div class="zo-text">
                                                <h2>جاودانه‌ی تاریخ (همراه با تقریظ رهبری)</h2>
                                                <div class="zo-sub">
                                                    مروری بر شخصیت و زندگی امیرالمؤمنین‌علی‌(علیه‌السّلام) در بیان حضرت آیت‌الله‌العظمی خامنه‌ای(مدّظلّه‌العالی)
                                                </div>
                                                <p>
                                                    این کتاب دو دو بخش و ده فصل که توسّط حجت‌الاسلام والمسلمین محمّد محمّدیان گردآوری و تنظیم شده، مروری بر شخصیّت و زندگی امیرالمومنین علی(ع) در اندیشه‌ی حضرت آیت‌الله‌العظمی خامنه‌ای است. این کتاب شامل بیانات رهبر معظّم انقلاب پیرامون زندگی و زمانه‌ی امیرالمومنین علی(ع) است.
                                                </p>
                                                <div class="zo-meta">
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                    <div class="zo-add">
                                                        <i class="mdi mdi-cart-outline"></i>
                                                        <span>خرید آنلاین</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </swiper-slide>
                                <swiper-slide>
                                    <a href="#" class="zo-book">
                                        <div class="zo-content">
                                            <img src="/assets/img/sample/28.png" alt="" class="img-fluid">
                                            <div class="zo-text">
                                                <h2>جاودانه‌ی تاریخ (همراه با تقریظ رهبری)</h2>
                                                <div class="zo-sub">
                                                    مروری بر شخصیت و زندگی امیرالمؤمنین‌علی‌(علیه‌السّلام) در بیان حضرت آیت‌الله‌العظمی خامنه‌ای(مدّظلّه‌العالی)
                                                </div>
                                                <p>
                                                    این کتاب دو دو بخش و ده فصل که توسّط حجت‌الاسلام والمسلمین محمّد محمّدیان گردآوری و تنظیم شده، مروری بر شخصیّت و زندگی امیرالمومنین علی(ع) در اندیشه‌ی حضرت آیت‌الله‌العظمی خامنه‌ای است. این کتاب شامل بیانات رهبر معظّم انقلاب پیرامون زندگی و زمانه‌ی امیرالمومنین علی(ع) است.
                                                </p>
                                                <div class="zo-meta">
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                    <div class="zo-add">
                                                        <i class="mdi mdi-cart-outline"></i>
                                                        <span>خرید آنلاین</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
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
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/23.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/24.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/18.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/16.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
                                    </swiper-slide>
                                    <swiper-slide>
                                        <a href="#" class="zo-book">
                                            <img src="assets/img/sample/17.png" alt="">
                                            <div class="zo-content">
                                                <h2>تربیت کودک</h2>
                                                <div class="zo-meta">
                                                    <div class="zo-star">
                                                        <i class="mdi mdi-star-outline"></i>
                                                        <span>۴.۹</span>
                                                    </div>
                                                    <div class="zo-price">
                                                        <strong>2.450.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                                <div class="zo-add">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                    <span>خرید آنلاین</span>
                                                </div>
                                            </div>
                                        </a>
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

const sort = ref([
    { title: 'جدیدترین' },
    { title: 'قدیمی‌ترین' },
])

const category = ref([
    'دسته‌بندی',
    'فرهنگ و رسانه',
    'انقلاب ایران',
    'تفکر ذهن',
])

const model = ref('دسته‌بندی')
const page = ref(1)

</script>