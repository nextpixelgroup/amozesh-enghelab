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
import CourseCard2 from "@/Components/Web/Courses/CourseCard2.vue";

const props = defineProps({
    courses: {
        type: Array,
    }
})
</script>
<style scoped>
    
.zo-prerequisites-courses-section {
    margin: 0 0 30px;
    padding: 0 30px
}

.zo-prerequisites-courses-section .zo-title {
    display: block;
    margin: 0 0 5px;
    position: relative;
    top: 0;
    font-size: 1.125rem;
    color: var(--Secondary)
}

.zo-prerequisites-courses-section .zo-courses-section .zo-course figure {
    margin: 0
}

.zo-prerequisites-courses-section .zo-courses-section .zo-course figure .zo-thumbnail img {
    height: 170px
}

.zo-prerequisites-courses-section .zo-courses-section .zo-course .zo-content ul {
    gap: 0
}

@media (max-width: 960px) {

    .zo-prerequisites-courses-section {
        padding: 0 15px
    }
}
</style>