<template>
    <AdminLayout>
        <v-container fluid class="px-4 px-md-8">

            <!-- بخش 1: ویدیو -->
            <v-row>
                <v-col cols="12">
                    <v-card class="rounded-lg overflow-hidden" elevation="3">
                        <video
                            :poster="quizData?.video?.poster"
                            controls
                            class="w-100 d-block"
                            style="max-height: 500px; width: 100%; object-fit: contain; background: #000;"
                        >
                            <source :src="quizData?.video?.url" type="video/mp4">
                            مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
                        </video>
                    </v-card>
                </v-col>
            </v-row>

            <!-- بخش 2: محتوا اصلی -->
            <v-row class="mt-4">

                <!-- ستون چپ: سوالات (8 از 12) -->
                <v-col cols="12" md="8">

                    <!-- 1. آزمون نهایی -->
                    <div class="mb-6">
                        <div class="d-flex align-center mb-2">
                            <v-icon color="primary" class="mr-2">mdi-school</v-icon>
                            <h2 class="text-h6 font-weight-bold text-primary">آزمون نهایی ویدیو</h2>
                        </div>

                        <QuizViewer
                            v-if="finalQuizData && finalQuizData.questions?.length"
                            :quiz="finalQuizData"
                            :title="'آزمون نهایی'"
                        />
                        <v-alert v-else type="info" variant="tonal" class="mb-4">
                            هیچ سوال نهایی برای این ویدیو ثبت نشده است.
                        </v-alert>
                    </div>

                    <v-divider class="my-6"></v-divider>

                    <!-- 2. آزمون‌های دروس -->
                    <div v-if="quizzesList && quizzesList.length > 0">
                        <div class="d-flex align-center mb-4">
                            <v-icon color="primary" class="mr-2">mdi-notebook-check-outline</v-icon>
                            <h2 class="text-h6 font-weight-bold text-primary">آزمون‌های دروس</h2>
                        </div>

                        <!-- حلقه روی تمام آزمون‌های دروس -->
                        <div v-for="(quizItem, index) in quizzesList" :key="quizItem.id" class="mb-8">

                            <!-- هدر هر آزمون شامل عنوان و نتیجه -->
                            <div class="d-flex align-center justify-space-between mb-2">
                                <div class="text-subtitle-1 font-weight-bold text-grey-darken-3">
                                    {{ index + 1 }}. {{ quizItem.title }}
                                </div>

                                <!-- نمایش نتیجه تکی برای این آزمون -->
                                <v-chip
                                    class="font-weight-bold"
                                    :color="getQuizColor(quizItem)"
                                    variant="flat"
                                    size="small"
                                >
                                    <v-icon start icon="mdi-check-decagram"></v-icon>
                                    {{ calculateQuizStats(quizItem).correct }} از {{ calculateQuizStats(quizItem).total }} صحیح
                                </v-chip>
                            </div>

                            <QuizViewer
                                :quiz="quizItem"
                                :title="quizItem.title"
                            />
                        </div>
                    </div>

                    <v-alert v-else type="info" variant="tonal">
                        هیچ آزمون درسی مرتبطی یافت نشد.
                    </v-alert>

                </v-col>

                <!-- ستون راست: سایدبار (4 از 12) -->
                <v-col cols="12" md="4">
                    <div class="position-sticky" style="top: 20px;">

                        <!-- کارت اطلاعات دوره -->
                        <v-card elevation="2" border class="mb-4">
                            <v-img
                                v-if="courseInfo.image"
                                :src="courseInfo.image"
                                height="180"
                                cover
                                class="align-end"
                                gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                            >
                                <v-card-title class="text-white font-weight-bold" style="text-shadow: 1px 1px 2px black;">
                                    {{ courseInfo.title }}
                                </v-card-title>
                            </v-img>
                            <v-card-item v-else class="bg-primary-lighten-5 py-6 text-center">
                                <v-icon size="64" color="primary-lighten-2">mdi-school-outline</v-icon>
                                <v-card-title class="mt-2 text-primary-darken-2 font-weight-bold">
                                    {{ courseInfo.title }}
                                </v-card-title>
                            </v-card-item>

                            <v-divider></v-divider>

                            <v-card-actions class="pa-4 d-flex flex-column gap-2">
                                <v-btn block variant="outlined" color="primary" :href="courseInfo.link" target="_blank" class="mb-3">
                                    <v-icon start>mdi-open-in-new</v-icon>
                                    مشاهده دوره
                                </v-btn>

                                <!-- >>> بخش جدید: آمار کلی سوالات دروس <<< -->
                                <v-card variant="tonal" color="grey-lighten-2" class="w-100 pa-3 rounded-lg border">
                                    <div class="d-flex align-center mb-3">
                                        <v-icon color="grey-darken-3" class="mr-2">mdi-chart-bar</v-icon>
                                        <span class="font-weight-bold text-grey-darken-3">آمار کلی سوالات دروس</span>
                                    </div>

                                    <div class="d-flex justify-space-between align-center mb-2">
                                        <span class="text-body-2 text-grey-darken-2">کل سوالات:</span>
                                        <v-chip size="x-small" color="back" variant="flat" class="font-weight-bold">
                                            {{ overallStats.total }}
                                        </v-chip>
                                    </div>

                                    <div class="d-flex justify-space-between align-center mb-2">
                                        <span class="text-body-2 text-grey-darken-2">پاسخ‌های صحیح:</span>
                                        <v-chip size="x-small" color="success" variant="flat" class="font-weight-bold">
                                            {{ overallStats.correct }}
                                        </v-chip>
                                    </div>

                                    <div class="d-flex justify-space-between align-center">
                                        <span class="text-body-2 text-grey-darken-2">پاسخ‌های غلط:</span>
                                        <v-chip size="x-small" color="error" variant="flat" class="font-weight-bold">
                                            {{ overallStats.wrong }}
                                        </v-chip>
                                    </div>

                                    <!-- نمایش درصد موفقیت (اختیاری) -->
                                    <v-progress-linear
                                        v-if="overallStats.total > 0"
                                        :model-value="(overallStats.correct / overallStats.total) * 100"
                                        :color="getProgressColor(overallStats.correct / overallStats.total)"
                                        height="6"
                                        rounded
                                        class="mt-3"
                                    ></v-progress-linear>
                                </v-card>
                                <!-- >>> پایان بخش آمار <<< -->

                            </v-card-actions>
                        </v-card>

                        <!-- پنل ادمین -->
                        <v-card elevation="2" border>
                            <v-card-item class="bg-blue-grey-lighten-5 py-3">
                                <template v-slot:prepend>
                                    <v-icon color="blue-grey-darken-2">mdi-shield-account</v-icon>
                                </template>
                                <v-card-title class="text-subtitle-1 font-weight-bold text-blue-grey-darken-3">
                                    اقدام مدیریتی
                                </v-card-title>
                            </v-card-item>

                            <v-divider></v-divider>

                            <v-card-text class="pa-4">

                                <!-- نمایش وضعیت فعلی (ذخیره شده در دیتابیس) -->
                                <div class="d-flex align-center justify-space-between pa-3 rounded-lg border mb-4"
                                     :class="currentStatusInfo.bg"
                                     style="border-style: dashed !important;">
            <span class="text-body-2 font-weight-bold text-grey-darken-3">
                وضعیت فعلی:
            </span>
                                    <v-chip
                                        :color="currentStatusInfo.color"
                                        variant="elevated"
                                        elevation="1"
                                        size="small"
                                        class="font-weight-bold px-3"
                                    >
                                        <v-icon start size="x-small">{{ currentStatusInfo.icon }}</v-icon>
                                        {{ currentStatusInfo.title }}
                                    </v-chip>
                                </div>
                                <!-- فرم تغییر وضعیت -->
                                <v-select
                                    v-model="adminForm.status"
                                    :items="statusOptions"
                                    item-title="title"
                                    item-value="value"
                                    label="انتخاب وضعیت جدید"
                                    variant="outlined"
                                    density="comfortable"
                                    hide-details="auto"
                                    class="mb-4"
                                    clearable
                                />

                                <v-textarea
                                    v-model="adminForm.note"
                                    label="یادداشت مدیر"
                                    variant="outlined"
                                    rows="3"
                                    density="comfortable"
                                    hide-details="auto"
                                    placeholder="دلیل تایید یا رد را بنویسید..."
                                ></v-textarea>
                            </v-card-text>

                            <v-card-actions class="pa-4 pt-0">
                                <v-btn
                                    block
                                    color="primary"
                                    variant="flat"
                                    size="large"
                                    :loading="isSaving"
                                    @click="save"
                                >
                                    ذخیره تغییرات
                                </v-btn>
                            </v-card-actions>
                        </v-card>

                    </div>
                </v-col>

            </v-row>
        </v-container>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from "@/Layouts/AdminLayout.vue";
import QuizViewer from "@/Components/Admin/Quiz/QuizViewer.vue";
import { ref, computed, reactive } from "vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const props = defineProps({
    quiz: {
        type: Object,
        required: true
    }
})

// --- استخراج داده‌ها ---
const quizData = ref(props.quiz.data);
const courseInfo = computed(() => quizData.value?.course || {});
const finalQuizData = computed(() => quizData.value?.finalQuiz || null);
const quizzesList = computed(() => quizData.value?.quizzes || []);

const currentStatusInfo = computed(() => {
    // اگر video.status وجود نداشته باشد، وضعیت پیش‌فرض 'pending' در نظر گرفته می‌شود.
    const status = quizData.value?.video?.status || 'pending';

    switch (status) {
        // --- وضعیت‌های نهایی ---
        case 'approved':
            return {
                title: 'تایید شده',
                color: 'success',
                icon: 'mdi-check-circle',
                bg: 'bg-green-lighten-5'
            };
        case 'rejected':
            return {
                title: 'رد شده',
                color: 'error',
                icon: 'mdi-close-circle',
                bg: 'bg-red-lighten-5'
            };
        case 'completed': // وضعیت جدید: تکمیل موفقیت‌آمیز فرآیند (مانند پردازش)
            return {
                title: 'تکمیل شده',
                color: 'info',
                icon: 'mdi-check-all',
                bg: 'bg-green-lighten-5'
            };
        case 'failed': // وضعیت جدید: ناموفق در فرآیند (مانند پردازش ویدیو)
            return {
                title: 'ناموفق',
                color: 'error',
                icon: 'mdi-alert-octagon',
                bg: 'bg-red-lighten-5'
            };

        // --- وضعیت‌های میانی و در حال انتظار ---
        case 'recording': // وضعیت جدید: در حال ضبط
            return {
                title: 'در حال ضبط',
                color: 'primary',
                icon: 'mdi-record-rec',
                bg: 'bg-indigo-lighten-5'
            };
        case 'pending_process': // وضعیت جدید: در حال پردازش (مانند انکدینگ ویدیو)
            return {
                title: 'در حال پردازش',
                color: 'info',
                icon: 'mdi-cog-sync',
                bg: 'bg-cyan-lighten-5'
            };
        case 'review': // وضعیت جدید: در حال بررسی (بازبینی انسانی یا فنی)
            return {
                title: 'در حال بررسی',
                color: 'warning',
                icon: 'mdi-eye-settings',
                bg: 'bg-orange-lighten-5'
            };

        case 'pending': // وضعیت پیشین: در انتظار اقدام بعدی یا شروع فرآیند
        default:
            return {
                title: 'در انتظار', // عنوان 'در حال بررسی' به 'در انتظار' تغییر داده شد
                color: 'warning',
                icon: 'mdi-clock',
                bg: 'bg-orange-lighten-5'
            };
    }
});
const getProgressColor = (ratio: number) => {
    if (ratio >= 0.6) return 'success';  // Green for 50% or more
    if (ratio >= 0.25) return 'warning';    // Blue for 25% to 49.99%
    return 'error';                    // Yellow for less than 25%
};
// ----------------------------------------------------------------------
// منطق محاسبه آمار سوالات
// ----------------------------------------------------------------------

/**
 * محاسبه آمار برای یک آزمون خاص
 * @param quiz آبجکت آزمون شامل آرایه questions
 */
const calculateQuizStats = (quiz: any) => {
    if (!quiz || !quiz.questions) return { total: 0, correct: 0, wrong: 0 };

    const questions = quiz.questions;
    const total = questions.length;
    let correct = 0;

    questions.forEach((q: any) => {
        // پیدا کردن گزینه انتخاب شده کاربر
        // فرض بر این است که q.userSelected شناسه (ID) گزینه انتخاب شده است
        // و options آرایه‌ای از گزینه‌هاست که فیلد is_correct دارد
        if (q.userSelected) {
            const selectedOption = q.options.find((opt: any) => opt.id === q.userSelected);
            if (selectedOption && selectedOption.is_correct) {
                correct++;
            }
        }
    });

    const wrong = total - correct; // شامل نزده‌ها هم به عنوان غلط حساب می‌شود (یا می‌توانید نزده را جدا کنید)
    return { total, correct, wrong };
};

/**
 * تعیین رنگ بج (Chip) بر اساس عملکرد
 */
const getQuizColor = (quiz: any) => {
    const stats = calculateQuizStats(quiz);
    if (stats.total === 0) return 'grey';
    const percent = stats.correct / stats.total;
    if (percent >= 0.7) return 'success'; // عالی
    if (percent >= 0.4) return 'warning'; // متوسط
    return 'error'; // ضعیف
};

/**
 * محاسبه آمار کلی تمام آزمون‌های لیست (quizzesList)
 */
const overallStats = computed(() => {
    let total = 0;
    let correct = 0;
    let wrong = 0;

    if (quizzesList.value) {
        quizzesList.value.forEach((quiz: any) => {
            const stats = calculateQuizStats(quiz);
            total += stats.total;
            correct += stats.correct;
            wrong += stats.wrong;
        });
    }

    return { total, correct, wrong };
});
// ----------------------------------------------------------------------


// --- فرم ادمین ---
const statusOptions = [
    { title: 'در حال بررسی', value: 'pending', icon: 'mdi-clock-outline', color: 'warning' },
    { title: 'تایید شده', value: 'approved', icon: 'mdi-check-circle-outline', color: 'success' },
    { title: 'رد شده', value: 'rejected', icon: 'mdi-close-circle-outline', color: 'error' }
];

const adminForm = useForm({
    status: '',
    note: quizData.value.video?.admin_note || ''
});

const isSaving = ref(false);

const save = () => {
    adminForm.put(route('admin.quizzes.update',{video: quizData.value.id}), {
        preserveState: true,
        preserveScroll: true,
        onStart: () => {
            isSaving.value = true;
        },
        onSuccess: () => {
            isSaving.value = false;
        },
        onError: () => {
            isSaving.value = false;
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });

};
</script>

<style scoped>
.position-sticky {
    position: sticky;
    z-index: 9;
}
</style>
