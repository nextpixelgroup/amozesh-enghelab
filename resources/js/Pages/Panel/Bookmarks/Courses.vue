<template>
    <WebLayout>
        <PanelLayout>
            <div class="zo-dashboard-section">
                <div class="zo-courses-section">
                    <v-container>
                        <div class="zo-header mb-3">
                            <v-row class="align-center">
                                <v-col cols="12">
                                    <div class="zo-info d-lg-flex d-sm-none">
                                        <div class="zo-icon elevation-4">
                                            <i class="mdi mdi-bookmark"></i>
                                        </div>
                                        <div class="zo-name">
                                            <strong class="d-block mb-1">علاقه‌مندی‌ها</strong>
                                            <span>در این بخش می توانید علاقه‌‌مندی‌های خود را مشاهده کنید.</span>
                                        </div>
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                        <v-row dense class="align-center">
                            <v-col v-for="(course, index) in courses.data" :key="index" cols="12" sm="6" md="4" lg="3">
                                <CourseCard :course="course" />
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </div>
        </PanelLayout>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";
defineProps({
    courses : {
        type: Object,
        default: []
    }
})
</script>
