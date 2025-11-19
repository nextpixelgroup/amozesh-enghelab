<template>
    <WebLayout>
        <v-container class="zo-course-section" max-width="1260">
            <v-row dense>
                <v-col cols="12">
                    <CourseDetailsIntro/>
                </v-col>
                <v-col cols="12" lg="9">
                    <v-card flat class="zo-card">
                        <div class="zo-space">
                            <div class="zo-nav">
                                <ul>
                                    <li>
                                        <a href="#" class="zo-active">درباره دوره</a>
                                    </li>
                                    <li>
                                        <a href="#">محتوای دوره</a>
                                    </li>
                                    <li>
                                        <a href="#">پیش نیازها</a>
                                    </li>
                                    <li>
                                        <a href="#">درباره استاد</a>
                                    </li>
                                    <li>
                                        <a href="#">نظرات کاربران</a>
                                    </li>
                                </ul>
                            </div>
                           <CourseDetailsDescription :description="course.data.description"/>
                            <div>
                                <div class="zo-hr"></div>
                            </div>

                            <CourseDetailsSeasons :seasons="course.data.seasons" :stats="course.data.stats" :isEnrolled="isEnrolled"/>
                        </div>
                        <CourseDetailsRequirements :courses="requirements.data"/>
                        <CourseDetailsRelated :courses="related.data"/>
                        <CourseDetailsComments/>
                    </v-card>
                </v-col>
                <v-col cols="12" lg="3">
                    <CourseDetailsSidebar/>
                </v-col>
            </v-row>
        </v-container>
    </WebLayout>
</template>

<script setup>
import WebLayout from '@/Layouts/WebLayout.vue'
import "swiper/css";
import CourseDetailsSidebar from "@/Components/Web/CourseDetailsSidebar.vue";
import CourseDetailsIntro from "@/Components/Web/CourseDetailsIntro.vue";
import CourseDetailsComments from "@/Components/Web/CourseDetailsComments.vue";
import CourseDetailsRelated from "@/Components/Web/CourseDetailsRelated.vue";
import CourseDetailsRequirements from "@/Components/Web/CourseDetailsRequirements.vue";
import CourseDetailsSeasons from "@/Components/Web/CourseDetailsSeasons.vue";
import CourseDetailsDescription from "@/Components/Web/CourseDetailsDescription.vue";

const props = defineProps({
    course: Object,
    requirements: Object,
    related: Object,
    isEnrolled: Object,
})
const tabs = ["درباره دوره", "محتوای دوره", "پیش نیازها", "درباره استاد", "نظرات کاربران"];


</script>
