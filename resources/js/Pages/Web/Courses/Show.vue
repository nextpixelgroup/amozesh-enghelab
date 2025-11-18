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
                           <CourseDetailsDescription :description="course.data.description"/>
                            <div>
                                <div class="zo-hr"></div>
                            </div>

                            <CourseDetailsSeasons :seasons="seasons" :stats="courseStats"/>
                        </div>
                        <CourseDetailsRequirements :courses="requirements"/>
                        <CourseDetailsRelated :courses="related"/>
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
import "swiper/css";
import CourseDetailsSidebar from "@/Components/Web/CourseDetailsSidebar.vue";
import CourseDetailsIntro from "@/Components/Web/CourseDetailsIntro.vue";
import CourseDetailsComments from "@/Components/Web/CourseDetailsComments.vue";
import CourseDetailsRelated from "@/Components/Web/CourseDetailsRelated.vue";
import CourseDetailsRequirements from "@/Components/Web/CourseDetailsRequirements.vue";
import CourseDetailsSeasons from "@/Components/Web/CourseDetailsSeasons.vue";
import CourseDetailsDescription from "@/Components/Web/CourseDetailsDescription.vue";

const props = defineProps({
    course: Object
})
const tabs = ["درباره دوره", "محتوای دوره", "پیش نیازها", "درباره استاد", "نظرات کاربران"];

const courseStats = {
    seasons: 7,
    lessons: 120,
    videos: 17,
};


const seasons = [
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

const requirements = ref([
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

const related = ref([
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
