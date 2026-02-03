<template>
    <WebLayout>
        <!-- has EnrollPath -->
        <v-dialog
            v-model="showEnrollPathDialog"
            max-width="500"
            persistent
            :overlay="false"
            transition="fade-transition"
        >
            <v-card class="elevation-12" style="border-radius: 16px; overflow: hidden; background: #f8f9fa;">
                <!-- هدر با گرادیان primary به secondary -->
                <v-card-title class="pa-4" style="background: #f44337; color: white; border-radius: 16px 16px 0 0;">
                    <v-icon color="white" class="mr-2">mdi-alert</v-icon>
                    هشدار
                </v-card-title>

                <!-- متن -->
                <v-card-text class="pa-6 text-center">
                    <v-icon color="red" size="64" class="mb-4" style="animation: pulse 2s infinite;">
                        mdi-information-outline
                    </v-icon>
                    <div class="font-weight-bold">برای گذراندن این درس، لطفاً از طریق سیر مطالعاتی اقدام نمایید.</div>
                    <div class="mt-2 text-grey">لطفاً روی دکمهٔ رفتن به مسیر کلیک کنید.</div>
                </v-card-text>

                <!-- اکشن‌ها -->
                <v-card-actions class="pa-4" style="border-top: 1px solid #eee;">
                    <v-btn
                        variant="outlined"
                        @click="showEnrollPathDialog = false"
                        color="red"
                        prepend-icon="mdi mdi-close"
                    >
                        لغو
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        large
                        elevation="1"
                        class="px-6"
                        variant="flat"
                        append-icon="mdi mdi-chevron-left"
                        :href="route('web.path.index')"
                    >
                        رفتن به مسیر
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- has Requested Certificate -->
        <v-dialog
            v-model="showRequestedCertificateDialog"
            max-width="500"
            persistent
            :overlay="false"
            transition="fade-transition"
        >
            <v-card class="elevation-12" style="border-radius: 16px; overflow: hidden; background: #f8f9fa;">
                <!-- هدر با گرادیان primary به secondary -->
                <v-card-title class="pa-4" style="background: #c8a064; color: white; border-radius: 16px 16px 0 0;">
                    <v-icon color="white" class="mr-2">mdi-certificate</v-icon>
                    درخواست گواهینامه
                </v-card-title>

                <!-- متن -->
                <v-card-text class="pa-6 text-center">
                    <v-icon color="#c8a064" size="64" class="mb-4" style="animation: pulse 2s infinite;">
                        mdi-information-outline
                    </v-icon>
                    <div class="font-weight-bold">آیا می‌خواهید گواهینامه دوره را دریافت کنید؟</div>
                    <div class="mt-2 text-grey">این عملیات قابل برگشت نیست.</div>
                </v-card-text>

                <!-- اکشن‌ها -->
                <v-card-actions class="pa-4" style="border-top: 1px solid #eee;">
                    <v-btn
                        variant="outlined"
                        @click="handleCertificate(false)"
                        color="gray"
                        prepend-icon="mdi mdi-close"
                        :loading="isIgnoreCertificate"
                        :disabled="isCertificate"
                    >
                        خیر، نمی‌خواهم
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        @click="handleCertificate(true)"
                        color="primary"
                        large
                        elevation="1"
                        class="px-6"
                        variant="flat"
                        prepend-icon="mdi mdi-check"
                        :loading="isAcceptCertificate"
                        :disabled="isCertificate"
                    >
                        بله، می‌خواهم
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

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
                    <CourseDetailsSidebar
                        :user="user"
                        :course="course.data"
                        :isEnrolled="isEnrolled"
                        :enrollPath="enrollPath"
                        v-model:showEnrollPathDialog="showEnrollPathDialog"
                    />
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
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const showCompletionDialog = ref(false);
const activeSection = ref(''); // مثلاً 'lessons' یا 'requirements'

const props = defineProps({
    course: Object,
    requirements: Object,
    related: Object,
    isEnrolled: Object,
    enrollPath: [Boolean, null],
    user: Object,
})
const showRequestedCertificateDialog = ref(props.course.data.quiz.hasQuiz && props.isEnrolled && props.course.data.hasRequestedCertificate === null)
const showEnrollPathDialog = ref(props.enrollPath === false)
// Watch for course data changes
watch(() => props.course, (newVal) => {
    if (newVal?.data?.hasCompletedCourse && newVal?.data?.quiz.completed === false) {
        showCompletionDialog.value = true;
    }
}, { immediate: true, deep: true });

watch(() => props.isEnrolled, (isEnrolled) => {
    showRequestedCertificateDialog.value = isEnrolled && props.course.data.hasRequestedCertificate === null;
    console.log(showRequestedCertificateDialog.value)
});

const isIgnoreCertificate = ref(false);
const isAcceptCertificate = ref(false);
const isCertificate = ref(false);
const handleCertificate = (response) => {


    router.post(route('web.courses.certificate.store', props.course.data.id), {response: response}, {
        onStart: () => {
            isCertificate.value = true
            if (response === true) {
                isAcceptCertificate.value = true;
            }
            else{
                isIgnoreCertificate.value = true;
            }
        },
        onSuccess: () => {
            showRequestedCertificateDialog.value = false;
        },
        onFinish: () => {
            showRequestedCertificateDialog.value = false;
        },
        onError: () => {
            showRequestedCertificateDialog.value = false;
        }
    });
}

</script>
<style>
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
    100% { transform: scale(1); opacity: 1; }
}
</style>
