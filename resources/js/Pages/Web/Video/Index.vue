<template>
    <VideoLayout>
        <v-container class="fill-height align-start justify-center pa-0 pa-md-4 persian-wrapper" fluid>

            <v-row justify="center" class="w-100 ma-0">
                <v-col cols="12" md="10" lg="8" class="pa-0">

                    <!--
                        تغییر ۱: اصلاح کلاس‌های کارت
                        - h-100 و overflow-hidden فقط برای دسکتاپ (mdAndUp) فعال است.
                        - در موبایل overflow-visible است تا اسکرول کار کند.
                    -->
                    <v-card
                        class="main-card d-flex flex-column"
                        :class="$vuetify.display.mdAndUp ? 'rounded-xl elevation-10 h-100 overflow-hidden' : 'rounded-0 elevation-0 bg-background'"
                    >

                        <!-- هدر وضعیت -->
                        <div v-if="(!isRecording || $vuetify.display.mdAndUp) && state !== 'completed'" class="px-4 py-3 d-flex align-center justify-space-between bg-grey-lighten-5 border-b">
                            <div class="d-flex align-center">
                                <v-avatar color="primary" variant="tonal" size="36" class="me-3">
                                    <v-icon icon="mdi-video-outline" size="20" color="primary"></v-icon>
                                </v-avatar>
                                <div>
                                    <h2 class="text-subtitle-1 font-weight-bold text-grey-darken-3">
                                        {{ isAllowedToRecord ? 'اتاق آزمون' : 'وضعیت' }}
                                    </h2>
                                </div>
                            </div>

                            <!-- تایمر -->
                            <div v-if="isAllowedToRecord" class="timer-badge px-3 py-1 rounded-pill" :class="isRecording ? 'recording-active' : 'bg-grey-lighten-3'">
                                <span class="text-h6 font-weight-bold font-monospace" :class="isRecording ? 'text-red' : 'text-grey-darken-2'">
                                    {{ formatTime(timer) }}
                                </span>
                                <v-icon v-if="isRecording" icon="mdi-record" color="red" size="x-small" class="ms-2 blink-animation"></v-icon>
                            </div>
                        </div>

                        <!-- بدنه اصلی -->
                        <v-card-text class="pa-0 position-relative d-flex flex-column flex-grow-1">

                            <!-- بخش ضبط -->
                            <div v-if="isAllowedToRecord && state !== 'completed' && state !== 'processing' && state !== 'uploading'" class="video-section d-flex flex-column flex-grow-1">

                                <!-- باکس ویدیو (Sticky در موبایل) -->
                                <div class="video-wrapper-container" :class="{ 'sticky-mobile': $vuetify.display.smAndDown && isRecording }">
                                    <div class="video-wrapper bg-black d-flex align-center justify-center">
                                        <video ref="videoPreview" autoplay playsinline muted class="video-element" :class="{ 'is-mirror': true }"></video>
                                        <!-- جایگزین بخش video-placeholder قبلی شود -->
                                        <div v-if="!stream" class="video-placeholder d-flex flex-column align-center justify-center text-white" style="position: absolute; inset: 0; background: rgba(0,0,0,0.85); z-index: 10;">

                                            <!-- حالت ۱: اگر ارور دسترسی داریم -->
                                            <div v-if="permissionError" class="text-center px-4">
                                                <v-icon size="40" color="red-accent-2" class="mb-2">mdi-camera-off</v-icon>
                                                <p class="text-body-2 font-weight-bold mb-2 text-white">
                                                    دسترسی به دوربین داده نشد یا خطایی رخ داد.
                                                </p>
                                                <v-btn color="white" variant="outlined" rounded="pill" @click="initializeCamera">
                                                    تلاش مجدد
                                                </v-btn>
                                            </div>

                                            <!-- حالت ۲: حالت عادی (دکمه فعال‌سازی دستی برای کروم/فایرفاکس) -->
                                            <div v-else class="text-center px-4">
                                                <div class="pulse-ring mb-2">
                                                    <v-icon size="40" color="white">mdi-camera-iris</v-icon>
                                                </div>
                                                <h3 class="text-h6 font-weight-bold mb-2">راه‌اندازی دوربین</h3>
                                                <p class="text-caption text-grey-lighten-1 mb-2">
                                                    جهت شروع، دسترسی دوربین را فعال کنید
                                                </p>
                                                <!-- این دکمه "User Gesture" لازم برای کروم و فایرفاکس را فراهم می‌کند -->
                                                <v-btn
                                                    color="primary"
                                                    rounded="pill"
                                                    prepend-icon="mdi-camera"
                                                    @click="initializeCamera"
                                                >
                                                    فعال‌سازی دوربین
                                                </v-btn>
                                            </div>
                                        </div>

                                        <div v-if="isRecording" class="rec-indicator"><span class="dot"></span><span class="text-caption font-weight-bold">ضبط</span></div>
                                    </div>
                                    <div v-if="$vuetify.display.smAndDown && isRecording" class="mobile-overlay-timer">{{ formatTime(timer) }}</div>
                                </div>

                                <!-- سوالات -->
                                <div v-if="isRecording && currentQuestion" class="questions-container pa-4 flex-grow-1 bg-surface">
                                    <div class="d-flex align-center justify-space-between mb-2">
                                        <span class="text-subtitle-2 font-weight-bold text-primary">سوال {{ currentQuestionIndex + 1 }}</span>
                                        <v-chip size="x-small" variant="flat" color="green-lighten-5" class="text-primary font-weight-bold">{{ currentQuestionIndex + 1 }} از {{ quiz.questions.length }}</v-chip>
                                    </div>
                                    <div class="d-flex align-center mb-4 text-grey-darken-1">
                                        <v-icon icon="mdi-account-voice" size="small" class="me-2" color="secondary"></v-icon>
                                        <span class="text-caption font-weight-bold">لطفاً سؤال را به همراه پاسخ صحیح، رو به دوربین مطرح کرده و توضیحات لازم را ارائه دهید.</span>
                                    </div>
                                    <div class="question-box mb-6"><h3 class="text-h6 font-weight-bold text-grey-darken-4 leading-relaxed text-right">{{ currentQuestion.question }}</h3></div>

                                    <!-- گزینه‌ها -->
                                    <div class="options-read-only d-flex flex-column gap-3">
                                        <div v-for="(option, idx) in currentQuestion.options" :key="option.id" class="option-item d-flex align-start pa-3 rounded-lg border border-dashed bg-grey-lighten-5">
                                            <div class="option-bullet me-3 mt-1 d-flex align-center justify-center rounded-circle bg-white border text-grey-darken-2 font-weight-bold text-caption" style="width: 24px; height: 24px; flex-shrink: 0;">{{ idx + 1 }}</div>
                                            <span class="text-body-1 text-grey-darken-3 font-weight-medium">{{ option.option }}</span>
                                        </div>
                                    </div>

                                    <!--
                                        تغییر ۲: فضای خالی برای جلوگیری از افتادن محتوا زیر دکمه‌های ثابت
                                        این فضا فقط در موبایل (smAndDown) دیده می‌شود.
                                    -->
                                    <div class="mobile-spacer d-md-none" style="height: 160px; width: 100%;"></div>
                                </div>

                                <!-- کنترل‌های شروع (بدون تغییر) -->
                                <div v-if="!isRecording" class="controls-bar py-8 px-4 bg-surface text-center d-flex flex-column align-center justify-center flex-grow-1">
                                    <v-btn size="x-large" color="primary" rounded="pill" class="px-8 font-weight-bold record-btn shadow-lg mb-6" @click="startRecording" :loading="isLoading" :disabled="!canRecord" height="64" block max-width="300">
                                        <v-icon start size="large">mdi-record-circle-outline</v-icon>شروع آزمون
                                    </v-btn>
                                    <v-card class="mb-6 rounded-xl elevation-0 border w-100" color="#f8f9fa" v-if="!isRecording">
                                        <v-row no-gutters class="py-6">
                                            <v-col cols="6" class="d-flex flex-column align-center justify-center position-relative">
                                                <div class="dashed-divider"></div>
                                                <v-avatar color="#283593" size="64" class="mb-3 elevation-3"><v-icon icon="mdi-cellphone-lock" size="32" color="white"></v-icon></v-avatar>
                                                <span class="text-caption text-grey-darken-1 mb-1 font-weight-medium">وضعیت دستگاه</span>
                                                <span class="text-body-1 font-weight-black text-indigo-darken-3">عمودی / ثابت</span>
                                            </v-col>
                                            <v-col cols="6" class="d-flex flex-column align-center justify-center">
                                                <v-avatar color="#e64a19" size="64" class="mb-3 elevation-3"><v-icon icon="mdi-clock-time-four-outline" size="32" color="white"></v-icon></v-avatar>
                                                <span class="text-caption text-grey-darken-1 mb-1 font-weight-medium">زمان پاسخگویی</span>
                                                <span class="text-body-1 font-weight-black text-deep-orange-darken-3">محدود است</span>
                                            </v-col>
                                        </v-row>
                                    </v-card>php artisan queue:work & php artisan schedule:work & npm run dev & php artisan horizon & php artisan config:clear & php artisan cache:clear & php artisan octane:start
                                </div>
                            </div>

                            <!-- (سایر بخش‌های Loading و Completed بدون تغییر باقی می‌مانند) -->
                            <!-- ... -->
                            <div v-else-if="state === 'uploading' || state === 'processing'" class="d-flex flex-column align-center justify-center py-16 px-4 bg-surface h-100 text-center">
                                <!-- انیمیشن پردازش (آبی) -->
                                <div class="mb-8 position-relative" style="height: 100px; width: 100px;">
                                    <div class="pulsing-circle-primary"></div>
                                    <div class="position-relative z-10 d-flex align-center justify-center" style="height: 100%; width: 100%;">
                                        <!-- لودر چرخنده -->
                                        <v-progress-circular
                                            indeterminate
                                            color="primary"
                                            size="80"
                                            width="6"
                                        ></v-progress-circular>
                                        <!-- آیکون وسط -->
                                        <v-icon
                                            icon="mdi-cloud-upload-outline"
                                            size="32"
                                            color="primary"
                                            class="position-absolute"
                                        ></v-icon>
                                    </div>
                                </div>

                                <h2 class="text-h5 font-weight-bold text-grey-darken-3 mb-2">
                                    {{ state === 'uploading' ? 'در حال آپلود ویدیو...' : 'پردازش نهایی' }}
                                </h2>

                                <p class="text-body-1 text-grey-darken-1 mb-6 max-width-300">
                                    لطفاً تا پایان عملیات صبر کنید و مرورگر را نبندید. اطلاعات در حال ذخیره‌سازی است.
                                </p>

                                <v-chip color="primary" variant="tonal" size="small" class="font-weight-bold">
                                    وضعیت: {{ state === 'uploading' ? 'ارسال به سرور' : 'اتصال قطعات' }}
                                </v-chip>
                            </div>

                            <!-- 3. حالت پایان موفقیت‌آمیز (Completed) -->
                            <div v-else-if="state === 'completed'" class="d-flex flex-column align-center justify-center py-16 px-4 bg-surface h-100 text-center">
                                <!-- انیمیشن موفقیت (سبز) -->
                                <div class="mb-8 position-relative" style="height: 100px; width: 100px;">
                                    <div class="pulsing-circle-success"></div>
                                    <v-avatar color="success" size="80" class="elevation-5 position-relative z-10" style="margin: 10px;">
                                        <v-icon icon="mdi-check-decagram" size="48" color="white"></v-icon>
                                    </v-avatar>
                                </div>

                                <h2 class="text-h5 font-weight-bold text-grey-darken-3 mb-2">
                                    آزمون با موفقیت ثبت شد
                                </h2>

                                <p class="text-body-1 text-grey-darken-1 mb-8 max-width-300">
                                    ویدیو شما با موفقیت آپلود شد و جهت بررسی برای کارشناسان ارسال گردید. خسته نباشید!
                                </p>

                                <v-btn
                                    color="primary"
                                    variant="flat"
                                    size="large"
                                    rounded="pill"
                                    class="px-8 font-weight-bold"
                                    :href="route('web.courses.index')"
                                >
                                    بازگشت به دوره
                                </v-btn>
                            </div>

                            <!-- 4. حالت غیر مجاز -->
                            <div v-else class="d-flex flex-column align-center justify-center py-16 px-4 bg-grey-lighten-5 h-100">
                                <v-icon size="80" color="orange-lighten-2" class="mb-6 opacity-50">mdi-lock-clock</v-icon>
                                <h2 class="text-subtitle-1 font-weight-bold text-grey-darken-3 mb-2">آزمون در دسترس نیست</h2>
                                <p class="text-body-2 text-grey-darken-1 mb-4 text-center" style="max-width: 400px; margin: 0 auto;">
                                    در حال حاضر امکان دسترسی به این آزمون وجود ندارد. در صورت نیاز، لطفاً موضوع را از طریق پشتیبانی پیگیری نمایید.
                                </p>
                                <v-chip color="orange" variant="outlined" size="small" label>وضعیت: {{ props.video?.status.title }}</v-chip>
                            </div>

                        </v-card-text>
                    </v-card>

                    <!-- نوار کنترل پایین (موبایل) -->
                    <v-slide-y-reverse-transition>
                        <div v-if="isRecording" class="mobile-action-bar bg-white border-t px-4 py-3 d-flex flex-column gap-3 elevation-10" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 100;">
                            <div class="d-flex gap-3">
                                <v-btn variant="outlined" color="grey-darken-1" @click="prevQuestion" :disabled="currentQuestionIndex === 0" rounded="lg" class="flex-grow-1" height="44">
                                    <v-icon>mdi-chevron-right</v-icon><span class="d-none d-sm-inline ms-1">قبلی</span>
                                </v-btn>
                                <v-btn variant="flat" color="primary" @click="nextQuestion" :disabled="currentQuestionIndex === quiz.questions.length - 1" rounded="lg" class="flex-grow-1" height="44">
                                    <span class="d-none d-sm-inline me-1">بعدی</span><v-icon>mdi-chevron-left</v-icon>
                                </v-btn>
                            </div>
                            <div class="d-flex gap-3">
                                <v-btn variant="text" color="grey-darken-2" size="small" @click="cancelRecording" :disabled="isLoading" class="flex-grow-0"><v-icon>mdi-close</v-icon></v-btn>
                                <v-btn color="error" rounded="pill" class="flex-grow-1 font-weight-bold" @click="stopRecording" :loading="isLoading" elevation="2" height="44"><v-icon start>mdi-stop</v-icon>پایان آزمون</v-btn>
                            </div>
                        </div>
                    </v-slide-y-reverse-transition>

                    <v-snackbar :model-value="!isOnline" color="error" location="top" timeout="-1" elevation="10" rounded="pill" class="mt-12">
                        <div class="d-flex align-center justify-center w-100"><v-icon start icon="mdi-wifi-off" class="animate-pulse" size="small"></v-icon><span class="text-caption font-weight-bold">اینترنت قطع شد! ضبط ادامه دارد...</span></div>
                    </v-snackbar>

                    <v-dialog v-model="hasError" persistent max-width="400">
                        <v-card color="error" class="text-white">
                            <v-card-text class="pa-4 text-center">{{ errorMessage }}</v-card-text>
                            <v-card-actions class="justify-center"><v-btn variant="text" color="white" @click="errorMessage = ''">بستن</v-btn></v-card-actions>
                        </v-card>
                    </v-dialog>

                </v-col>
            </v-row>
        </v-container>
    </VideoLayout>
</template>


<script setup>
import { ref, onMounted, onBeforeUnmount, onUnmounted, computed } from 'vue';
import axios from 'axios';
import localforage from 'localforage';
import { route } from "ziggy-js";
import VideoLayout from "@/Layouts/VideoLayout.vue";

// --- Props & Setup ---
const props = defineProps({
    uuid: String,
    quiz: Object,
    video: Object,
})

const isAllowedToRecord = computed(() => props.video?.status.value === 'pending' || props.video?.status.value === 'rejected');
const hasError = computed(() => !!errorMessage.value);

localforage.config({ name: 'VideoUploadApp', storeName: 'upload_chunks' });

// --- Refs ---
const videoPreview = ref(null);
const stream = ref(null);
const mediaRecorder = ref(null);
const state = ref('idle'); // idle, recording, uploading, processing, completed
const timer = ref(0);
const timerInterval = ref(null);
const errorMessage = ref('');
const canRecord = ref(false);
const isLoading = ref(false);
const isRecording = ref(false);
const isOnline = ref(navigator.onLine);
const currentQuestionIndex = ref(0);
let videoUuid = ref(props.uuid);
let chunkCounter = 0;
let isUploaderRunning = false;
const permissionError = ref(false);
// --- Logic ---
const currentQuestion = computed(() => props.quiz?.questions?.[currentQuestionIndex.value] || null);
const nextQuestion = () => { if (currentQuestionIndex.value < props.quiz.questions.length - 1) currentQuestionIndex.value++; };
const prevQuestion = () => { if (currentQuestionIndex.value > 0) currentQuestionIndex.value--; };

const startRecording = async () => {
    if (!isAllowedToRecord.value) return;
    const confirm = await $confirm('آیا می‌خواهید شروع به ضبط کنید؟');
    if (!confirm) return;
    try {
        errorMessage.value = '';
        state.value = 'recording';
        timer.value = 0;
        chunkCounter = 0;
        currentQuestionIndex.value = 0;
        await localforage.clear();

        stream.value = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: "user"
            },
            audio: true
        });
        videoPreview.value.srcObject = stream.value;

        await axios.post(route('web.video.init', videoUuid.value));

        let options = { mimeType: 'video/webm;codecs=vp9,opus', videoBitsPerSecond: 1000000 };
        if (!MediaRecorder.isTypeSupported(options.mimeType)) delete options.mimeType;

        mediaRecorder.value = new MediaRecorder(stream.value, options);
        mediaRecorder.value.ondataavailable = handleDataAvailable;
        mediaRecorder.value.start(5000);

        isRecording.value = true;
        startTimer();
    } catch (err) {
        console.error(err);
        errorMessage.value = 'خطا در دسترسی به دوربین. لطفاً دسترسی‌ها را بررسی کنید.';
        state.value = 'idle';
    }
};

const handleDataAvailable = async (event) => {
    if (event.data && event.data.size > 0) {
        const idx = chunkCounter++;
        await localforage.setItem(`chunk_${String(idx).padStart(5, '0')}`, {
            uuid: videoUuid.value, index: idx, blob: event.data
        });
        processUploadQueue();
    }
};

const stopRecording = async () => {
    if (await $confirm('از اتمام آزمون اطمینان دارید؟')) {
        if (mediaRecorder.value && state.value === 'recording') {
            mediaRecorder.value.stop();
            isRecording.value = false;
            stopStream();
            stopTimer();
            state.value = 'uploading';
        }
    }
};

const cancelRecording = async () => {
    if (await $confirm('آیا آزمون را لغو می‌کنید؟')) {
        if (mediaRecorder.value && state.value === 'recording') mediaRecorder.value.stop();
        state.value = 'idle';
        isRecording.value = false;
        stopStream();
        stopTimer();
        await localforage.clear();
        initializeCamera();
    }
};

const initializeCamera = async () => {
    // اگر قبلاً استریم داریم، کاری نکن
    if (stream.value) return;

    // ریست کردن ارورها
    permissionError.value = false;
    errorMessage.value = '';

    try {
        // تلاش اول: کیفیت ایده آل
        const mediaStream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: "user"
            },
            audio: true
        });
        handleStreamSuccess(mediaStream);
    } catch (err) {
        console.warn('HD failed, trying fallback...', err);
        try {
            // تلاش دوم: حداقل کیفیت (مناسب برای دستگاه‌های قدیمی یا ناسازگار)
            const fallbackStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: "user" }, // حذف رزولوشن تا هر چه هست را بدهد
                audio: true
            });
            handleStreamSuccess(fallbackStream);
        } catch (finalErr) {
            console.error('Camera init failed:', finalErr);
            canRecord.value = false;
            permissionError.value = true; // نمایش دکمه تلاش مجدد

            // تشخیص نوع ارور برای نمایش پیام بهتر
            if (finalErr.name === 'NotAllowedError' || finalErr.name === 'PermissionDeniedError') {
                errorMessage.value = 'دسترسی به دوربین رد شد. لطفاً دسترسی را از تنظیمات مرورگر فعال کنید.';
            } else if (finalErr.name === 'NotFoundError') {
                errorMessage.value = 'دوربین پیدا نشد.';
            } else {
                errorMessage.value = 'خطا در راه‌اندازی دوربین: ' + finalErr.message;
            }
        }
    }
};

// تابع کمکی برای موفقیت
const handleStreamSuccess = (mediaStream) => {
    stream.value = mediaStream;
    if (videoPreview.value) {
        videoPreview.value.srcObject = mediaStream;
        // نکته مهم: در برخی موبایل‌ها باید play زده شود
        videoPreview.value.play().catch(e => console.log('Autoplay blocked', e));
    }
    canRecord.value = true;
    permissionError.value = false;
};

const processUploadQueue = async () => {
    if (isUploaderRunning) return;
    isUploaderRunning = true;
    try {
        const keys = await localforage.keys();
        if (keys.length > 0) {
            const nextKey = keys.sort()[0];
            const item = await localforage.getItem(nextKey);
            if (item) {
                const fd = new FormData();
                fd.append('uuid', item.uuid); fd.append('chunk_index', item.index); fd.append('chunk', item.blob);
                await axios.post(route('web.video.chunk'), fd);
                await localforage.removeItem(nextKey);
            }
            isUploaderRunning = false;
            processUploadQueue();
        } else {
            // اگر صفی نمانده و وضعیت آپلودینگ است، یعنی کار تمام شده
            if (state.value === 'uploading') await finishUpload();
            isUploaderRunning = false;
        }
    } catch (e) { setTimeout(() => { isUploaderRunning = false; processUploadQueue(); }, 2000); }
};

const finishUpload = async () => {
    state.value = 'processing';
    await axios.post(route('web.video.finish'), { uuid: videoUuid.value, total_chunks: chunkCounter });

    // اینجا که وضعیت completed می‌شود، computed بالا مقدار false را برمی‌گرداند
    // و خروج آزاد می‌شود
    state.value = 'completed';

    await localforage.clear();
};

// Utils
const stopStream = () => stream.value?.getTracks().forEach(t => t.stop());
const startTimer = () => { timerInterval.value = setInterval(() => timer.value++, 1000); };
const stopTimer = () => clearInterval(timerInterval.value);
const formatTime = (s) => `${Math.floor(s / 60).toString().padStart(2, '0')}:${(s % 60).toString().padStart(2, '0')}`;
const updateOnlineStatus = () => isOnline.value = navigator.onLine;

onMounted(async () => {
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);

    // تغییر مهم: فقط اگر وضعیت مجاز بود (pending یا rejected) دوربین را روشن کن
    if (isAllowedToRecord.value) {
        await initializeCamera();
    }
});

onBeforeUnmount(() => { stopStream(); stopTimer(); });
onUnmounted(() => { window.removeEventListener('online', updateOnlineStatus); window.removeEventListener('offline', updateOnlineStatus); });

// شرطی که تعیین می‌کند آیا هشدار نمایش داده شود یا خیر
// مثلاً وقتی در حال ضبط یا آپلود هستید این را true کنید
const preventExit = computed(() => {
    // فقط در زمان ضبط، آپلود و پردازش جلوی خروج را بگیر
    return ['recording', 'uploading', 'processing'].includes(state.value);
});

const handleBeforeUnload = (event) => {
    if (preventExit.value) {
        // کنسل کردن رویداد پیش‌فرض
        event.preventDefault();
        // این خط برای نمایش دیالوگ استاندارد کروم و سایر مرورگرها الزامی است
        event.returnValue = '';
        return '';
    }
};

onMounted(() => {
    window.addEventListener('beforeunload', handleBeforeUnload);
});

onUnmounted(() => {
    window.removeEventListener('beforeunload', handleBeforeUnload);
});
</script>

<style scoped>
/* --- Font Fixes for Persian --- */
.persian-wrapper :deep(.text-h1), .persian-wrapper :deep(.text-h2),
.persian-wrapper :deep(.text-h3), .persian-wrapper :deep(.text-h4),
.persian-wrapper :deep(.text-h5), .persian-wrapper :deep(.text-h6),
.persian-wrapper :deep(.text-subtitle-1), .persian-wrapper :deep(.text-subtitle-2),
.persian-wrapper :deep(.text-body-1), .persian-wrapper :deep(.text-body-2),
.persian-wrapper :deep(.text-button), .persian-wrapper :deep(.text-caption),
.persian-wrapper :deep(.text-overline) {
    font-family: inherit !important;
    letter-spacing: 0 !important;
}

.font-monospace { letter-spacing: 1px; font-family: monospace !important; }
.gap-3 { gap: 12px; }

/*
   Video Styles - اصلاح شده برای نمایش کامل کادر دوربین
*/
.video-wrapper-container {
    width: 100%;
    background: #000;
}

.video-wrapper {
    width: 100%;
    /* حذف aspect-ratio ثابت */
    position: relative;
    overflow: hidden;
    background: #18181b; /* هماهنگ با رنگ پس‌زمینه جدید */
    min-height: 350px; /* حداقل ارتفاع برای نمایش دکمه فعال‌سازی قبل از لود دوربین */
    display: flex;
    align-items: center;
    justify-content: center;
}

.sticky-mobile {
    position: sticky;
    top: 0;
    z-index: 50;
    border-bottom: 1px solid #333;
}

.video-element {
    width: 100%;
    height: auto; /* ارتفاع بر اساس ورودی دوربین تنظیم می‌شود */
    max-height: 60vh; /* جلوگیری از اشغال کل صفحه در موبایل */
    object-fit: contain; /* تضمین می‌کند هیچ بخشی از تصویر بریده نمی‌شود */
    display: block;
}

.is-mirror { transform: scaleX(-1); }

/* Rec Indicator */
.rec-indicator { position: absolute; top: 12px; left: 12px; background: rgba(255, 0, 0, 0.7); color: white; padding: 4px 8px; border-radius: 4px; display: flex; align-items: center; gap: 6px; z-index: 20; }
.rec-indicator .dot { width: 8px; height: 8px; background: white; border-radius: 50%; }
.mobile-overlay-timer { position: absolute; bottom: 8px; right: 12px; background: rgba(0,0,0,0.6); color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-family: monospace; z-index: 20; }

/* Animations */
.blink-animation { animation: blink 1s infinite; }
@keyframes blink { 50% { opacity: 0; } }

/* Success Pulse Animation (Green) */
.pulsing-circle-success {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background-color: rgba(76, 175, 80, 0.2);
    border-radius: 50%;
    animation: pulse-green 2s infinite;
}
@keyframes pulse-green {
    0% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7); }
    70% { transform: translate(-50%, -50%) scale(1.5); box-shadow: 0 0 0 20px rgba(76, 175, 80, 0); }
    100% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); }
}

/* Processing Pulse Animation (Blue/Primary) */
.pulsing-circle-primary {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background-color: rgba(25, 118, 210, 0.1);
    border-radius: 50%;
    animation: pulse-blue 2s infinite;
}
@keyframes pulse-blue {
    0% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(25, 118, 210, 0.7); }
    70% { transform: translate(-50%, -50%) scale(1.5); box-shadow: 0 0 0 20px rgba(25, 118, 210, 0); }
    100% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(25, 118, 210, 0); }
}

/* UI Elements */
.leading-relaxed { line-height: 1.8 !important; }
.mobile-action-bar { box-shadow: 0 -4px 10px rgba(0,0,0,0.05); }
.option-item { border-color: #e0e0e0 !important; transition: none; }
.option-bullet { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.dashed-divider { position: absolute; left: 0; top: 15%; bottom: 15%; width: 1px; border-left: 2px dashed #e0e0e0; }
</style>
