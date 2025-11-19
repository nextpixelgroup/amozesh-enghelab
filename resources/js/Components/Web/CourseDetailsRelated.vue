<template>
    <div class="zo-related-courses-section" v-if="courses.length">
        <div class="zo-courses-section">
            <strong class="zo-title">دوره‌های مرتبط</strong>
            <div class="zo-swiper">
                <swiper
                    dir="rtl"
                    :slides-per-view="3.5"
                    :space-between="35"
                    :modules="[Navigation]"
                    navigation
                >
                    <swiper-slide
                        v-for="(course, index) in courses"
                        :key="index"
                    >
                        <CourseCard :course="course"/>
                    </swiper-slide>
                </swiper>
            </div>
        </div>
    </div>
</template>
<script setup>

import {Navigation} from "swiper/modules";
import {Swiper, SwiperSlide} from "swiper/vue";
import CourseCard from "@/Components/Web/CourseCard.vue";

const props = defineProps({
    courses: {
        type: Array,
    }
})
</script>
