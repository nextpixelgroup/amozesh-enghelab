<template>
    <div class="zo-courses-section" v-if="courses.length">
        <div class="zo-swiper">
            <swiper
                dir="rtl"
                :slides-per-view="3.5"
                :space-between="10"
                :modules="[Navigation]"
                navigation
                :breakpoints="breakpoints"
            >
                <swiper-slide v-for="(course, index) in courses" :key="index">
                    <CourseCard :course="course" />
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script setup>
import { Navigation } from "swiper/modules";
import { Swiper, SwiperSlide } from "swiper/vue";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";

const props = defineProps({
    courses: {
        type: Array,
    }
})

const breakpoints = {
    320: {
        slidesPerView: 1,
        spaceBetween: 10,
    },
    640: {
        slidesPerView: 2,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 3.5,
        spaceBetween: 10,
    }
}
</script>