<template>
    <v-expansion-panel-text>
        <v-expansion-panels multiple class="zo-lessons-section">
            <v-expansion-panel
                v-for="(lesson, lIndex) in lessons"
                :key="lIndex"
                hide-actions
                class="zo-lesson-section"
            >
                <v-expansion-panel-title>
                    <v-row class="align-center">
                        <v-col cols="12" md="9">
                            <div class="zo-subtitle">
                                <i v-if="!isEnrolled" class="zo-uncheck"></i>
                                <i v-else-if="lesson.completed" class="zo-check"></i>
                                <i v-else class="zo-uncheck"></i>
                                <strong>{{ lesson.title }}</strong>
                                <p class="zo-description">{{ lesson.description }}</p>
                            </div>
                        </v-col>
                        <v-col cols="12" md="3">
                            <ul class="zo-subinfo">
                                <li>{{ lesson.duration }}</li>
                                <li>

                                    <div v-if="!isEnrolled || !lesson.can_show_video" class="zo-play">
                                        <i class="mdi mdi-lock-outline"></i>
                                    </div>
                                    <div v-else-if="lesson.completed" class="zo-check">
                                        <i class="mdi mdi-check"></i>
                                    </div>
                                    <div v-else class="zo-play">
                                        <i class="mdi mdi-play-circle-outline"></i>
                                    </div>
                                </li>
                                <li>
                                    <v-btn
                                        v-if="isEnrolled && lesson.can_show_video"
                                        variant="outlined"
                                        rounded="xl"
                                        class="zo-download"
                                        @click="downloadVideo(lesson.download_url)"
                                    >
                                        دانلود
                                    </v-btn>
                                </li>
                            </ul>
                        </v-col>
                    </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text v-if="isEnrolled && lesson.video && lesson.can_show_video">
                    <CustomVideoPlayer :lesson="lesson" :src="lesson.video" :poster="lesson.poster" :filename="lesson.download_url"/>
                    <CourseLessonQuiz :lesson="lesson" />
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-expansion-panel-text>
</template>
<script setup>

import CustomVideoPlayer from "@/Components/CustomVideoPlayer.vue";
import {Link} from "@inertiajs/vue3";
import CourseLessonQuiz from "@/Components/Web/Courses/CourseLessonQuiz.vue";

const props = defineProps({
    lessons: {
        type: Object,
    },
    isEnrolled: {
        type: Boolean,
    },
    mustCompleteQuizzes: {
        type: Boolean,
    },
})
const downloadVideo = (url) => {
    if(!url)
        return false;
    window.open(url, '_blank');
}

</script>
