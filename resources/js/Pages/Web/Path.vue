<template>
    <WebLayout>

        <!-- Notice For Enroll -->
        <v-dialog
            v-model="showDialog"
            max-width="800"
            persistent
            :overlay="false"
            transition="fade-transition"
        >
            <v-card class="elevation-12" style="border-radius: 16px; overflow: hidden; background: #f8f9fa;">
                <!-- هدر با گرادیان primary به secondary -->
                <v-card-title class="pa-4" style="background: #c8a064; color: white; border-radius: 16px 16px 0 0;">
                    <v-icon color="white" class="mr-2">mdi-alert-outline</v-icon>
                    اطلاعیه
                </v-card-title>

                <!-- متن -->
                <v-card-text class="pa-6 text-center">
                    <div class="font-weight-bold"><h4>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ</h4></div>
                    <div class="mt-2 text-black">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد. لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.</div>
                </v-card-text>

                <!-- اکشن‌ها -->
                <v-card-actions class="pa-4" style="border-top: 1px solid #eee;">
                    <v-spacer></v-spacer>
                    <v-btn
                        @click="onAcceptPathMessage"
                        color="secondary"
                        large
                        elevation="1"
                        class="px-6"
                        variant="flat"
                        prepend-icon="mdi mdi-check"
                    >
                        متوجه شدم
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

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
                            سیر مطالعاتی، فرآیندی ساختاریافته برای آموزش روشمند و گام‌به‌گامِ منظومه فکری حضرت آیت‌الله‌العظمی خامنه‌ای است. در این الگو، محتوا به صورت سلسله‌مراتبی تدوین شده و دسترسی به هر مرحله، منوط به گذراندن دقیق گام قبلی و موفقیت در آزمون مربوطه است.

                            با ثبت‌نام در یک سیر مطالعاتی، جهت تضمین تمرکز و یادگیری عمیق، محتوای مراحل آتی تا زمان نهایی‌سازی مرحله جاری برای شما محفوظ (قفل) باقی می‌ماند. دانش‌پژوهان پس از تکمیل موفقیت‌آمیز سیر و کسب حدنصاب نمره در آزمون‌های جامع، گواهی‌نامه رسمی پایان دوره را دریافت خواهند کرد.

                        </p>
                    </v-col>
                </v-row>
            </v-container>
        </div>
        <div class="zo-path-section" v-for="(item,index) in path">
            <v-container :key="index">
                <v-row>
                    <v-col cols="10">
                        <div class="zo-title-section">
                            <strong>{{item.title}}</strong>
                            <span>{{item.description}}</span>
                        </div>
                    </v-col>
                    <v-col cols="2">
                        <v-btn
                            v-if="!item.enrolled"
                            class="w-100"
                            variant="elevated"
                            elevation="3"
                            color="primary"
                            prepend-icon="mdi mdi-account-plus"
                            @click="enroll(item.id)"
                            :disabled="disabled"
                            :loading="isLoading"
                        >
                            ثبت‌نام مسیر
                        </v-btn>
                        <v-btn
                            v-else
                            class="w-100"
                            variant="outlined"
                            color="primary"
                            prepend-icon="mdi mdi-school"
                            style="cursor: auto"
                        >
                            ثبت‌نام کرده‌اید
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
            <div class="zo-courses-section">
                <div class="zo-swiper">
                    <Swiper dir="rtl" :modules="[Navigation]" :breakpoints="{
                            0: { slidesPerView: 1.5, spaceBetween: 15 },
                            575: { slidesPerView: 2.5, spaceBetween: 15 },
                            990: { slidesPerView: 3.5, spaceBetween: 15 },
                            1200: { slidesPerView: 6.15, spaceBetween: 15 }
                        }">
                        <swiper-slide
                            v-for="(course, index) in item.courses"
                            :key="index"
                            :class="!isAuth || (course.enrolled || course.passed || course.currentCourse) ? 'zo-passed': 'zo-notpassed'"
                        >
                            <div v-if="index !== item.courses.length - 1" class="zo-step" :class="(index + 1) % 2 === 1 ? 'zo-up' : 'zo-down'">
                                <span>{{ index + 1 }}</span>
                            </div>
                            <CourseCard :course="course" />
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
        </div>
    </WebLayout>
</template>
<script setup>
import {Head, router, usePage} from '@inertiajs/vue3'
import WebLayout from "@/Layouts/WebLayout.vue";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";
import { Navigation } from "swiper/modules";
import 'swiper/css'
import 'swiper/css/navigation'
import { Swiper, SwiperSlide } from "swiper/vue";
import {computed, ref} from "vue";
import {route} from "ziggy-js";
const page = usePage();
const props = defineProps({
    path: Object,
})
const isAuth = ref(page.props.isAuth);
const path = computed( () => props.path.data);
const disabled = ref(false);
const isLoading = ref(false);

const showDialog = ref(false);

// بررسی اینکه آیا کاربر قبلاً دیالوگ را دیده است
const hasSeenPath = localStorage.getItem('hasSeenPathDialog') === 'true';

// فقط اگر کاربر وارد شده باشد و قبلاً دیالوگ را ندیده باشد، نمایش دهید
if (isAuth.value && !hasSeenPath) {
    showDialog.value = true;
}

// در صورتی که کاربر روی دکمه کلیک کرد، وضعیت را ذخیره کنید
function onAcceptPathMessage() {
    localStorage.setItem('hasSeenPathDialog', 'true');
    showDialog.value = false;
}


const enroll = (pathId) => {
    router.post(route('web.path.enroll',pathId),[], {
        preserveState: true,
        preserveScroll: true,
        onStart: () => {
            disabled.value = true;
            isLoading.value = true;
        },
        onSuccess: () => {
            disabled.value = false;
            isLoading.value = false;
        },
        onFinish: () => {
            disabled.value = false;
            isLoading.value = false;
        },
        onError: () => {
            disabled.value = false;
            isLoading.value = false;
        }
    });
}
</script>
<style scoped>
.zo-path-section .zo-title-section {
    flex-direction: column;
    align-items: flex-start;
    gap: 5px;
    padding: 0
}

</style>
