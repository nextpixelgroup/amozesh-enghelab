<template>
    <VideoLayout>
        <v-container class="record-container">
            <v-card class="mx-auto" max-width="800" elevation="4">
                <v-card-title class="text-h5 font-weight-bold primary--text d-flex justify-center py-4">
                    <v-icon large color="primary" class="me-2">mdi-video</v-icon>
                    ضبط ویدیوی جدید
                </v-card-title>

                <v-card-text class="text-center">
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
                        <!-- Record Button -->
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

                        <!-- Stop Button -->
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

                        <!-- Cancel Button -->
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
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import localforage from 'localforage';
import {route} from "ziggy-js";
import VideoLayout from "@/Layouts/VideoLayout.vue";

// --- تنظیمات LocalForage ---
localforage.config({
    name: 'VideoUploadApp',
    storeName: 'upload_chunks'
});

// --- State Variables ---
const videoPreview = ref(null);
const stream = ref(null);
const mediaRecorder = ref(null);
const state = ref('idle'); // 'idle', 'recording', 'uploading', 'processing', 'completed'
const timer = ref(0);
const timerInterval = ref(null);
const errorMessage = ref('');
const uploadProgress = ref(0);
const pendingChunksCount = ref(0);
const canRecord = ref(false);
const isLoading = ref(false);
const isRecording = ref(false);

// متغیرهای داخلی آپلود
let videoUuid = null;
let chunkCounter = 0;
let isUploaderRunning = false;

// --- 1. شروع ضبط ---
const startRecording = async () => {
    try {
        errorMessage.value = '';
        state.value = 'recording';
        timer.value = 0;
        chunkCounter = 0;
        uploadProgress.value = 0;

        // پاکسازی دیتابیس قدیمی برای اطمینان
        await localforage.clear();

        // درخواست دسترسی به مدیا
        stream.value = await navigator.mediaDevices.getUserMedia({
            video: { width: 1280, height: 720 }, // HD Resolution
            audio: true
        });

        videoPreview.value.srcObject = stream.value;

        // دریافت UUID جدید از سرور
        const initRes = await axios.post(route('web.video.init'));
        videoUuid = initRes.data.uuid;


        // تنظیمات MediaRecorder با سازگاری بیشتر
        const options = {
            mimeType: 'video/webm;codecs=vp9,opus',
            videoBitsPerSecond: 1000000,  // 1 Mbps (optimized for 720p)
            audioBitsPerSecond: 128000,   // 128 Kbps (good quality with opus)
            audioChannelCount: 1,         // Mono audio is sufficient for voice
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true,
                channelCount: 1,
                sampleRate: 24000,        // 24kHz is sufficient for voice
                sampleSize: 16,
                volume: 1.0
            },
            video: {
                width: 1280,
                height: 720,
                frameRate: {
                    min: 15,             // Minimum acceptable frame rate
                    ideal: 24,            // Target frame rate
                    max: 24               // Cap at 24fps for consistency
                },
                // VP9 specific optimizations
                bitrateMode: 'variable',  // Better compression for static scenes
                latencyMode: 'realtime',  // Optimize for real-time encoding
                // Advanced constraints for better compression
                advanced: [
                    // Prefer hardware acceleration if available
                    { width: 1280, height: 720 },
                    // Fallback to lower resolution if needed
                    { width: 854, height: 480 },
                    // Optimize for screen content if needed
                    { aspectRatio: 16/9 }
                ]
            },
            // Additional VP9 encoder settings
            vp9: {
                cqLevel: 30,              // Quality level (0-63, lower is better quality)
                cpuUsed: 4,               // Speed/quality tradeoff (0-8, lower is better quality)
                frameParallel: true,      // Enable frame parallel decoding
                rowMt: true,              // Enable row-based multi-threading
                tileColumns: 2,           // Enable tiling for parallel encoding
                tileRows: 1,              // 2x1 tiling
                noiseSensitivity: 0,      // Disable noise sensitivity for better compression
                aqMode: 2,                // Adaptive quantization mode (2 is good for most content)
                lagInFrames: 0            // Disable lag for real-time encoding
            },
            // Opus audio settings
            opus: {
                complexity: 6,            // Quality/speed tradeoff (0-10, higher is better quality)
                frameDuration: 60,        // 60ms frame size (good balance between latency and efficiency)
                application: 'voip',      // Optimize for voice
                signal: 'voice',          // Optimize for voice
                inbandFec: true,          // Enable forward error correction
                packetLossPerc: 10,       // Expected packet loss percentage
                useinbandfec: 1,          // Enable in-band FEC
                usedtx: 0,                // Disable DTX for continuous transmission
                maxaveragebitrate: 128000 // Maximum bitrate
            }
        };

        // ایجاد MediaRecorder با تنظیمات
        mediaRecorder.value = new MediaRecorder(stream.value, options);

        // هندلر دریافت داده (هر ۵ ثانیه یا موقع توقف)
        mediaRecorder.value.ondataavailable = handleDataAvailable;

        // شروع ضبط با تکه‌های ۵ ثانیه‌ای
        const chunkDuration = 5000; // 5 seconds
        mediaRecorder.value.start(chunkDuration);
        isRecording.value = true;
        startTimer();

    } catch (err) {
        console.error(err);
        errorMessage.value = 'دسترسی به دوربین/میکروفون امکان‌پذیر نیست یا خطای سرور رخ داده.';
        state.value = 'idle';
    }
};

// --- 2. مدیریت داده‌ها و ذخیره در IndexedDB ---
const handleDataAvailable = async (event) => {
    if (event.data && event.data.size > 0) {
        const currentChunkIndex = chunkCounter++;

        const chunkData = {
            uuid: videoUuid,
            index: currentChunkIndex,
            blob: event.data,
            retries: 0
        };

        // ذخیره در IndexedDB (برای پایداری)
        // کلید طوری ساخته شده که ترتیب داشته باشد
        await localforage.setItem(`chunk_${String(currentChunkIndex).padStart(5, '0')}`, chunkData);

        // فراخوانی آپلودر
        processUploadQueue();
    }
};

// --- 3. توقف ضبط ---
const stopRecording = () => {
    if (mediaRecorder.value && state.value === 'recording') {
        // فراخوانی stop باعث می‌شود آخرین تکه دیتا (Final Blob) تولید شود
        // و متد ondataavailable برای آخرین بار صدا زده شود
        mediaRecorder.value.stop();
        isRecording.value = false;
        stopStream();
        stopTimer();

        // تغییر وضعیت به "در حال آپلود"
        // ما هنوز finish نمی‌زنیم، صبر می‌کنیم صف خالی شود
        state.value = 'uploading';
    }
};

const cancelRecording = async () => {
    const confirm = await $confirm('آیا از لغو ضبط ویدیو اطمینان دارید؟ تمام ویدیوی ضبط شده حذف خواهد شد.')
    if (confirm) {
        // Stop any ongoing recording
        if (mediaRecorder.value && state.value === 'recording') {
            mediaRecorder.value.stop();
        }

        // Reset all states
        state.value = 'idle';
        isRecording.value = false;
        isLoading.value = false;
        timer.value = 0;
        stopTimer();

        // Clear any pending chunks
        try {
            const keys = await localforage.keys();
            await Promise.all(keys.map(key => localforage.removeItem(key)));
            pendingChunksCount.value = 0;
            uploadProgress.value = 0;
        } catch (error) {
            console.error('Error clearing local storage:', error);
        }

        // Stop all tracks in the stream
        if (stream.value) {
            stream.value.getTracks().forEach(track => track.stop());
            stream.value = null;
        }

        // Reinitialize the camera
        initializeCamera();
    }
};

const initializeCamera = async () => {
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

// --- 4. صف آپلود (Worker) ---
const processUploadQueue = async () => {
    if (isUploaderRunning) return; // جلوگیری از اجرای همزمان
    isUploaderRunning = true;

    try {
        // دریافت همه کلیدها
        const keys = await localforage.keys();
        pendingChunksCount.value = keys.length;

        // اگر کلیدی هست، یعنی چیزی برای آپلود داریم
        if (keys.length > 0) {
            // مرتب‌سازی کلیدها برای رعایت ترتیب (حیاتی)
            const sortedKeys = keys.sort();
            const nextKey = sortedKeys[0];
            const chunkData = await localforage.getItem(nextKey);

            if (chunkData) {
                // آپلود چانک
                await uploadChunkToServer(chunkData);

                // حذف از دیتابیس بعد از موفقیت
                await localforage.removeItem(nextKey);

                // محاسبه درصد پیشرفت (تخمینی)
                // فرمول دقیق‌تر نیاز به دانستن تعداد کل دارد که هنوز نمی‌دانیم، پس نمایشی است
                uploadProgress.value = Math.min(95, uploadProgress.value + 5);
            }

            // فراخوانی بازگشتی برای چانک بعدی
            isUploaderRunning = false;
            processUploadQueue();

        } else {
            // صف خالی است. حالا چک می‌کنیم که آیا ضبط تمام شده؟
            if (state.value === 'uploading') {
                // صف خالی شده و کاربر دکمه توقف را زده است -> پایان کار
                await finishUpload();
            }
            isUploaderRunning = false;
        }
    } catch (err) {
        console.error("Upload Loop Error:", err);
        // در صورت خطا، بعد از ۲ ثانیه دوباره تلاش کن
        setTimeout(() => {
            isUploaderRunning = false;
            processUploadQueue();
        }, 2000);
    }
};

// --- 5. آپلود یک تکه به سرور ---
const uploadChunkToServer = async (chunkData) => {
    const formData = new FormData();
    formData.append('uuid', chunkData.uuid);
    formData.append('chunk_index', chunkData.index);
    formData.append('chunk', chunkData.blob);

    // استفاده از Axios برای آپلود
    await axios.post(route('web.video.chunk'), formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    });
};

// --- 6. پایان نهایی ---
const finishUpload = async () => {
    state.value = 'processing';
    try {
        await axios.post(route('web.video.finish'), {
            uuid: videoUuid,
            total_chunks: chunkCounter // تعداد کل چانک‌های تولید شده
        });

        state.value = 'completed';
        uploadProgress.value = 100;

        // پاکسازی نهایی دیتابیس
        await localforage.clear();

    } catch (err) {
        console.error(err);
        errorMessage.value = 'خطا در پردازش نهایی ویدیو.';
        state.value = 'idle'; // اجازه تلاش مجدد
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
onMounted(async () => {
    // Initialize camera access
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
});

onBeforeUnmount(() => {
    stopStream();
    stopTimer();
});
</script>
