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
                                <i :class="lesson.completed ? 'zo-check' : 'zo-uncheck'"></i>
                                <strong>{{ lesson.title }}</strong>
                            </div>
                        </v-col>
                        <v-col cols="12" md="3">
                            <ul class="zo-subinfo">
                                <li>{{ lesson.duration }} ساعت</li>
                                <li>
                                    <div v-if="lesson.completed" class="zo-check">
                                        <i class="mdi mdi-check"></i>
                                    </div>
                                    <div v-else class="zo-play">
                                        <i class="mdi mdi-play-circle-outline"></i>
                                    </div>
                                </li>
                                <li>
                                    <v-btn variant="outlined" rounded="xl"
                                           class="zo-download">
                                        دانلود
                                    </v-btn>
                                </li>
                            </ul>
                        </v-col>
                    </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <CustomVideoPlayer src="https://dl.enghelab.ir/uploads/videos/courses/2.mp4" filename="2.mp4"/>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-expansion-panel-text>
</template>
<script setup>

import CustomVideoPlayer from "@/Components/CustomVideoPlayer.vue";

const props = defineProps({
    lessons: {
        type: Object,
    }
})

</script>
