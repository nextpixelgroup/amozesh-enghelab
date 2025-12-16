<template>
  <div class="zo-courses-section" v-if="courses.length">
    <div class="zo-swiper">
      <swiper
        dir="rtl"
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
import "swiper/css";
import "swiper/css/navigation";

const props = defineProps({
  courses: {
    type: Array,
    default: () => []
  }
});

const breakpoints = {
  320: { slidesPerView: 1, spaceBetween: 10 },
  640: { slidesPerView: 2, spaceBetween: 10 },
  1024: { slidesPerView: 3.5, spaceBetween: 10 }
};
</script>