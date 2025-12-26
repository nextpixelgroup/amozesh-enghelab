<template>
    <WebLayout>
        <!-- Completion Dialog -->
        <v-dialog
            v-model="showCompletionDialog"
            max-width="500"
            persistent
        >
            <v-card>
                <v-card-title class="text-h5 text-center pa-4">
                    <v-icon color="success" size="40" class="ml-2">mdi-check-circle</v-icon>
                    تبریک می‌گم!
                </v-card-title>
                <v-card-text class="text-center text-body-1 pa-4">
                    شما این دوره را با موفقیت به پایان رساندید.
                </v-card-text>
                <v-card-actions class="justify-center pb-4">
                    <v-btn
                        color="primary"
                        :href="course.data.quiz.url"
                        large
                        rounded
                    >
                        آزمون پایان دوره
                    </v-btn>
                    <v-btn
                        color="primary"
                        @click="showCompletionDialog = false"
                        large
                        rounded
                    >
                        متوجه شدم
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-container class="zo-course-section" max-width="1260">
            <v-row dense>
                <v-col cols="12">
                    <CourseDetailsIntro :course="course.data"/>
                </v-col>
                <v-col cols="12" lg="9">
                    <v-card flat class="zo-card">
                        <div class="zo-content">
                            <div class="zo-nav">
                                <ul>
                                    <li v-if="course.data.description">
                                        <a href="#description">درباره دوره</a>
                                    </li>
                                    <li v-if="course.data.seasons.length">
                                        <a href="#lessons">محتوای دوره</a>
                                    </li>
                                    <li v-if="requirements.data.length">
                                        <a href="#requirements">پیش نیازها</a>
                                    </li>
                                    <li>
                                        <a href="#about-teacher">درباره استاد</a>
                                    </li>
                                    <li>
                                        <a href="#comments">نظرات کاربران</a>
                                    </li>
                                </ul>
                            </div>
                           <CourseDetailsDescription :description="course.data.description"/>
                            <div>
                                <div class="zo-hr"></div>
                            </div>

                            <CourseDetailsSeasons :course="course.data" :isEnrolled="isEnrolled"/>
                        </div>
                        <CourseDetailsRequirements :courses="requirements.data"/>
                        <CourseDetailsRelated :courses="related.data"/>
                        <Comments :user="user" :item="course.data" type="course"/>
                    </v-card>
                </v-col>
                <v-col cols="12" lg="3">
                    <CourseDetailsSidebar :user="user" :course="course.data" :isEnrolled="isEnrolled"/>
                </v-col>
            </v-row>
        </v-container>
    </WebLayout>
</template>

<script setup>
import WebLayout from '@/Layouts/WebLayout.vue'
import "swiper/css";
import CourseDetailsSidebar from "@/Components/Web/Courses/CourseDetailsSidebar.vue";
import CourseDetailsIntro from "@/Components/Web/Courses/CourseDetailsIntro.vue";
import Comments from "@/Components/Web/Comments.vue";
import CourseDetailsRelated from "@/Components/Web/Courses/CourseDetailsRelated.vue";
import CourseDetailsRequirements from "@/Components/Web/Courses/CourseDetailsRequirements.vue";
import CourseDetailsSeasons from "@/Components/Web/Courses/CourseDetailsSeasons.vue";
import CourseDetailsDescription from "@/Components/Web/Courses/CourseDetailsDescription.vue";
import {ref, watch} from "vue";

const showCompletionDialog = ref(false);


const props = defineProps({
    course: Object,
    requirements: Object,
    related: Object,
    isEnrolled: Object,
    user: Object,
})
const activeSection = ref(''); // مثلاً 'lessons' یا 'requirements'
console.log(props.course)
// Watch for course data changes
watch(() => props.course, (newVal) => {
    if (newVal?.data?.hasCompletedCourse) {
        showCompletionDialog.value = true;
    }
}, { immediate: true, deep: true });


</script>
