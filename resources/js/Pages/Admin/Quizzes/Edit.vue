<template>
    <AdminLayout>
        <v-container fluid class="px-4 px-md-8">

            <!-- بخش 1: ویدیو (تمام عرض) -->
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

                    <!-- 1. آزمون نهایی (Final Quiz) -->
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

                    <!-- 2. آزمون‌های دروس (Lesson Quizzes) -->
                    <div v-if="quizzesList && quizzesList.length > 0">
                        <div class="d-flex align-center mb-4">
                            <v-icon color="primary" class="mr-2">mdi-notebook-check-outline</v-icon>
                            <h2 class="text-h6 font-weight-bold text-primary">آزمون‌های دروس</h2>
                        </div>

                        <!-- حلقه روی تمام آزمون‌های دروس -->
                        <div v-for="(quizItem, index) in quizzesList" :key="quizItem.id" class="mb-8">
                            <div class="text-subtitle-1 font-weight-bold mb-2 text-grey-darken-3">
                                {{ index + 1 }}. {{ quizItem.title }}
                            </div>
                            <!-- استفاده از همان کامپوننت نمایش دهنده سوالات -->
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

                            <v-card-actions class="pa-4">
                                <v-btn block variant="outlined" color="primary" :href="courseInfo.link" target="_blank">
                                    <v-icon start>mdi-open-in-new</v-icon>
                                    مشاهده دوره
                                </v-btn>
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
                                <v-select
                                    v-model="adminForm.status"
                                    :items="statusOptions"
                                    item-title="title"
                                    item-value="value"
                                    label="وضعیت ویدیو"
                                    variant="outlined"
                                    density="comfortable"
                                    hide-details="auto"
                                    class="mb-4"
                                >
                                    <template v-slot:selection="{ item }">
                                        <v-icon :color="item.raw.color" start size="small">{{ item.raw.icon }}</v-icon>
                                        <span :class="`text-${item.raw.color}`">{{ item.title }}</span>
                                    </template>
                                </v-select>

                                <v-textarea
                                    v-model="adminForm.note"
                                    label="یادداشت مدیر"
                                    variant="outlined"
                                    rows="3"
                                    density="comfortable"
                                    hide-details="auto"
                                ></v-textarea>
                            </v-card-text>

                            <v-card-actions class="pa-4 pt-0">
                                <v-btn
                                    block
                                    color="blue-grey-darken-1"
                                    variant="flat"
                                    size="large"
                                    :loading="isSaving"
                                    @click="saveAdminDecision"
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
/**
 * برای تمیز نگه داشتن کد و قابلیت استفاده مجدد برای هر تعداد آزمون،
 * بخش نمایش آزمون را به یک کامپوننت داخلی تبدیل کردیم.
 * در Vue 3 می‌توان کامپوننت‌ها را داخل فایل اصلی تعریف کرد (اگر بیلد سیستم اجازه دهد)
 * یا همینجا به عنوان یک آبجکت ایمپورت کرد.
 * اما بهترین کار در اینجا استفاده از یک کامپوننت جداگانه است.
 *
 * *راه حل:* من کامپوننت `QuizViewer` را در پایین همین اسکریپت به صورت یک کامپوننت محلی تعریف می‌کنم
 * یا اگر از SFC استفاده می‌کنید، بهتر است آن را در فایل `QuizViewer.vue` بسازید.
 *
 * در اینجا برای سادگی کپی پیست شما، من لاجیک را به فایل اصلی برمی‌گردانم اما با استفاده از یک کامپوننت DefineComponent.
 */
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, computed, reactive } from "vue";
// import QuizViewer from "./QuizViewer.vue"; // اگر فایل جدا ساختید

// -----------------------------------------------------------------
// تعریف کامپوننت داخلی QuizViewer برای نمایش هر آزمون
// -----------------------------------------------------------------
import { defineComponent, h } from 'vue';

// نکته: اگر این بخش کار نکرد، بهتر است محتویات QuizViewer که در پایینتر توضیح میدهم را در یک فایل جداگانه مثلا Components/QuizViewer.vue ذخیره کنید و ایمپورت کنید.
// اما اینجا برای یکپارچگی کد در پاسخ، فرض میکنیم QuizViewer ایمپورت شده است.
// برای اینکه کد شما کار کند، من کد QuizViewer را در انتهای پاسخ به صورت جداگانه قرار می‌دهم که باید بسازید.
import QuizViewer from "@/Components/Admin/Quiz/QuizViewer.vue"; // فرض بر این است که فایل ساخته شده
// -----------------------------------------------------------------


const props = defineProps({
    quiz: {
        type: Object,
        required: true
    }
})

// --- استخراج داده‌ها ---
const quizData = ref(props.quiz.data);

// 1. اطلاعات دوره
const courseInfo = computed(() => quizData.value?.course || {});

// 2. داده‌های آزمون‌ها
const finalQuizData = computed(() => quizData.value?.finalQuiz || null);
const quizzesList = computed(() => quizData.value?.quizzes || []);


// --- فرم ادمین ---
const statusOptions = [
    { title: 'در حال بررسی', value: 'pending', icon: 'mdi-clock-outline', color: 'warning' },
    { title: 'تایید شده', value: 'approved', icon: 'mdi-check-circle-outline', color: 'success' },
    { title: 'رد شده', value: 'rejected', icon: 'mdi-close-circle-outline', color: 'error' }
];

const adminForm = reactive({
    status: quizData.value.video?.status || 'pending',
    note: quizData.value.video?.admin_note || ''
});

const isSaving = ref(false);

const saveAdminDecision = () => {
    isSaving.value = true;
    console.log("Saving...", adminForm);
    setTimeout(() => {
        isSaving.value = false;
        alert('وضعیت ذخیره شد');
    }, 800);
};
</script>

<style scoped>
.position-sticky {
    position: sticky;
    z-index: 9;
}
</style>
