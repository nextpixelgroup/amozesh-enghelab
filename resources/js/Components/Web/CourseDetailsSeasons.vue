<template>
    <v-expansion-panels
        v-model="openChapter"
        multiple
        class="zo-curriculum-section"
    >
        <v-expansion-panel
            v-for="(chapter, cIndex) in seasons"
            :key="cIndex"
            class="zo-topic-section"
        >
            <v-expansion-panel-title>
                <v-row class="align-center">
                    <v-col cols="12" md="9">
                        <div class="zo-title">
                            <strong>{{ chapter.title }}</strong>
                            <p>{{ chapter.description }}</p>
                        </div>
                    </v-col>
                    <v-col cols="12" md="3">
                        <ul class="zo-meta">
                            <li>{{ chapter.lessons.length }} درس</li>
                            <li>{{ chapter.duration }} ساعت</li>
                        </ul>
                    </v-col>
                </v-row>
            </v-expansion-panel-title>
            <v-expansion-panel-text>
                <v-expansion-panels multiple class="zo-lessons-section">
                    <v-expansion-panel
                        v-for="(lesson, lIndex) in chapter.lessons"
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
                        <v-expansion-panel-text>Test</v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-expansion-panel-text>
        </v-expansion-panel>
    </v-expansion-panels>
</template>
<script setup>

import {ref} from "vue";
const props = defineProps({
    seasons: {
        type: Array,
    }
});
const openChapter = ref([0]);
</script>
