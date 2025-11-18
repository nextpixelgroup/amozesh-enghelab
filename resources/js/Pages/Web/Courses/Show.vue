<template>
    <WebLayout>
        <v-container class="zo-course-section" max-width="1260">
            <v-row dense>
                <v-col cols="12">
                    <CourseDetailsIntro/>
                </v-col>
                <v-col cols="12" lg="9">
                    <v-card flat class="zo-card">
                        <div class="zo-space">
                            <div class="zo-nav">
                                <ul>
                                    <li>
                                        <a href="#" class="zo-active">درباره دوره</a>
                                    </li>
                                    <li>
                                        <a href="#">محتوای دوره</a>
                                    </li>
                                    <li>
                                        <a href="#">پیش نیازها</a>
                                    </li>
                                    <li>
                                        <a href="#">درباره استاد</a>
                                    </li>
                                    <li>
                                        <a href="#">نظرات کاربران</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="zo-text-section">
                                <strong class="zo-title">درباره دوره</strong>
                                <p>
                                    دوره آموزشی آنلاین جهاد تبیین در گام دوم انقلاب یک برنامه جامع و مهارت‌محور است که
                                    با
                                    هدف توانمندسازی مخاطبان برای شناخت عمیق، روایت صحیح، مقابله با جنگ شناختی و ایفای
                                    نقش
                                    فعال در پیشبرد گام دوم انقلاب طراحی می‌شود.
                                </p>
                                <p>
                                    در این دوره تلاش می‌شود تا شرکت‌کنندگان علاوه بر آشنایی با مبانی نظری و نگاه راهبردی
                                    رهبر انقلاب، به مهارت‌های عملی برای تولید محتوا، پاسخ به شبهات، تحلیل رسانه‌ای و
                                    فعالیت
                                    مؤثر در فضای مجازی و حقیقی مجهز شوند.
                                </p>
                                <h2>خروجی مورد انتظار دوره</h2>
                                <p>فارغ‌التحصیلان این دوره می‌توانند:</p>
                                <ul>
                                    <li>مفاهیم گام دوم انقلاب و جهاد تبیین را به شکل دقیق و قابل فهم برای دیگران بیان
                                        کنند.
                                    </li>
                                    <li>شبهات رایج در حوزه انقلاب، نظام، پیشرفت، آزادی و عدالت را تحلیل و پاسخ دهند.
                                    </li>
                                    <li>با تکنیک‌های عملیات روانی و جنگ روایت‌ها آشنا شده و در برابر آن‌ها واکنش مؤثر
                                        نشان
                                        دهند.
                                    </li>
                                    <li>محتوای رسانه‌ای جذاب و اقناعی تولید و در فضای مجازی منتشر کنند.</li>
                                    <li>نقش فعال‌تری در جریان‌سازی فرهنگی و اجتماعی داشته باشند.</li>
                                </ul>
                                <h2>ویژگی‌های دوره</h2>
                                <ul>
                                    <li>کاملاً کاربردی: از مباحث تئوریک تا مهارت‌های عملی روایت‌گری، اقناع، تولید محتوا
                                        و
                                        تحلیل رسانه.
                                    </li>
                                    <li>متناسب با نیازهای گام دوم انقلاب: تمرکز ویژه بر تحلیل بیانیه و نقش جوانان در
                                        پیشرفت
                                        و عدالت.
                                    </li>
                                    <li>آموزش در بستر جنگ شناختی امروز: مشارکت فعال در مقابله با عملیات رسانه‌ای و
                                        شایعه‌سازی دشمن.
                                    </li>
                                    <li>قابل استفاده برای فعالان فرهنگی و رسانه‌ای: دانشجویان، طلاب، مدیران فرهنگی،
                                        معلمان،
                                        مربیان و…
                                    </li>
                                    <li>پروژه‌محور: در پایان دوره از فراگیران خواسته می‌شود یک طرح عملی جهاد تبیین در
                                        محیط
                                        زندگی یا کاری خود اجرا و ارائه کنند.
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <div class="zo-hr"></div>
                            </div>
                            <div class="zo-info-section">
                                <v-row dense class="align-center">
                                    <v-col cols="12" md="3">
                                        <strong>محتوای دوره</strong>
                                    </v-col>
                                    <v-col cols="12" md="9">
                                        <ul class="zo-info">
                                            <li>{{ courseContent.chapters }} فصل</li>
                                            <li>{{ courseContent.lessons }} درس</li>
                                            <li>{{ courseContent.videos }} ویدیو</li>
                                        </ul>
                                    </v-col>
                                </v-row>
                            </div>
                            <CourseDetailsSeasons/>
                        </div>
                        <CourseDetailsRequirments/>
                        <CourseDetailsRalated/>
                        <CourseDetailsComments/>
                    </v-card>
                </v-col>
                <v-col cols="12" lg="3">
                    <CourseDetailsSidebar/>
                </v-col>
            </v-row>
        </v-container>
    </WebLayout>
</template>

<script setup>
import {ref} from 'vue'
import WebLayout from '@/Layouts/WebLayout.vue'
import {Swiper, SwiperSlide} from "swiper/vue";
import "swiper/css";
import {Navigation, Pagination} from "swiper/modules";
import CourseDetailsSidebar from "@/Components/Web/CourseDetailsSidebar.vue";
import CourseDetailsIntro from "@/Components/Web/CourseDetailsIntro.vue";
import CourseDetailsComments from "@/Components/Web/CourseDetailsComments.vue";
import CourseDetailsRalated from "@/Components/Web/CourseDetailsRalated.vue";
import CourseDetailsRequirments from "@/Components/Web/CourseDetailsRequirments.vue";
import CourseDetailsSeasons from "@/Components/Web/CourseDetailsSeasons.vue";

const tabs = ["درباره دوره", "محتوای دوره", "پیش نیازها", "درباره استاد", "نظرات کاربران"];

const courseContent = {
    chapters: 7,
    lessons: 120,
    videos: 17,
};

const openChapter = ref([0]);

const chapters = [
    {
        title: "فصل اول",
        description: "مبانی نظری جهاد تبیین",
        duration: 7,
        lessons: [
            {title: "تعریف مفهومی جهاد تبیین و جایگاه آن در اندیشه اسلامی", duration: 1, completed: true},
            {title: "ضرورت تبیین در شرایط جنگ ترکیبی و جهاد روایت", duration: 2, completed: false},
            {title: "بررسی مؤلفه‌های جنگ شناختی و عملیات روانی", duration: 2, completed: false},
            {title: "رسالت نخبگان، رسانه‌ها و عموم جامعه در جهاد تبیین", duration: 1, completed: false},
            {title: "الزامات اخلاقی و معرفتی برای فعالیت‌های تبیینی", duration: 3, completed: false},
        ],
    },
    {
        title: "فصل دوم",
        description: "گام دوم انقلاب و الزامات تمدنی",
        duration: 6,
        lessons: [
            {title: "درس اول", duration: 2, completed: true},
            {title: "درس دوم", duration: 3, completed: false},
        ],
    },
    {
        title: "فصل سوم",
        description: "دشمن‌شناسی و جنگ شناختی",
        duration: 6,
        lessons: [
            {title: "درس اول", duration: 2, completed: true},
            {title: "درس دوم", duration: 3, completed: false},
        ],
    },
    {
        title: "فصل چهارم",
        description: "کاربردهای عملی جهاد تبیین در جامعه",
        duration: 6,
        lessons: [
            {title: "درس اول", duration: 2, completed: true},
            {title: "درس دوم", duration: 3, completed: false},
        ],
    },
];

const prerequisites = ref([
    {
        image: '/assets/img/sample/1.png',
        category: 'فلسفه',
        title: 'دوره کتاب چهل حکمت نبوی (ص)',
        professor: 'عباسی علیزاده',
        time: '۰۳:۵۸',
        students: '۲.۱۲۳',
        price: 'رایگان',
    },
    {
        image: '/assets/img/sample/2.png',
        category: 'فلسفه',
        title: 'دوره کتاب چهل حکمت نبوی (ص)',
        professor: 'عباسی علیزاده',
        time: '۰۳:۵۸',
        students: '۲.۱۲۳',
        price: 'رایگان',
    },
])

const courses = ref([
    {
        image: '/assets/img/sample/1.png',
        category: 'فلسفه',
        title: 'دوره کتاب چهل حکمت نبوی (ص)',
        professor: 'عباسی علیزاده',
        time: '۰۳:۵۸',
        students: '۲.۱۲۳',
        price: 'رایگان',
    },
    {
        image: '/assets/img/sample/2.png',
        category: 'فلسفه',
        title: 'دوره کتاب چهل حکمت نبوی (ص)',
        professor: 'عباسی علیزاده',
        time: '۰۳:۵۸',
        students: '۲.۱۲۳',
        price: 'رایگان',
    },
    {
        image: '/assets/img/sample/2.png',
        category: 'فلسفه',
        title: 'دوره کتاب چهل حکمت نبوی (ص)',
        professor: 'عباسی علیزاده',
        time: '۰۳:۵۸',
        students: '۲.۱۲۳',
        price: 'رایگان',
    },
    {
        image: '/assets/img/sample/2.png',
        category: 'فلسفه',
        title: 'دوره کتاب چهل حکمت نبوی (ص)',
        professor: 'عباسی علیزاده',
        time: '۰۳:۵۸',
        students: '۲.۱۲۳',
        price: 'رایگان',
    },
])
</script>
