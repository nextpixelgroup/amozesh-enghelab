<template>
    <div class="video-recorder p-6 max-w-4xl mx-auto">

        <!-- وضعیت سیستم -->
        <div v-if="recoveryMode" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p class="font-bold">بازیابی اطلاعات</p>
            <p>یک ویدیوی نیمه‌کاره پیدا شد. در حال آپلود خودکار باقیمانده فایل‌ها...</p>
        </div>

        <!-- بخش نمایش ویدیو -->
        <div class="relative w-full aspect-video bg-black rounded-xl overflow-hidden shadow-2xl border border-gray-800">
            <video ref="preview" autoplay muted playsinline class="w-full h-full object-cover transform -scale-x-100"></video>

            <!-- Overlay تایمر -->
            <div v-if="isRecording" class="absolute top-4 right-4 flex items-center gap-2 bg-red-600/90 text-white px-4 py-1.5 rounded-full backdrop-blur-sm shadow-lg">
                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                <span class="font-mono font-bold text-lg">{{ formattedTime }}</span>
            </div>

            <!-- Overlay وضعیت آپلود -->
            <div v-if="pendingChunks > 0" class="absolute bottom-4 left-4 bg-black/60 text-white px-3 py-1 rounded text-xs backdrop-blur-sm">
                <span v-if="!isOnline" class="text-red-400 font-bold mr-1">⚠️ آفلاین</span>
                <span>{{ pendingChunks }} بسته در صف آپلود</span>
            </div>
        </div>

        <!-- دکمه‌های کنترل -->
        <div class="flex flex-col items-center gap-4 mt-8">

            <div class="flex gap-4">
                <button
                    v-if="!isRecording"
                    @click="startRecording"
                    class="group relative inline-flex items-center justify-center px-8 py-3 text-lg font-medium text-white transition-all duration-200 bg-blue-600 rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="isProcessing || recoveryMode"
                >
                    <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                    <span class="relative flex items-center gap-2">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.818v6.364a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
             شروع ضبط
          </span>
                </button>

                <button
                    v-else
                    @click="stopRecording"
                    class="group relative inline-flex items-center justify-center px-8 py-3 text-lg font-medium text-white transition-all duration-200 bg-red-600 rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600"
                >
          <span class="relative flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" /></svg>
            پایان ضبط
          </span>
                </button>
            </div>

            <!-- وضعیت متنی -->
            <div class="h-6 text-sm font-medium text-gray-600 transition-all duration-300">
                {{ statusMessage }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import localforage from 'localforage';
import axios from 'axios';
import { v4 as uuidv4 } from 'uuid';
import {route} from "ziggy-js";

// --- تنظیمات حیاتی ---
const VIDEO_CONFIG = {
    mimeType: 'video/webm;codecs=vp8,opus',
    videoBitsPerSecond: 1000000, // 1 Mbps
    width: 1280,
    height: 720
};
const CHUNK_DURATION = 1000; // هر 1 ثانیه تکه کن
const BATCH_SIZE = 10; // هر 10 تکه یکبار ذخیره کن

// --- State Variables ---
const preview = ref(null);
const isRecording = ref(false);
const isProcessing = ref(false);
const recoveryMode = ref(false); // حالت بازیابی ویدیوی قبلی
const recordingTime = ref(0);
const statusMessage = ref('آماده برای ضبط');
const pendingChunks = ref(0);
const isOnline = ref(navigator.onLine);

// --- Internal Variables ---
let mediaRecorder = null;
let stream = null;
let timerInterval = null;
let currentVideoId = null;
let chunkSequence = 0;
let localBuffer = []; // بافر موقت رم قبل از ریختن در DB
let uploaderInterval = null;

// --- Config LocalForage ---
localforage.config({
    name: 'VideoRecorderDB',
    storeName: 'upload_queue'
});

// ==========================================================
// Lifecycle Hooks
// ==========================================================
onMounted(async () => {
    // گوش دادن به وضعیت اینترنت
    window.addEventListener('online', () => { isOnline.value = true; });
    window.addEventListener('offline', () => { isOnline.value = false; });

    // جلوگیری از بستن تب اگر آپلود مانده است
    window.addEventListener('beforeunload', handleBeforeUnload);

    // بررسی فایل‌های جامانده از قبل (Recovery Mode)
    await checkPendingUploads();
});

onUnmounted(() => {
    if (uploaderInterval) clearInterval(uploaderInterval);
    window.removeEventListener('beforeunload', handleBeforeUnload);
    if (stream) stream.getTracks().forEach(track => track.stop());
});

const handleBeforeUnload = (e) => {
    if (pendingChunks.value > 0 || isRecording.value) {
        e.preventDefault();
        e.returnValue = ''; // استاندارد مرورگر برای نمایش هشدار
    }
};

// ==========================================================
// Core Recording Functions
// ==========================================================
const startRecording = async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            audio: true,
            video: {
                width: { ideal: VIDEO_CONFIG.width },
                height: { ideal: VIDEO_CONFIG.height },
                frameRate: { ideal: 24 }
            }
        });

        preview.value.srcObject = stream;

        // ایجاد ID جدید
        currentVideoId = uuidv4();

        // ذخیره ID در LocalStorage برای اطلاع از اینکه کدام ویدیو فعال است
        localStorage.setItem('active_video_id', currentVideoId);

        chunkSequence = 0;
        localBuffer = [];
        recordingTime.value = 0;

        // اطلاع به سرور
        await axios.post(route('web.video.init'), { video_id: currentVideoId });

        mediaRecorder = new MediaRecorder(stream, VIDEO_CONFIG);
        mediaRecorder.ondataavailable = handleDataAvailable;
        mediaRecorder.start(CHUNK_DURATION);

        isRecording.value = true;
        statusMessage.value = 'در حال ضبط...';
        startTimer();

        // اطمینان از روشن بودن آپلودر
        startUploaderWorker();

    } catch (error) {
        console.error('Camera Error:', error);
        alert('دسترسی به دوربین امکان‌پذیر نیست.');
    }
};

const stopRecording = async () => {
    if (!mediaRecorder) return;

    isProcessing.value = true;
    mediaRecorder.stop(); // این باعث تریگر شدن آخرین dataavailable می‌شود
    stopTimer();

    // بستن دوربین
    stream.getTracks().forEach(track => track.stop());
    preview.value.srcObject = null;
    isRecording.value = false;

    // فلاش کردن باقیمانده بافر به DB
    await saveBatchToDB(true); // true = force save

    statusMessage.value = 'ضبط تمام شد. آپلود در پس‌زمینه ادامه دارد...';

    // پاک کردن فلگ ویدیوی فعال چون ضبط تمام شده (آپلود شاید مانده باشد)
    localStorage.removeItem('active_video_id');
};

// ==========================================================
// Data Handling & Storage
// ==========================================================
const handleDataAvailable = async (event) => {
    if (event.data && event.data.size > 0) {
        localBuffer.push(event.data);

        // اگر بافر پر شد، ذخیره کن
        if (localBuffer.length >= BATCH_SIZE) {
            await saveBatchToDB();
        }
    }
};

const saveBatchToDB = async (force = false) => {
    if (localBuffer.length === 0) return;
    if (!force && localBuffer.length < BATCH_SIZE) return;

    const blob = new Blob(localBuffer, { type: 'video/webm' });

    // ساخت کلید ترکیبی که هم یکتا باشد هم قابل سورت شدن
    // videoId + sequence
    const itemKey = `${currentVideoId}_${String(chunkSequence).padStart(5, '0')}`;

    const batchData = {
        id: itemKey,
        videoId: currentVideoId,
        sequence: chunkSequence,
        blob: blob,
        timestamp: Date.now()
    };

    await localforage.setItem(itemKey, batchData);

    localBuffer = [];
    chunkSequence++;
    await updatePendingCount();
};

// ==========================================================
// Background Uploader Worker
// ==========================================================
const checkPendingUploads = async () => {
    await updatePendingCount();
    if (pendingChunks.value > 0) {
        recoveryMode.value = true;
        startUploaderWorker();
    }
};

const startUploaderWorker = () => {
    if (uploaderInterval) return; // اگر روشن است، دوباره نساز

    console.log("Uploader Worker Started");

    uploaderInterval = setInterval(async () => {
        // شرط توقف: اگر اینترنت نیست، کاری نکن
        if (!navigator.onLine) {
            statusMessage.value = 'اینترنت قطع است. در انتظار اتصال...';
            return;
        }

        try {
            const keys = await localforage.keys();

            // اگر صف خالی است
            if (keys.length === 0) {
                if (recoveryMode.value) {
                    recoveryMode.value = false;
                    statusMessage.value = 'آپلودهای معوقه تکمیل شد.';
                    // اینجا ممکن است نیاز باشد فینیش را برای ویدیوی ریکاوری شده صدا بزنید
                    // اما چون total_chunks را نداریم، بهتر است سرور تایم‌اوت هندل کند
                    // یا آخرین sequence را ذخیره کنیم. برای سادگی اینجا رد می‌شویم.
                }

                // اگر ضبط تمام شده و صف هم خالی است، فینیش را بزن
                if (!isRecording.value && isProcessing.value) {
                    await finishUploadProcess();
                }
                return;
            }

            // مرتب‌سازی کلیدها تا به ترتیب ارسال شوند
            keys.sort();
            const key = keys[0];
            const item = await localforage.getItem(key);

            if (!item) {
                await localforage.removeItem(key); // آیتم خراب
                return;
            }

            statusMessage.value = `در حال آپلود بخش ${item.sequence + 1}...`;

            const formData = new FormData();
            formData.append('video_id', item.videoId);
            formData.append('sequence', item.sequence);
            formData.append('file', item.blob);

            await axios.post(route('web.video.uploadChunk'), formData);

            // موفقیت: حذف از DB
            await localforage.removeItem(key);
            await updatePendingCount();

        } catch (error) {
            console.error('Upload Retry Error:', error);
            // اینجا می‌توان منطق Exponential Backoff گذاشت
        }
    }, 2000); // هر 2 ثانیه تلاش کن
};

const finishUploadProcess = async () => {
    // جلوگیری از اجرای چندباره
    if(pendingChunks.value > 0) return;

    // توقف ورکر موقت
    clearInterval(uploaderInterval);
    uploaderInterval = null;

    try {
        // برای اطمینان از اینکه کدام ویدیو را فینیش می‌زنیم
        // در حالت ضبط عادی currentVideoId داریم
        if (currentVideoId) {
            await axios.post(route('web.video.finish'), {
                video_id: currentVideoId,
                total_chunks: chunkSequence
            });
            statusMessage.value = 'ویدیو با موفقیت ذخیره و ارسال شد.';
            alert('عملیات با موفقیت انجام شد!');
        }

        isProcessing.value = false;

    } catch (e) {
        console.error("Finish Error:", e);
        // اگر فیل شد، دوباره ورکر را روشن کن شاید نت قطع شده
        startUploaderWorker();
    }
}

// ==========================================================
// Helpers
// ==========================================================
const updatePendingCount = async () => {
    pendingChunks.value = await localforage.length();
}

const startTimer = () => {
    timerInterval = setInterval(() => {
        recordingTime.value++;
    }, 1000);
};

const stopTimer = () => {
    clearInterval(timerInterval);
};

const formattedTime = computed(() => {
    const m = Math.floor(recordingTime.value / 60).toString().padStart(2, '0');
    const s = (recordingTime.value % 60).toString().padStart(2, '0');
    return `${m}:${s}`;
});
</script>
