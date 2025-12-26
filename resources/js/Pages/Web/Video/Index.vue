<template>
    <VideoLayout>
        <v-container class="record-container">
            <!-- کارت اصلی ضبط ویدیو -->
            <v-card class="mx-auto" max-width="800" elevation="4">
                <v-card-title class="text-h5 font-weight-bold primary--text d-flex justify-center py-4">
                    <v-icon large color="primary" class="me-2">mdi-video</v-icon>
                    {{ isAllowedToRecord ? 'ضبط ویدیوی جدید' : 'وضعیت آزمون' }}
                </v-card-title>

                <!-- حالت مجاز: نمایش ریکوردر -->
                <v-card-text class="text-center" v-if="isAllowedToRecord">
                    <!-- Video Preview -->
                    <div class="video-preview mb-6" :class="{ 'has-video': stream }">
                        <video
                            ref="videoPreview"
                            autoplay
                            playsinline
                            muted
                            class="elevation-2"
                            :class="{ 'recording': isRecording }"
                        ></video>
                        <div v-if="!stream" class="preview-placeholder">
                            <v-icon size="64" color="grey lighten-1">mdi-video-off</v-icon>
                            <div class="text-subtitle-1 mt-2 grey--text">پیش‌نمایش دوربین</div>
                        </div>
                    </div>

                    <!-- Timer & Status -->
                    <div class="d-flex justify-center align-center mb-6">
                        <v-chip
                            v-if="isRecording"
                            color="red"
                            text-color="white"
                            class="mr-2"
                        >
                            <v-icon left>mdi-record</v-icon>
                            در حال ضبط
                        </v-chip>
                        <div class="text-h5 font-weight-medium" :class="{ 'red--text': isRecording }">
                            {{ formatTime(timer) }}
                        </div>
                    </div>

                    <!-- Controls -->
                    <div class="d-flex justify-center flex-wrap gap-3">
                        <v-btn
                            v-if="!isRecording"
                            x-large
                            color="red"
                            dark
                            rounded
                            @click="startRecording"
                            :loading="isLoading"
                            :disabled="!canRecord"
                        >
                            <v-icon left>mdi-record-circle</v-icon>
                            شروع ضبط
                        </v-btn>

                        <v-btn
                            v-else
                            x-large
                            color="red"
                            dark
                            rounded
                            @click="stopRecording"
                            :loading="isLoading"
                        >
                            <v-icon left>mdi-stop</v-icon>
                            توقف ضبط
                        </v-btn>

                        <v-btn
                            v-if="isRecording"
                            x-large
                            color="grey darken-1"
                            text
                            @click="cancelRecording"
                            :disabled="isLoading"
                        >
                            انصراف
                        </v-btn>
                    </div>

                    <!-- Instructions -->
                    <v-alert
                        v-if="!isRecording"
                        type="info"
                        border="left"
                        class="mt-6 text-right"
                        color="primary"
                        dense
                        outlined
                    >
                        <strong>راهنما:</strong> برای شروع ضبط روی دکمه قرمز کلیک کنید. حداکثر زمان ضبط ۱۰ دقیقه می‌باشد.
                    </v-alert>

                    <!-- Internet Connection Status -->
                    <v-alert
                        v-if="!isOnline"
                        type="error"
                        border="left"
                        class="mt-4 text-right"
                        dense
                        outlined
                        transition="scale-transition"
                    >
                        <v-row align="center">
                            <v-col cols="auto">
                                <v-icon>mdi-wifi-off</v-icon>
                            </v-col>
                            <v-col>
                                <strong>خطا در اتصال به اینترنت</strong>
                                <div class="text-caption">لطفا اتصال اینترنت خود را بررسی کنید.</div>
                            </v-col>
                        </v-row>
                    </v-alert>
                </v-card-text>

                <!-- حالت غیر مجاز: نمایش پیام خطا -->
                <v-card-text v-else class="text-center py-10">
                    <v-icon size="80" color="orange darken-2" class="mb-4">mdi-lock-alert</v-icon>
                    <h2 class="text-h5 font-weight-bold mb-3">امکان شرکت در آزمون وجود ندارد</h2>
                    <v-alert type="warning" variant="tonal" class="text-right mx-auto" max-width="500">
                        در صورتی که فکر می‌کنید اشتباهی رخ داده است، لطفاً با پشتیبانی تماس بگیرید.
                    </v-alert>
                </v-card-text>

                <!-- Upload Progress -->
                <v-progress-linear
                    v-if="uploadProgress > 0"
                    :value="uploadProgress"
                    height="8"
                    color="primary"
                    class="mb-0"
                ></v-progress-linear>
            </v-card>

            <!-- بخش سوالات آزمون (فقط اگر مجاز باشد و در حال ضبط باشد) -->
            <v-slide-y-transition>
                <v-card
                    v-if="isAllowedToRecord && isRecording && currentQuestion"
                    class="mx-auto mt-4"
                    max-width="800"
                    elevation="4"
                    border
                >
                    <v-toolbar color="grey-lighten-4" density="compact">
                        <v-toolbar-title class="text-body-1 font-weight-bold">
                            سوال {{ currentQuestionIndex + 1 }} از {{ quiz.questions.length }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>

                    <v-card-text class="text-right py-4">
                        <!-- متن سوال -->
                        <div class="text-h6 mb-4 font-weight-bold text-wrap">
                            {{ currentQuestion.question ? currentQuestion.question : `متن سوال شماره ${currentQuestionIndex + 1}` }}
                        </div>

                        <v-divider class="mb-4"></v-divider>

                        <!-- گزینه‌ها -->
                        <v-list density="compact" class="bg-transparent">
                            <v-list-item
                                v-for="(option, idx) in currentQuestion.options"
                                :key="option.id"
                                class="mb-2 rounded border"
                                :prepend-icon="`mdi-numeric-${idx+1}-box-outline`"
                            >
                                <v-list-item-title class="text-wrap py-2" style="white-space: normal; line-height: 1.6;">
                                    {{ option.option }}
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions class="justify-space-between pa-4">
                        <v-btn
                            variant="outlined"
                            color="secondary"
                            @click="prevQuestion"
                            :disabled="currentQuestionIndex === 0"
                        >
                            <v-icon right>mdi-chevron-right</v-icon>
                            سوال قبلی
                        </v-btn>

                        <v-btn
                            variant="elevated"
                            color="primary"
                            @click="nextQuestion"
                            :disabled="currentQuestionIndex === quiz.questions.length - 1"
                        >
                            سوال بعدی
                            <v-icon left>mdi-chevron-left</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-slide-y-transition>

            <!-- Error Alert -->
            <v-alert
                v-if="errorMessage"
                type="error"
                class="mt-4"
                dismissible
                @input="errorMessage = ''"
            >
                {{ errorMessage }}
            </v-alert>
        </v-container>
    </VideoLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, onUnmounted, computed } from 'vue';
import axios from 'axios';
import localforage from 'localforage';
import {route} from "ziggy-js";
import VideoLayout from "@/Layouts/VideoLayout.vue";

const props = defineProps({
    uuid: String,
    quiz: Object,
    video: Object,
})

// --- Computed Property برای بررسی وضعیت ---
const isAllowedToRecord = computed(() => {
    return props.video?.status === 'pending';
});

// --- تنظیمات LocalForage ---
localforage.config({
    name: 'VideoUploadApp',
    storeName: 'upload_chunks'
});

// --- State Variables ---
const videoPreview = ref(null);
const stream = ref(null);
const mediaRecorder = ref(null);
const state = ref('idle');
const timer = ref(0);
const timerInterval = ref(null);
const errorMessage = ref('');
const uploadProgress = ref(0);
const pendingChunksCount = ref(0);
const canRecord = ref(false);
const isLoading = ref(false);
const isRecording = ref(false);
const isOnline = ref(navigator.onLine);

// --- متغیرهای مربوط به سوالات ---
const currentQuestionIndex = ref(0);

const currentQuestion = computed(() => {
    if (props.quiz && props.quiz.questions && props.quiz.questions.length > 0) {
        return props.quiz.questions[currentQuestionIndex.value];
    }
    return null;
});

const nextQuestion = () => {
    if (props.quiz?.questions && currentQuestionIndex.value < props.quiz.questions.length - 1) {
        currentQuestionIndex.value++;
    }
};

const prevQuestion = () => {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--;
    }
};

// متغیرهای داخلی آپلود
let videoUuid = ref(props.uuid);
let chunkCounter = 0;
let isUploaderRunning = false;

// --- 1. شروع ضبط ---
const startRecording = async () => {
    if (!isAllowedToRecord.value) return; // اطمینان از اینکه در توابع هم چک می‌شود

    try {
        errorMessage.value = '';
        state.value = 'recording';
        timer.value = 0;
        chunkCounter = 0;
        uploadProgress.value = 0;

        currentQuestionIndex.value = 0;

        await localforage.clear();

        stream.value = await navigator.mediaDevices.getUserMedia({
            video: { width: 1280, height: 720 },
            audio: true
        });

        videoPreview.value.srcObject = stream.value;

        const initRes = await axios.post(route('web.video.init', videoUuid.value));

        const options = {
            mimeType: 'video/webm;codecs=vp9,opus',
            videoBitsPerSecond: 1000000,
            audioBitsPerSecond: 128000,
            audioChannelCount: 1,
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true,
                channelCount: 1,
                sampleRate: 24000,
                sampleSize: 16,
                volume: 1.0
            },
            video: {
                width: 1280,
                height: 720,
                frameRate: { min: 15, ideal: 24, max: 24 },
                bitrateMode: 'variable',
                latencyMode: 'realtime',
                advanced: [
                    { width: 1280, height: 720 },
                    { width: 854, height: 480 },
                    { aspectRatio: 16/9 }
                ]
            },
            vp9: {
                cqLevel: 30,
                cpuUsed: 4,
                frameParallel: true,
                rowMt: true,
                tileColumns: 2,
                tileRows: 1,
                noiseSensitivity: 0,
                aqMode: 2,
                lagInFrames: 0
            },
            opus: {
                complexity: 6,
                frameDuration: 60,
                application: 'voip',
                signal: 'voice',
                inbandFec: true,
                packetLossPerc: 10,
                useinbandfec: 1,
                usedtx: 0,
                maxaveragebitrate: 128000
            }
        };

        mediaRecorder.value = new MediaRecorder(stream.value, options);
        mediaRecorder.value.ondataavailable = handleDataAvailable;

        const chunkDuration = 5000;
        mediaRecorder.value.start(chunkDuration);
        isRecording.value = true;
        startTimer();

    } catch (err) {
        console.error(err);
        errorMessage.value = 'دسترسی به دوربین/میکروفون امکان‌پذیر نیست یا خطای سرور رخ داده.';
        state.value = 'idle';
    }
};

// --- 2. مدیریت داده‌ها ---
const handleDataAvailable = async (event) => {
    if (event.data && event.data.size > 0) {
        const currentChunkIndex = chunkCounter++;
        const chunkData = {
            uuid: videoUuid.value,
            index: currentChunkIndex,
            blob: event.data,
            retries: 0
        };
        await localforage.setItem(`chunk_${String(currentChunkIndex).padStart(5, '0')}`, chunkData);
        processUploadQueue();
    }
};

// --- 3. توقف ضبط ---
const stopRecording = () => {
    if (mediaRecorder.value && state.value === 'recording') {
        mediaRecorder.value.stop();
        isRecording.value = false;
        stopStream();
        stopTimer();
        state.value = 'uploading';
    }
};

const cancelRecording = async () => {
    const confirm = await $confirm('آیا از لغو ضبط ویدیو اطمینان دارید؟ تمام ویدیوی ضبط شده حذف خواهد شد.')
    if (confirm) {
        if (mediaRecorder.value && state.value === 'recording') {
            mediaRecorder.value.stop();
        }

        state.value = 'idle';
        isRecording.value = false;
        isLoading.value = false;
        timer.value = 0;
        stopTimer();

        currentQuestionIndex.value = 0;

        try {
            const keys = await localforage.keys();
            await Promise.all(keys.map(key => localforage.removeItem(key)));
            pendingChunksCount.value = 0;
            uploadProgress.value = 0;
        } catch (error) {
            console.error('Error clearing local storage:', error);
        }

        if (stream.value) {
            stream.value.getTracks().forEach(track => track.stop());
            stream.value = null;
        }

        initializeCamera();
    }
};

const initializeCamera = async () => {
    // اگر کاربر مجاز نباشد، دوربین را روشن نمی‌کنیم
    if (!isAllowedToRecord.value) return;

    try {
        const mediaStream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                frameRate: { ideal: 30, max: 30 }
            },
            audio: true
        });

        if (videoPreview.value) {
            videoPreview.value.srcObject = mediaStream;
            stream.value = mediaStream;
            canRecord.value = true;
        }
    } catch (err) {
        console.error('Error accessing media devices:', err);
        errorMessage.value = 'دسترسی به دوربین یا میکروفون ممکن نیست. لطفاً مجوزهای لازم را تأیید کنید.';
        canRecord.value = false;
    }
};

// --- 4. صف آپلود ---
const processUploadQueue = async () => {
    if (isUploaderRunning) return;
    isUploaderRunning = true;

    try {
        const keys = await localforage.keys();
        pendingChunksCount.value = keys.length;

        if (keys.length > 0) {
            const sortedKeys = keys.sort();
            const nextKey = sortedKeys[0];
            const chunkData = await localforage.getItem(nextKey);

            if (chunkData) {
                await uploadChunkToServer(chunkData);
                await localforage.removeItem(nextKey);
                uploadProgress.value = Math.min(95, uploadProgress.value + 5);
            }

            isUploaderRunning = false;
            processUploadQueue();

        } else {
            if (state.value === 'uploading') {
                await finishUpload();
            }
            isUploaderRunning = false;
        }
    } catch (err) {
        console.error("Upload Loop Error:", err);
        setTimeout(() => {
            isUploaderRunning = false;
            processUploadQueue();
        }, 2000);
    }
};

// --- 5. آپلود تکه ---
const uploadChunkToServer = async (chunkData) => {
    const formData = new FormData();
    formData.append('uuid', chunkData.uuid);
    formData.append('chunk_index', chunkData.index);
    formData.append('chunk', chunkData.blob);

    await axios.post(route('web.video.chunk'), formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    });
};

// --- 6. پایان نهایی ---
const finishUpload = async () => {
    state.value = 'processing';
    try {
        await axios.post(route('web.video.finish'), {
            uuid: videoUuid.value,
            total_chunks: chunkCounter
        });

        state.value = 'completed';
        uploadProgress.value = 100;

        await localforage.clear();

    } catch (err) {
        console.error(err);
        errorMessage.value = 'خطا در پردازش نهایی ویدیو.';
        state.value = 'idle';
    }
};

// --- Utility Functions ---
const stopStream = () => {
    if (stream.value) {
        stream.value.getTracks().forEach(track => track.stop());
        stream.value = null;
    }
};

const startTimer = () => {
    timerInterval.value = setInterval(() => {
        timer.value++;
    }, 1000);
};

const stopTimer = () => {
    clearInterval(timerInterval.value);
};

const formatTime = (seconds) => {
    const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
    const secs = (seconds % 60).toString().padStart(2, '0');
    return `${mins}:${secs}`;
};

// --- Lifecycle ---
const updateOnlineStatus = () => {
    isOnline.value = navigator.onLine;
};

onMounted(async () => {
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);

    // چک کردن وضعیت قبل از راه‌اندازی دوربین
    if (isAllowedToRecord.value) {
        try {
            const mediaStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    frameRate: { ideal: 30, max: 30 }
                },
                audio: true
            });

            if (videoPreview.value) {
                videoPreview.value.srcObject = mediaStream;
                stream.value = mediaStream;
                canRecord.value = true;
            }
        } catch (err) {
            console.error('Error accessing media devices:', err);
            errorMessage.value = 'دسترسی به دوربین یا میکروفون ممکن نیست. لطفاً مجوزهای لازم را تأیید کنید.';
        }
    }
});

onBeforeUnmount(() => {
    stopStream();
    stopTimer();
});

onUnmounted(() => {
    window.removeEventListener('online', updateOnlineStatus);
    window.removeEventListener('offline', updateOnlineStatus);
});
</script>

<style scoped>
.v-list-item--variant-text .v-list-item__overlay {
    background: transparent;
}
</style>
