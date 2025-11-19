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
                                <li>{{ lesson.duration }}</li>
                                <li>
                                    <div v-if="lesson.completed" class="zo-check">
                                        <i class="mdi mdi-check"></i>
                                    </div>
                                    <div v-else class="zo-play">
                                        <i class="mdi mdi-play-circle-outline"></i>
                                    </div>
                                </li>
                                <li>
                                    <v-btn
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
                <v-expansion-panel-text v-if="lesson.video">
                    <CustomVideoPlayer :src="lesson.video" :poster="lesson.poster" :filename="lesson.download_url"/>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-expansion-panel-text>
</template>
<script setup>

import CustomVideoPlayer from "@/Components/CustomVideoPlayer.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    lessons: {
        type: Object,
    }
})
const downloadVideo = (url) => {
    if(!url)
        alert(1)
    window.open(url, '_blank');
}

</script>
