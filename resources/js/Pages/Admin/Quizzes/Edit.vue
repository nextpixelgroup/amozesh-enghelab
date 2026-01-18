<template>
    <AdminLayout>
        <v-container fluid class="px-4 px-md-8">
            <v-row>
                <v-col cols="12">
                    <v-card class="rounded-lg overflow-hidden" elevation="3" v-if="quizData?.video.url">
                        <video
                            :poster="quizData?.video?.poster"
                            controls
                            class="w-100 d-block"
                            style="max-height: 500px; width: 100%; object-fit: contain; background: #000;"
                        >
                            <source :src="`${quizData?.video?.url}?v=${quizData?.video?.updatedAtTimestamp}`" type="video/mp4">
                            مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
                        </video>
                    </v-card>
                    <v-card
                        v-else
                        class="rounded-lg d-flex align-center justify-center text-center pa-10"
                        elevation="3"
                        style="height: 300px; background-color: #f5f5f5;"
                    >
                        <div class="pa-5">

                            <!-- آیکون بزرگ ویدیو -->
                            <v-icon
                                icon="mdi-video-off-outline"
                                size="80"
                                color="grey-darken-1"
                                class="mb-4"
                            ></v-icon>

                            <!-- عنوان وضعیت -->
                            <div class="text-h6 font-weight-bold text-grey-darken-2 mb-2">
                                ویدیویی یافت نشد
                            </div>

                            <!-- توضیحات -->
                            <p class="text-body-2 text-medium-emphasis">
                                تا کنون، اقدامی جهت شرکت در آزمون نهایی صورت نگرفته است.
                            </p>

                        </div>
                    </v-card>

                </v-col>
            </v-row>

            <v-row class="mt-4">

                <v-col cols="12" md="8">

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

                    <div v-if="quizzesList && quizzesList.length > 0">
                        <div class="d-flex align-center mb-4">
                            <v-icon color="primary" class="mr-2">mdi-notebook-check-outline</v-icon>
                            <h2 class="text-h6 font-weight-bold text-primary">آزمون‌های دروس</h2>
                        </div>
                        <div v-for="(quizItem, index) in quizzesList" :key="quizItem.id" class="mb-8">

                            <div class="d-flex align-center justify-space-between mb-2">
                                <div class="text-subtitle-1 font-weight-bold text-grey-darken-3">
                                    {{ index + 1 }}. {{ quizItem.title }}
                                </div>

                                <v-chip
                                    class="font-weight-bold"
                                    :color="getQuizColor(quizItem)"
                                    variant="flat"
                                    size="small"
                                >
                                    <v-icon start icon="mdi-check-decagram"></v-icon>
                                    {{ calculateQuizStats(quizItem).correct }} از {{
                                        calculateQuizStats(quizItem).total
                                    }} صحیح
                                </v-chip>
                            </div>

                            <QuizViewer
                                :quiz="quizItem"
                                :title="quizItem.title"
                            />
                        </div>
                    </div>

                    <v-alert v-else type="info" variant="tonal">
                        هیچ آزمون درسی یافت نشد.
                    </v-alert>

                </v-col>

                <v-col cols="12" md="4">
                    <div class="position-sticky" style="top: 20px;">

                        <v-card elevation="2" border class="mb-4">
                            <v-img
                                v-if="courseInfo.image"
                                :src="courseInfo.image"
                                max-height="350"
                                cover
                                class="align-end"
                                gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                            >
                                <v-card-title class="text-white font-weight-bold"
                                              style="text-shadow: 1px 1px 2px black;">
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
                                <v-btn block variant="outlined" color="primary" :href="courseInfo.link" target="_blank"
                                       class="mb-3">
                                    <v-icon start>mdi-open-in-new</v-icon>
                                    مشاهده دوره
                                </v-btn>



                            </v-card-actions>
                        </v-card>
                        <v-card elevation="2" border class="mb-4">
                            <v-card-item class="bg-blue-grey-lighten-5 py-3">
                                <template v-slot:prepend>
                                    <v-icon color="blue-grey-darken-2">mdi-chart-bar</v-icon>
                                </template>
                                <v-card-title class="text-subtitle-1 font-weight-bold text-blue-grey-darken-3">
                                    آمار کلی سوالات دروس
                                </v-card-title>
                            </v-card-item>
                            <v-divider></v-divider>
                            <v-card-text class="pa-4">
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
                            </v-card-text>
                        </v-card>
                        <!-- ========================================== -->
                        <!-- کارت جدید: اطلاعات کاربر (اضافه شده) -->
                        <!-- ========================================== -->
                        <v-card elevation="2" border class="mb-4">
                            <v-card-item class="bg-blue-grey-lighten-5 py-3">
                                <template v-slot:prepend>
                                    <v-icon color="blue-grey-darken-2">mdi-account-details</v-icon>
                                </template>
                                <v-card-title class="text-subtitle-1 font-weight-bold text-blue-grey-darken-3">
                                    مشخصات
                                </v-card-title>
                            </v-card-item>
                            <v-divider></v-divider>
                            <v-card-text class="pa-4">
                                <div class="d-flex align-center mb-4">
                                    <v-avatar color="green-lighten-4" size="48" class="ml-3">
                                        <v-icon size="28" color="green-darken-3">mdi-account</v-icon>
                                    </v-avatar>
                                    <div>
                                        <div class="text-subtitle-1 font-weight-bold text-grey-darken-4">

                                            <a :href="route('admin.users.edit', userInfo.id)" target="_blank">
                                                {{ userInfo.name || 'بدون نام' }}
                                                <v-icon size="16">mdi mdi-open-in-new</v-icon>
                                            </a>
                                        </div>
                                        <div class="text-caption text-grey-darken-1">
                                            شناسه: {{ userInfo.id || '-' }}
                                        </div>
                                    </div>
                                </div>

                                <v-divider class="mb-3 border-dashed"></v-divider>

                                <div class="d-flex align-center mb-2">
                                    <v-icon size="small" color="grey-darken-1" class="ml-2">mdi-phone-outline</v-icon>
                                    <span class="text-body-2 font-weight-medium text-grey-darken-3" dir="ltr">
                                        {{ userInfo.mobile || '-' }}
                                    </span>
                                </div>

                                <div class="d-flex align-center mb-2">
                                    <v-icon size="small" color="grey-darken-1" class="ml-2">mdi-email-outline</v-icon>
                                    <span class="text-body-2 text-grey-darken-3 text-truncate">
                                        {{ userInfo.email || '-' }}
                                    </span>
                                </div>

                                <v-divider class="mb-3 border-dashed"></v-divider>
                                <div class="d-flex align-center mb-2">
                                    <v-icon size="small" color="grey-darken-1" class="ml-2">mdi-badge-account-outline</v-icon>
                                    <strong>درخواست گواهینامه:</strong>
                                    <v-chip
                                        :color="quizData.requestedCertificate ? 'green-lighten-4' : 'red-lighten-4'"
                                        size="small"
                                        :class="`${quizData.requestedCertificate ? 'text-green-darken-3' : 'text-red-darken-3'} font-weight-bold`"
                                    >
                                        <v-icon :color="quizData.requestedCertificate ? 'green' : 'red'">mdi mdi-{{quizData.requestedCertificate ? 'check' : 'close'}}</v-icon>
                                    </v-chip>
                                </div>

                                <v-divider class="mb-3 border-dashed" v-if="quizData.certificate?.number"></v-divider>
                                <div class="d-flex align-center mb-2" v-if="quizData.certificate?.number">
                                    <v-icon size="small" color="grey-darken-1" class="ml-2">mdi-certificate-outline</v-icon>
                                    <strong>شماره گواهی دوره:</strong>
                                    <v-chip
                                        v-if="quizData.certificate?.number"
                                        color="green-lighten-4"
                                        size="small"
                                        class="text-green-darken-3 font-weight-bold"
                                    >
                                        {{ quizData.certificate?.number }}
                                    </v-chip>
                                </div>

                            </v-card-text>
                        </v-card>
                        <!-- ========================================== -->
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
                                    v-if="quizData.video.status !== 'approved'"
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

                        <v-card
                            v-if="historyNotes && historyNotes.length > 0"
                            elevation="2"
                            border
                            class="mt-4"
                        >
                            <v-card-item class="bg-grey-lighten-4 py-3">
                                <template v-slot:prepend>
                                    <v-icon color="grey-darken-2">mdi-history</v-icon>
                                </template>
                                <v-card-title class="text-subtitle-1 font-weight-bold text-grey-darken-3">
                                    تاریخچه یادداشت‌ها
                                </v-card-title>
                            </v-card-item>

                            <v-divider></v-divider>

                            <v-card-text class="pa-4" style="max-height: 400px; overflow-y: auto;">
                                <v-timeline density="compact" side="end" truncate-line="start">

                                    <v-timeline-item
                                        v-for="(note, i) in historyNotes"
                                        :key="i"
                                        dot-color="blue-grey-lighten-2"
                                        size="x-small"
                                        width="100%"
                                    >
                                        <div class="d-flex flex-column w-100">
                                            <div class="d-flex justify-space-between align-center mb-1 w-100">
                                            <span class="text-caption font-weight-bold text-blue-grey-darken-3">
                                                {{ note.user || 'کاربر سیستم' }}
                                            </span>
                                                <span class="text-caption text-grey mr-2">
                                                {{ note.created_at }}
                                            </span>
                                            </div>

                                            <div
                                                class="text-caption text-grey-darken-4 bg-grey-lighten-5 pa-2 rounded border note-text">
                                                {{ note.note }}
                                            </div>
                                        </div>
                                    </v-timeline-item>

                                </v-timeline>

                            </v-card-text>
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
import {ref, computed, reactive} from "vue";
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

// --- اطلاعات کاربر (جدید) ---
const userInfo = computed(() => quizData.value?.user || {});


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
        case 'completed':
            return {
                title: 'تکمیل شده',
                color: 'info',
                icon: 'mdi-check-all',
                bg: 'bg-green-lighten-5'
            };
        case 'failed':
            return {
                title: 'ناموفق',
                color: 'error',
                icon: 'mdi-alert-octagon',
                bg: 'bg-red-lighten-5'
            };

        // --- وضعیت‌های میانی ---
        case 'recording':
            return {
                title: 'در حال ضبط',
                color: 'primary',
                icon: 'mdi-record-rec',
                bg: 'bg-indigo-lighten-5'
            };
        case 'process':
            return {
                title: 'در حال پردازش',
                color: 'info',
                icon: 'mdi-cog-sync',
                bg: 'bg-cyan-lighten-5'
            };
        case 'review':
            return {
                title: 'در حال بررسی',
                color: 'warning',
                icon: 'mdi-eye-settings',
                bg: 'bg-orange-lighten-5'
            };

        case 'pending':
        default:
            return {
                title: 'در انتظار',
                color: 'warning',
                icon: 'mdi-clock',
                bg: 'bg-orange-lighten-5'
            };
    }
});

const getProgressColor = (ratio: number) => {
    if (ratio >= 0.6) return 'success';
    if (ratio >= 0.25) return 'warning';
    return 'error';
};

// ----------------------------------------------------------------------
// منطق محاسبه آمار سوالات
// ----------------------------------------------------------------------
const calculateQuizStats = (quiz: any) => {
    if (!quiz || !quiz.questions) return {total: 0, correct: 0, wrong: 0};

    const questions = quiz.questions;
    const total = questions.length;
    let correct = 0;

    questions.forEach((q: any) => {
        if (q.userSelected) {
            const selectedOption = q.options.find((opt: any) => opt.id === q.userSelected);
            if (selectedOption && selectedOption.is_correct) {
                correct++;
            }
        }
    });

    const wrong = total - correct;
    return {total, correct, wrong};
};

const getQuizColor = (quiz: any) => {
    const stats = calculateQuizStats(quiz);
    if (stats.total === 0) return 'grey';
    const percent = stats.correct / stats.total;
    if (percent >= 0.7) return 'success';
    if (percent >= 0.4) return 'warning';
    return 'error';
};

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

    return {total, correct, wrong};
});
// ----------------------------------------------------------------------


// --- فرم ادمین ---
const statusOptions = [
    {title: 'در حال بررسی', value: 'review', icon: 'mdi-clock-outline', color: 'warning'},
    {title: 'تایید شده', value: 'approved', icon: 'mdi-check-circle-outline', color: 'success'},
    {title: 'رد شده', value: 'rejected', icon: 'mdi-close-circle-outline', color: 'error'}
];

const historyNotes = computed(() => {
    return quizData.value?.video?.notes || [];
});

const adminForm = useForm({
    status: '',
    note: ''
});

const isSaving = ref(false);

const save = () => {
    adminForm.put(route('admin.quizzes.update', {video: quizData.value.id}), {
        preserveState: true,
        preserveScroll: true,

        onStart: () => {
            isSaving.value = true;
        },
        onSuccess: (response) => {
            quizData.value = response.props.quiz.data;
            isSaving.value = false;
            adminForm.reset();
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
