<template>
    <VideoLayout>
        <v-container class="fill-height align-start justify-center pa-0 pa-md-4 persian-wrapper" fluid>

            <v-row justify="center" class="w-100 ma-0">
                <v-col cols="12" md="10" lg="8" class="pa-0">

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

                            <!-- بخش تایمر (فقط تایمر اصلی) -->
                            <div v-if="isAllowedToRecord" class="d-flex align-center">

                                <!-- تایمر اصلی با قابلیت چشمک زن در شرایط بحرانی -->
                                <div class="timer-badge px-3 py-1 rounded-pill d-flex align-center transition-swing"
                                     :class="[
                                         isCriticalTime ? 'critical-pulse' : '',
                                         !isCriticalTime && isRecording ? 'recording-active bg-red-lighten-5 border-red' : '',
                                         !isRecording ? 'bg-grey-lighten-3' : ''
                                     ]"
                                >
                                    <span class="text-h6 font-weight-bold font-monospace transition-swing"
                                          :class="[
                                              isCriticalTime ? 'text-white' : '',
                                              !isCriticalTime && isRecording ? 'text-red-darken-2' : '',
                                              !isRecording ? 'text-grey-darken-2' : ''
                                          ]">
                                        {{ formatTime(timer) }}
                                    </span>

                                    <!-- آیکون ضبط (در حالت بحرانی مخفی می‌شود تا شلوغ نشود یا رنگش عوض می‌شود) -->
                                    <v-icon v-if="isRecording" icon="mdi-record"
                                            :color="isCriticalTime ? 'white' : 'red'"
                                            size="x-small"
                                            class="ms-2 blink-animation"
                                    ></v-icon>
                                </div>
                            </div>
                        </div>

                        <!-- بدنه اصلی -->
                        <v-card-text class="pa-0 position-relative d-flex flex-column flex-grow-1">

                            <!-- بخش ضبط -->
                            <div v-if="isAllowedToRecord && state !== 'completed' && state !== 'processing' && state !== 'uploading'" class="video-section d-flex flex-column flex-grow-1">

                                <!-- باکس ویدیو (Sticky در موبایل) -->
                                <div class="video-wrapper-container" :class="{ 'sticky-mobile': $vuetify.display.smAndDown && isRecording }">
                                    <div class="video-wrapper bg-black d-flex align-center justify-center position-relative">
                                        <video ref="videoPreview" autoplay playsinline muted class="video-element" :class="{ 'is-mirror': true }"></video>

                                        <!-- Placeholder -->
                                        <div v-if="!stream" class="video-placeholder d-flex flex-column align-center justify-center text-white" style="position: absolute; inset: 0; background: rgba(0,0,0,0.85); z-index: 10;">
                                            <div v-if="permissionError" class="text-center px-4">
                                                <v-icon size="40" color="red-accent-2" class="mb-2">mdi-camera-off</v-icon>
                                                <p class="text-body-2 font-weight-bold mb-2 text-white">دسترسی به دوربین داده نشد.</p>
                                                <v-btn color="white" variant="outlined" rounded="pill" @click="initializeCamera">تلاش مجدد</v-btn>
                                            </div>
                                            <div v-else class="text-center px-4">
                                                <div class="pulse-ring mb-2"><v-icon size="40" color="white">mdi-camera-iris</v-icon></div>
                                                <h3 class="text-h6 font-weight-bold mb-2">راه‌اندازی دوربین</h3>
                                                <v-btn color="primary" rounded="pill" prepend-icon="mdi-camera" @click="initializeCamera">فعال‌سازی دوربین</v-btn>
                                            </div>
                                        </div>

                                        <div v-if="isRecording" class="rec-indicator"><span class="dot"></span><span class="text-caption font-weight-bold">ضبط</span></div>

                                        <!-- هشدار متنی روی ویدیو (فقط یک متن کلی، بدون تایمر) -->
                                        <v-slide-y-transition>
                                            <div v-if="isRecording && isCriticalTime"
                                                 class="critical-overlay d-flex align-center justify-center gap-2 px-3 py-1 rounded-pill elevation-6"
                                            >
                                                <v-icon icon="mdi-clock-alert-outline" color="white" class="rapid-pulse" size="small"></v-icon>
                                                <span class="text-white font-weight-bold text-caption">زمان رو به اتمام است!</span>
                                            </div>
                                        </v-slide-y-transition>
                                    </div>

                                    <!-- تایمر شناور موبایل (فقط تایمر اصلی با حالت چشمک زن) -->
                                    <div v-if="$vuetify.display.smAndDown && isRecording" class="mobile-timer-group">
                                        <div class="mobile-overlay-timer transition-swing" :class="{ 'critical-pulse-mobile': isCriticalTime }">
                                            {{ formatTime(timer) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- سوالات -->
                                <div v-if="isRecording && currentQuestion" class="questions-container pa-4 flex-grow-1 bg-surface">
                                    <div class="d-flex align-center justify-space-between mb-2">
                                        <span class="text-subtitle-2 font-weight-bold text-primary">سوال {{ currentQuestionIndex + 1 }}</span>
                                        <v-chip size="x-small" variant="flat" color="green-lighten-5" class="text-primary font-weight-bold">{{ currentQuestionIndex + 1 }} از {{ quiz.questions.length }}</v-chip>
                                    </div>
                                    <div class="question-box mb-6"><h3 class="text-h6 font-weight-bold text-grey-darken-4 leading-relaxed text-right">{{ currentQuestion.question }}</h3></div>

                                    <div class="options-read-only d-flex flex-column gap-3">
                                        <div v-for="(option, idx) in currentQuestion.options" :key="option.id" class="option-item d-flex align-start pa-3 rounded-lg border border-dashed bg-grey-lighten-5">
                                            <div class="option-bullet me-3 mt-1 d-flex align-center justify-center rounded-circle bg-white border text-grey-darken-2 font-weight-bold text-caption" style="width: 24px; height: 24px; flex-shrink: 0;">{{ idx + 1 }}</div>
                                            <span class="text-body-1 text-grey-darken-3 font-weight-medium">{{ option.option }}</span>
                                        </div>
                                    </div>
                                    <div class="mobile-spacer" style="height: 120px; width: 100%;"></div>
                                </div>

                                <!-- کنترل‌های شروع -->
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
                                                <span class="text-body-1 font-weight-black text-deep-orange-darken-3">{{ MAX_RECORDING_MINUTES }} دقیقه</span>
                                            </v-col>
                                        </v-row>
                                    </v-card>
                                </div>
                            </div>

                            <!-- سایر وضعیت‌ها (بدون تغییر) -->
                            <div v-else-if="state === 'uploading' || state === 'processing'" class="d-flex flex-column align-center justify-center py-16 px-4 bg-surface h-100 text-center">
                                <div class="mb-8 position-relative" style="height: 100px; width: 100px;">
                                    <div class="pulsing-circle-primary"></div>
                                    <div class="position-relative z-10 d-flex align-center justify-center" style="height: 100%; width: 100%;">
                                        <v-progress-circular indeterminate color="primary" size="80" width="6"></v-progress-circular>
                                        <v-icon icon="mdi-cloud-upload-outline" size="32" color="primary" class="position-absolute"></v-icon>
                                    </div>
                                </div>
                                <h2 class="text-h5 font-weight-bold text-grey-darken-3 mb-2">{{ state === 'uploading' ? 'در حال آپلود ویدیو...' : 'پردازش نهایی' }}</h2>
                                <p class="text-body-1 text-grey-darken-1 mb-6 max-width-300">لطفاً تا پایان عملیات صبر کنید و مرورگر را نبندید.</p>
                            </div>

                            <div v-else-if="state === 'completed'" class="d-flex flex-column align-center justify-center py-16 px-4 bg-surface h-100 text-center">
                                <div class="mb-8 position-relative" style="height: 100px; width: 100px;">
                                    <div class="pulsing-circle-success"></div>
                                    <v-avatar color="success" size="80" class="elevation-5 position-relative z-10" style="margin: 10px;"><v-icon icon="mdi-check-decagram" size="48" color="white"></v-icon></v-avatar>
                                </div>
                                <h2 class="text-h5 font-weight-bold text-grey-darken-3 mb-2">آزمون با موفقیت ثبت شد</h2>
                                <v-btn color="primary" variant="flat" size="large" rounded="pill" class="px-8 font-weight-bold mt-6" :href="route('web.courses.index')">بازگشت به دوره‌ها</v-btn>
                            </div>

                            <div v-else class="d-flex flex-column align-center justify-center py-16 px-4 bg-grey-lighten-5 h-100">
                                <v-icon size="80" color="orange-lighten-2" class="mb-6 opacity-50">mdi-lock-clock</v-icon>
                                <h2 class="text-subtitle-1 font-weight-bold text-grey-darken-3 mb-2">آزمون در دسترس نیست</h2>
                                <p class="text-body-2 text-grey-darken-1 mb-4 text-center">امکان دسترسی وجود ندارد.</p>
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

// --- تنظیمات محدودیت زمانی ---
const MAX_RECORDING_MINUTES = ref(10);
const MAX_DURATION_SECONDS = computed(() => MAX_RECORDING_MINUTES.value * 60);

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
const state = ref('idle');
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

// --- Computed Logic for Time ---
const remainingSeconds = computed(() => Math.max(0, MAX_DURATION_SECONDS.value - timer.value));
// بحرانی: کمتر از ۶۰ ثانیه
const isCriticalTime = computed(() => remainingSeconds.value <= 60 && remainingSeconds.value > 0);

const currentQuestion = computed(() => props.quiz?.questions?.[currentQuestionIndex.value] || null);
const nextQuestion = () => { if (currentQuestionIndex.value < props.quiz.questions.length - 1) currentQuestionIndex.value++; };
const prevQuestion = () => { if (currentQuestionIndex.value > 0) currentQuestionIndex.value--; };

const startRecording = async () => {
    if (isLoading.value) return;
    if (!isAllowedToRecord.value) return;

    const confirm = await $confirm(`آیا آماده شروع آزمون هستید؟ (زمان مجاز: ${MAX_RECORDING_MINUTES.value} دقیقه)`);
    if (!confirm) return;

    isLoading.value = true;

    try {
        stopTimer();
        errorMessage.value = '';
        state.value = 'recording';
        timer.value = 0;
        chunkCounter = 0;
        currentQuestionIndex.value = 0;
        await localforage.clear();

        if (!stream.value || !stream.value.active) {
            stream.value = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 854 }, height: { ideal: 480 }, frameRate: { ideal: 24, max: 30 }, facingMode: "user" },
                audio: { echoCancellation: true, noiseSuppression: true }
            });
            videoPreview.value.srcObject = stream.value;
            await videoPreview.value.play();
        }

        await axios.post(route('web.video.init', videoUuid.value));

        let options = { mimeType: 'video/webm;codecs=vp8,opus', videoBitsPerSecond: 600000 };
        if (!MediaRecorder.isTypeSupported(options.mimeType)) {
            options = { mimeType: 'video/mp4', videoBitsPerSecond: 600000 };
            if (!MediaRecorder.isTypeSupported(options.mimeType)) delete options.mimeType;
        }

        mediaRecorder.value = new MediaRecorder(stream.value, options);
        mediaRecorder.value.ondataavailable = handleDataAvailable;
        mediaRecorder.value.start(10000); // 10s chunks

        isRecording.value = true;
        startTimer();

    } catch (err) {
        console.error(err);
        errorMessage.value = err.response?.data?.message ?? 'خطا در دسترسی به دوربین یا ارتباط با سرور.';
        state.value = 'idle';
        isRecording.value = false;
    } finally {
        isLoading.value = false;
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

const startTimer = () => {
    stopTimer();
    timerInterval.value = setInterval(() => {
        timer.value++;
        // توقف خودکار
        if (timer.value >= MAX_DURATION_SECONDS.value) {
            autoStopRecording();
        }
    }, 1000);
};

const stopTimer = () => clearInterval(timerInterval.value);

const autoStopRecording = async () => {
    if (mediaRecorder.value && state.value === 'recording') {
        mediaRecorder.value.stop();
        isRecording.value = false;
        stopStream();
        stopTimer();
        state.value = 'uploading';
    }
};

const stopRecording = async () => {
    if (await $confirm('از اتمام آزمون اطمینان دارید؟')) {
        autoStopRecording();
    }
};

const cancelRecording = async () => {
    if (await $confirm('آیا آزمون را لغو می‌کنید؟')) {
        state.value = 'idle';
        isRecording.value = false;
        if (mediaRecorder.value) {
            mediaRecorder.value.ondataavailable = null;
            if (mediaRecorder.value.state === 'recording') mediaRecorder.value.stop();
        }
        stopStream();
        stopTimer();
        await localforage.clear();
        try { await axios.delete(route('web.video.cancel', videoUuid.value)); } catch (e) {}
        initializeCamera();
    }
};

const initializeCamera = async () => {
    if (stream.value) return;
    permissionError.value = false;
    errorMessage.value = '';
    try {
        const mediaStream = await navigator.mediaDevices.getUserMedia({
            video: { width: { ideal: 854 }, height: { ideal: 480 }, frameRate: { ideal: 24, max: 30 }, facingMode: "user" },
            audio: { echoCancellation: true, noiseSuppression: true, autoGainControl: true }
        });
        handleStreamSuccess(mediaStream);
    } catch (err) {
        try {
            const fallbackStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: true });
            handleStreamSuccess(fallbackStream);
        } catch (finalErr) {
            canRecord.value = false;
            permissionError.value = true;
            errorMessage.value = 'دسترسی به دوربین امکان پذیر نیست.';
        }
    }
};

const handleStreamSuccess = (mediaStream) => {
    stream.value = mediaStream;
    if (videoPreview.value) {
        videoPreview.value.srcObject = mediaStream;
        videoPreview.value.play().catch(e => console.log('Autoplay blocked', e));
    }
    canRecord.value = true;
    permissionError.value = false;
};

const processUploadQueue = async () => {
    if (isUploaderRunning) return;
    if (state.value === 'idle') return;
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
            if (state.value === 'uploading') await finishUpload();
            isUploaderRunning = false;
        }
    } catch (e) { setTimeout(() => { isUploaderRunning = false; processUploadQueue(); }, 2000); }
};

const finishUpload = async () => {
    state.value = 'processing';
    await axios.post(route('web.video.finish'), { uuid: videoUuid.value, total_chunks: chunkCounter });
    state.value = 'completed';
    await localforage.clear();
};

const stopStream = () => {
    if (stream.value) {
        stream.value.getTracks().forEach(track => track.stop());
        stream.value = null;
    }
    if (videoPreview.value) videoPreview.value.srcObject = null;
};

const formatTime = (s) => `${Math.floor(s / 60).toString().padStart(2, '0')}:${(s % 60).toString().padStart(2, '0')}`;
const updateOnlineStatus = () => isOnline.value = navigator.onLine;

onMounted(async () => {
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    window.addEventListener('beforeunload', handleBeforeUnload);
    if (isAllowedToRecord.value) await initializeCamera();
});

onBeforeUnmount(() => { stopStream(); stopTimer(); });
onUnmounted(() => {
    window.removeEventListener('online', updateOnlineStatus);
    window.removeEventListener('offline', updateOnlineStatus);
    window.removeEventListener('beforeunload', handleBeforeUnload);
});

const preventExit = computed(() => ['recording', 'uploading', 'processing'].includes(state.value));
const handleBeforeUnload = (event) => {
    if (preventExit.value) { event.preventDefault(); event.returnValue = ''; return ''; }
};
</script>

<style scoped>
.persian-wrapper :deep(.text-h1), .persian-wrapper :deep(.text-h2),
.persian-wrapper :deep(.text-h3), .persian-wrapper :deep(.text-h4),
.persian-wrapper :deep(.text-h5), .persian-wrapper :deep(.text-h6),
.persian-wrapper :deep(.text-subtitle-1), .persian-wrapper :deep(.text-subtitle-2),
.persian-wrapper :deep(.text-body-1), .persian-wrapper :deep(.text-body-2),
.persian-wrapper :deep(.text-button), .persian-wrapper :deep(.text-caption) {
    font-family: inherit !important; letter-spacing: 0 !important;
}

.font-monospace { letter-spacing: 1px; font-family: monospace !important; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 12px; }

.video-wrapper-container { width: 100%; background: #000; }
.video-wrapper { width: 100%; position: relative; overflow: hidden; background: #18181b; min-height: 350px; display: flex; align-items: center; justify-content: center; }
.sticky-mobile { position: sticky; top: 0; z-index: 50; border-bottom: 1px solid #333; }
.video-element { width: 100%; height: auto; max-height: 60vh; object-fit: contain; display: block; }
.is-mirror { transform: scaleX(-1); }

/* Indicators */
.rec-indicator { position: absolute; top: 12px; left: 12px; background: rgba(255, 0, 0, 0.7); color: white; padding: 4px 8px; border-radius: 4px; display: flex; align-items: center; gap: 6px; z-index: 20; }
.rec-indicator .dot { width: 8px; height: 8px; background: white; border-radius: 50%; }

/* Mobile Floating Timer */
.mobile-timer-group { position: absolute; bottom: 8px; right: 12px; display: flex; flex-direction: column; align-items: flex-end; z-index: 20; }
.mobile-overlay-timer { background: rgba(0,0,0,0.6); color: white; padding: 2px 8px; border-radius: 12px; font-size: 14px; font-family: monospace; }

/* PANIC MODE ANIMATIONS */
/* دسکتاپ: چشمک زدن پس‌زمینه و کمی تغییر سایز */
.critical-pulse {
    animation: panic-pulse 1s infinite ease-in-out;
}
@keyframes panic-pulse {
    0% { background-color: #d32f2f; color: white; border-color: #d32f2f; transform: scale(1); }
    50% { background-color: #b71c1c; color: white; border-color: #b71c1c; transform: scale(1.1); box-shadow: 0 0 10px rgba(211, 47, 47, 0.5); }
    100% { background-color: #d32f2f; color: white; border-color: #d32f2f; transform: scale(1); }
}

/* موبایل: چشمک زدن شدید قرمز برای باکس شناور */
.critical-pulse-mobile {
    animation: panic-pulse-mobile 0.8s infinite;
}
@keyframes panic-pulse-mobile {
    0% { background-color: rgba(213, 0, 0, 0.8); }
    50% { background-color: rgba(255, 23, 68, 1); }
    100% { background-color: rgba(213, 0, 0, 0.8); }
}

/* Video Overlay Warning */
.rapid-pulse { animation: rapid-pulse-anim 1s infinite; }
@keyframes rapid-pulse-anim { 0% { transform: scale(1); } 50% { transform: scale(1.3); } 100% { transform: scale(1); } }
.critical-overlay { position: absolute; top: 12px; left: 50%; transform: translateX(-50%); background: linear-gradient(90deg, #d32f2f, #ef5350); z-index: 30; box-shadow: 0 4px 15px rgba(211, 47, 47, 0.4); border: 1px solid rgba(255,255,255,0.3); min-width: 180px; }

/* Standard Animations */
.border-red { border: 1px solid #ff5252 !important; }
.blink-animation { animation: blink 1s infinite; }
@keyframes blink { 50% { opacity: 0; } }
.pulsing-circle-success { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80px; height: 80px; background-color: rgba(76, 175, 80, 0.2); border-radius: 50%; animation: pulse-green 2s infinite; }
@keyframes pulse-green { 0% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7); } 70% { transform: translate(-50%, -50%) scale(1.5); box-shadow: 0 0 0 20px rgba(76, 175, 80, 0); } 100% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); } }
.pulsing-circle-primary { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80px; height: 80px; background-color: rgba(25, 118, 210, 0.1); border-radius: 50%; animation: pulse-blue 2s infinite; }
@keyframes pulse-blue { 0% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(25, 118, 210, 0.7); } 70% { transform: translate(-50%, -50%) scale(1.5); box-shadow: 0 0 0 20px rgba(25, 118, 210, 0); } 100% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(25, 118, 210, 0); } }

/* UI */
.leading-relaxed { line-height: 1.8 !important; }
.mobile-action-bar { box-shadow: 0 -4px 10px rgba(0,0,0,0.05); }
.option-item { border-color: #e0e0e0 !important; transition: none; }
.option-bullet { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.dashed-divider { position: absolute; left: 0; top: 15%; bottom: 15%; width: 1px; border-left: 2px dashed #e0e0e0; }
.transition-swing { transition: all 0.5s cubic-bezier(0.25, 0.8, 0.5, 1); }
</style>
