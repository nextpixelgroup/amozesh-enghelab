<template>
    <Head title="Path"/>
    <WebLayout>
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
                                    <span>مسیرها</span>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </v-col>
            </v-row>
        </v-container>
        <div class="zo-path-about-section">
            <v-container>
                <v-row class="justify-center">
                    <v-col lg="9" cols="12">
                        <div class="zo-title-section">
                            <img src="/assets/img/site/right-green.svg" alt="">
                            <strong>مسیرهای آموزشی</strong>
                            <img src="/assets/img/site/left-green.svg" alt="">
                        </div>
                        <p>
                            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک
                            است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی
                            تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی
                            در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم
                            افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان
                            فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و
                            شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات
                            پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.
                        </p>
                    </v-col>
                </v-row>
            </v-container>
        </div>
        <div class="zo-path-section" v-for="(item,index) in path">
            <v-container :key="index">
                <v-row>
                    <v-col cols="12">
                        <div class="zo-title-section">
                            <strong>{{item.title}}</strong>
                            <span>{{item.description}}</span>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <div class="zo-courses-section">
                <div class="zo-swiper">
                    <Swiper
                        dir="rtl"
                        :modules="[Navigation]"
                        :breakpoints="{
                            0: { slidesPerView: 1.5, spaceBetween: 15 },
                            575: { slidesPerView: 2.5, spaceBetween: 15 },
                            990: { slidesPerView: 3.5, spaceBetween: 15 },
                            1200: { slidesPerView: 6.15, spaceBetween: 15 }
                        }"
                    >
                        <swiper-slide
                            v-for="(course, index) in item.courses"
                            :key="index"
                            :class="[course.passed ? 'zo-passed' : 'zo-notpassed' ]"
                        >
                            <div
                                class="zo-step"
                                :class="(index + 1) % 2 === 1 ? 'zo-up' : 'zo-down'"
                            >
                                <span>{{ index + 1 }}</span>
                            </div>
                            <CourseCard :course="course"/>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
        </div>
    </WebLayout>
</template>
<script setup>
import {Head} from '@inertiajs/vue3'
import WebLayout from "@/Layouts/WebLayout.vue";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";
import {Navigation} from "swiper/modules";
import 'swiper/css'
import 'swiper/css/navigation'
import {Swiper, SwiperSlide} from "swiper/vue";
import {ref} from "vue";

const props = defineProps({
    path: Object
})
const path = ref(props.path.data);
</script>
