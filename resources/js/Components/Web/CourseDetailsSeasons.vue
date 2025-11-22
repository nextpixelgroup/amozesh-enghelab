<template>
    <div class="zo-info-section">
        <v-row dense class="align-center">
            <v-col cols="12" md="3">
                <strong>محتوای دوره</strong>
            </v-col>
            <v-col cols="12" md="9">
                <ul class="zo-info">
                    <li>تعداد فصل: {{ course.stats.seasons }}</li>
                    <li>تعداد درس: {{ course.stats.lessons }}</li>
                    <li>مدت زمان: {{ course.stats.duration }}</li>
                </ul>
            </v-col>
        </v-row>
    </div>
    <v-expansion-panels
        v-if="course.seasons.length"
        v-model="openSeason"
        multiple
        class="zo-curriculum-section"
    >
        <v-expansion-panel
            v-for="(season, sIndex) in course.seasons"
            :key="sIndex"
            class="zo-topic-section"
        >
            <v-expansion-panel-title>
                <v-row class="align-center">
                    <v-col cols="12" md="9">
                        <div class="zo-title">
                            <strong>{{ season.title }}</strong>
                            <p>{{ season.description }}</p>
                        </div>
                    </v-col>
                    <v-col cols="12" md="3">
                        <ul class="zo-meta">
                            <li>{{ season.lessons.length }} درس</li>
                            <li>{{ season.duration }}</li>
                        </ul>
                    </v-col>
                </v-row>
            </v-expansion-panel-title>
            <CourseDetailsLessons :lessons="season.lessons" :mustCompleteQuizzes="course.must_complete_quizzes" :isEnrolled="isEnrolled"/>
        </v-expansion-panel>
    </v-expansion-panels>
</template>
<script setup>

import {ref} from "vue";
import CourseDetailsLessons from "@/Components/Web/CourseDetailsLessons.vue";
const props = defineProps({
    course: {
        type: Object,
    },
    isEnrolled: {
        type: Boolean,
    }
});
const openSeason = ref([0]);
</script>
