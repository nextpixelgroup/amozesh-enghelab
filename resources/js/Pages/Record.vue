<template>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow-lg">
        <!-- هدر و وضعیت -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">ضبط ویدیو</h2>
            <div class="text-sm font-mono">
                <span v-if="state === 'recording'" class="text-red-600 animate-pulse">● ضبط: {{ formatTime(timer) }}</span>
                <span v-else-if="state === 'uploading'" class="text-blue-600">در حال آپلود... ({{ uploadProgress }}%)</span>
                <span v-else-if="state === 'processing'" class="text-yellow-600">در حال پردازش نهایی...</span>
                <span v-else-if="state === 'completed'" class="text-green-600">آپلود موفقیت‌آمیز!</span>
                <span v-else class="text-gray-500">آماده ضبط</span>
            </div>
        </div>

        <!-- کادر نمایش ویدیو -->
        <div class="relative aspect-video bg-black rounded-lg overflow-hidden mb-4">
            <video ref="videoPreview" autoplay muted playsinline class="w-full h-full object-cover"></video>

            <!-- لایه محافظ هنگام آپلود -->
            <div v-if="state === 'uploading' || state === 'processing'" class="absolute inset-0 bg-black/70 flex items-center justify-center flex-col text-white">
                <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p>لطفاً صبر کنید...</p>
                <p class="text-sm opacity-70 mt-2">چانک‌های باقی‌مانده: {{ pendingChunksCount }}</p>
            </div>
        </div>

        <!-- دکمه‌های کنترل -->
        <div class="flex gap-4">
            <button
                v-if="state === 'idle' || state === 'completed'"
                @click="startRecording"
                class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-bold transition flex items-center justify-center gap-2"
            >
                <span>شروع ضبط</span>
            </button>

            <button
                v-if="state === 'recording'"
                @click="stopRecording"
                class="flex-1 bg-gray-800 hover:bg-gray-900 text-white py-3 rounded-lg font-bold transition"
            >
                توقف و ارسال
            </button>
        </div>

        <!-- نمایش خطا -->
        <div v-if="errorMessage" class="mt-4 p-3 bg-red-100 text-red-700 rounded border border-red-200 text-sm">
            {{ errorMessage }}
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import localforage from 'localforage';
import {route} from "ziggy-js";

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
            mimeType: 'video/webm;codecs=vp9,opus',  // Using VP9 for better compression
            videoBitsPerSecond: 1500000,  // 1.5 Mbps (good balance for 720p)
            audioBitsPerSecond: 48000,    // 48 Kbps (good quality for voice)
            audioChannelCount: 1,         // Mono audio
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true,
                channelCount: 1,
                sampleRate: 24000,        // Lower sample rate
                sampleSize: 16
            },
            video: {
                width: 1280,
                height: 720,
                frameRate: {
                    ideal: 24,            // 24fps is sufficient for most cases
                    max: 30
                },
                // Advanced constraints for better compression
                advanced: [{
                    width: 1280,
                    height: 720,
                    aspectRatio: 16/9,
                    frameRate: { max: 24 }
                }]
            }
        };

        // ایجاد MediaRecorder با تنظیمات
        mediaRecorder.value = new MediaRecorder(stream.value, options);

        // هندلر دریافت داده (هر ۵ ثانیه یا موقع توقف)
        mediaRecorder.value.ondataavailable = handleDataAvailable;

        // شروع ضبط با تکه‌های ۵ ثانیه‌ای
        const chunkDuration = 5000; // 5 seconds
        mediaRecorder.value.start(chunkDuration);

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
        stopStream();
        stopTimer();

        // تغییر وضعیت به "در حال آپلود"
        // ما هنوز finish نمی‌زنیم، صبر می‌کنیم صف خالی شود
        state.value = 'uploading';
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
onMounted(() => {
    // اینجا می‌توانید کدی برای "ادامه آپلود" در صورت رفرش شدن صفحه بنویسید
    // با چک کردن localforage.keys()
});

onBeforeUnmount(() => {
    stopStream();
    stopTimer();
});
</script>
