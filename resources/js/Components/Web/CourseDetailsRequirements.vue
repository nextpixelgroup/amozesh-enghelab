<template>
    <div class="zo-prerequisites-courses-section" v-if="courses.length" id="requirements">
        <div class="zo-courses-section">
            <strong class="zo-title">پیش نیازها</strong>
            <v-row dense>
                <v-col cols="12" lg="6"
                       v-for="(course, index) in courses"
                       :key="index"
                >
                    <CourseCard2 :course="course"/>
                </v-col>
            </v-row>
        </div>
    </div>
</template>
<script setup>
import CourseCard2 from "@/Components/Web/CourseCard2.vue";

const props = defineProps({
    courses: {
        type: Array,
    }
})
</script>
