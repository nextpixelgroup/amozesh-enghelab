<template>
    <div ref="videoContainer" class="video-container" @mousemove="handleControlInteraction">

        <!-- Overlay Play Button -->
        <div
            v-if="!playing && !isBuffering"
            class="overlay-play"
            @click="playing = true"
        >
            <v-icon size="64" color="white" class="play-icon-pulse">mdi-play</v-icon>
        </div>

        <video
            ref="video"
            class="video-player"
            :poster="poster"
            @click="playing = !playing"
            @waiting="isBuffering = true"
            @playing="isBuffering = false"
            @canplay="isBuffering = false"
            @progress="updateBuffered"
            :autoplay="autoplay"
            :muted="autoplay"
        ></video>

        <!-- Loading Overlay -->
        <div v-if="isBuffering" class="loading-overlay">
            <v-progress-circular
                indeterminate
                color="#00e676"
                size="64"
                width="4"
            />
        </div>

        <!-- Controls -->
        <div class="video-controls" :class="{ 'controls-hidden': !showControls }" @mousemove="handleControlInteraction">
            <div class="controls-wrapper">

                <!-- Progress Bar -->
                <div class="progress-container">
                    <!--
                        تغییر ۱: حذف @click="onProgressBarClick" از اینجا.
                        چون input داخلی خودش کلیک را مدیریت می‌کند.
                    -->
                    <div class="progress-bar">
                        <!-- Buffered ranges -->
                        <div
                            v-for="(range, index) in bufferedRanges"
                            :key="index"
                            class="progress-buffered"
                            :style="{
                                left: range.left + '%',
                                width: range.width + '%'
                            }"
                        ></div>

                        <!-- Played -->
                        <div
                            class="progress-played"
                            :style="{ width: progressPercentage + '%' }"
                        ></div>

                        <!-- Thumb (Circle) -->
                        <div
                            class="progress-thumb"
                            :style="{ left: progressPercentage + '%' }"
                        ></div>

                        <!-- Invisible slider input -->
                        <input
                            type="range"
                            v-model="currentTime"
                            :max="duration"
                            step="0.1"
                            @input="onSeek"
                            class="progress-slider"
                        />
                    </div>
                </div>

                <!-- Buttons Row -->
                <div class="controls-row">
                    <!-- ... بقیه کدها بدون تغییر ... -->
                    <div class="controls-right">

                        <button @click="skip(-10)" class="control-btn mini-btn" title="10 ثانیه قبل">
                            <v-icon color="white" size="20">mdi-rewind-10</v-icon>
                        </button>

                        <button @click="playing = !playing" class="control-btn play-pause-btn">
                            <v-icon color="white" size="28">
                                {{ playing ? 'mdi-pause' : 'mdi-play' }}
                            </v-icon>
                        </button>

                        <button @click="skip(10)" class="control-btn mini-btn" title="10 ثانیه بعد">
                            <v-icon color="white" size="20">mdi-fast-forward-10</v-icon>
                        </button>

                        <div class="time-display">
                            <span>{{ formatTime(currentTime) }}</span>
                            <span class="time-separator">/</span>
                            <span>{{ formatTime(duration) }}</span>
                        </div>

                        <div class="volume-control">
                            <!-- تغییرات در این تگ اعمال شد -->
                            <v-icon
                                color="white"
                                size="22"
                                class="mr-1"
                                style="cursor: pointer;"
                                @click="toggleMute"
                            >
                                {{ volume === 0 ? 'mdi-volume-off' : 'mdi-volume-high' }}
                            </v-icon>

                            <div class="volume-slider-wrapper">
                                <input
                                    type="range"
                                    min="0"
                                    max="1"
                                    step="0.05"
                                    v-model="volume"
                                    class="volume-slider"
                                    dir="ltr"
                                    :style="{ backgroundSize: (volume * 100) + '% 100%' }"
                                />
                            </div>
                        </div>


                    </div>
                    <div class="controls-left">

                        <v-menu
                            location="top"
                            offset="10"
                            :attach="videoContainer"
                            content-class="speed-menu-container"
                            :close-on-content-click="false"
                            transition="slide-y-transition"
                        >
                            <template #activator="{ props }">
                                <button v-bind="props" class="control-btn" @click.stop title="سرعت پخش">
                                    <span class="speed-text">{{ rate }}x</span>
                                </button>
                            </template>
                            <v-list class="speed-menu">
                                <v-list-item
                                    v-for="speed in speeds"
                                    :key="speed"
                                    @click="setPlaybackSpeed(speed)"
                                    :class="{ active: rate === speed }"
                                    class="speed-item"
                                    density="compact"
                                >
                                    <v-list-item-title class="text-center">{{ speed }}x</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                        <a
                            :href="lesson.download_url"
                            class="control-btn"
                            title="دانلود ویدیو"
                            @click="handleDownload"
                        >
                            <v-progress-circular
                                v-if="isDownloading"
                                indeterminate
                                size="20"
                                width="2"
                                color="#00e676"
                            />
                            <v-icon v-else color="white" size="24">mdi-download</v-icon>
                        </a>

                        <button @click="toggleFullscreen" class="control-btn" title="تمام صفحه">
                            <v-icon color="white" size="24">
                                {{ isFullscreen ? 'mdi-fullscreen-exit' : 'mdi-fullscreen' }}
                            </v-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useFullscreen, useMediaControls} from '@vueuse/core'
import {ref, onMounted, computed, onBeforeUnmount, watch} from 'vue'
import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    src : { type: String, required: true },
    poster : { type: String, required: true },
    filename: { type: String, required: true },
    autoplay: { type: Boolean, default: false },
    lesson: { type: Object, default: null }
});

const video = ref(null)
const isBuffering = ref(false)
const isDownloading = ref(false)
const bufferedTimeRanges = ref(null)

// تغییر ۲: اضافه کردن seeking به destructuring
const {
    playing,
    currentTime,
    duration,
    volume,
    buffered,
    rate,
    seeking // <--- این خیلی مهم است
} = useMediaControls(video, {
    src: props.src,
})

const previousVolume = ref(1) // مقدار پیش‌فرض ۱۰۰٪ است

const toggleMute = () => {
    if (volume.value > 0) {
        // اگر صدا باز است، مقدار فعلی را ذخیره کن و صدا را قطع کن
        previousVolume.value = volume.value
        volume.value = 0
    } else {
        // اگر صدا قطع است، به مقدار ذخیره شده برگرد
        // اگر مقدار قبلی هم صفر بود (باگ احتمالی)، روی ۱ تنظیم کن
        volume.value = previousVolume.value > 0 ? previousVolume.value : 1
    }
}

const videoContainer = ref(null)
const { isFullscreen, toggle: toggleFullscreen } = useFullscreen(videoContainer)
const showControls = ref(true)
let controlsTimeout
const speeds = [0.75, 1, 1.5, 2, 3]

const updateBuffered = () => {
    if (!video.value) return
    bufferedTimeRanges.value = video.value.buffered
}

// ... توابع مربوط به کنترل‌ها و فول اسکرین دست نخورده ...
const hideControls = () => {
    if (isFullscreen.value && playing.value) {
        showControls.value = false
        document.body.style.cursor = 'none'
    }
}
const showControlsWithTimer = () => {
    showControls.value = true
    document.body.style.cursor = 'auto'
    if (controlsTimeout) clearTimeout(controlsTimeout)
    controlsTimeout = setTimeout(hideControls, 3000)
}
const handleControlInteraction = () => { showControlsWithTimer() }
const handleUserActivity = (e) => {
    if (e.target.closest('.speed-menu-container') || e.target.closest('.speed-menu')) return
    showControlsWithTimer()
}
const setPlaybackSpeed = (speed) => {
    rate.value = speed
    showControlsWithTimer()
}
watch(isFullscreen, (newVal) => {
    if (newVal) {
        showControlsWithTimer()
        window.addEventListener('mousemove', handleUserActivity)
        window.addEventListener('mousedown', handleUserActivity)
        window.addEventListener('keydown', handleUserActivity)
    } else {
        if (controlsTimeout) clearTimeout(controlsTimeout)
        showControls.value = true
        document.body.style.cursor = 'auto'
        window.removeEventListener('mousemove', handleUserActivity)
        window.removeEventListener('mousedown', handleUserActivity)
        window.removeEventListener('keydown', handleUserActivity)
    }
}, { immediate: true })

onBeforeUnmount(() => {
    if (controlsTimeout) clearTimeout(controlsTimeout)
    window.removeEventListener('mousemove', handleUserActivity)
    window.removeEventListener('mousedown', handleUserActivity)
    window.removeEventListener('keydown', handleUserActivity)
    document.body.style.cursor = 'auto'
})

// --- Computed Properties ---
const bufferedRanges = computed(() => {
    if (!bufferedTimeRanges.value || duration.value === 0) return []
    const ranges = []
    const timeRanges = bufferedTimeRanges.value
    for (let i = 0; i < timeRanges.length; i++) {
        const start = timeRanges.start(i)
        const end = timeRanges.end(i)
        ranges.push({
            left: (start / duration.value) * 100,
            width: ((end - start) / duration.value) * 100
        })
    }
    return ranges
})

const progressPercentage = computed(() => {
    if (!duration.value) return 0
    return (currentTime.value / duration.value) * 100
})

const formatTime = (time) => {
    if (!time && time !== 0) return '0:00'
    const hours = Math.floor(time / 3600)
    const minutes = Math.floor((time % 3600) / 60)
    const seconds = Math.floor(time % 60)
    if (hours > 0) {
        return `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
    }
    return `${minutes}:${seconds.toString().padStart(2, '0')}`
}

const onSeek = (e) => {
    // برای اطمینان بیشتر، وقتی کاربر اسلایدر را میکشد این کد اجرا میشود
    // v-model خودش کار میکند اما این برای لاجیک های اضافه است
    currentTime.value = Number(e.target.value)
}

// تغییر ۳: تابع onProgressBarClick کاملا حذف شد چون باعث تداخل بود

const seekStep = 10

const handleDownload = (e) => {
    isDownloading.value = true
    const timer = setTimeout(() => { isDownloading.value = false }, 2000)
    const onDownloadStart = () => {
        clearTimeout(timer)
        isDownloading.value = false
        window.removeEventListener('blur', onDownloadStart)
    }
    window.addEventListener('blur', onDownloadStart)
}

const handleKey = (e) => {
    if (!video.value) return
    switch (e.key) {
        case ' ':
        case 'k':
            e.preventDefault()
            playing.value = !playing.value
            break
        case 'ArrowLeft':
            // تغییر: استفاده از currentTime.value
            currentTime.value = Math.max(0, currentTime.value - seekStep)
            break
        case 'ArrowRight':
            // تغییر: استفاده از currentTime.value
            currentTime.value = Math.min(duration.value, currentTime.value + seekStep)
            break
        case 'ArrowUp':
            e.preventDefault()
            volume.value = Math.min(1, volume.value + 0.05)
            break
        case 'ArrowDown':
            e.preventDefault()
            volume.value = Math.max(0, volume.value - 0.05)
            break
        case 'f':
            toggleFullscreen()
            break
    }
}


const skip = (seconds) => {
    // تغییر: به جای video.value.currentTime از currentTime.value استفاده می‌کنیم
    // این کار باعث می‌شود اسلایدر بلافاصله جابجا شود حتی اگر ویدیو لود نشده باشد
    currentTime.value = Math.min(Math.max(0, currentTime.value + seconds), duration.value)
}

onMounted(() => {
    volume.value = 1
    currentTime.value = 0
    window.addEventListener('keydown', handleKey)
    window.addEventListener('blur', handleDownloadComplete)
})

const handleDownloadComplete = () => { isDownloading.value = false }

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKey)
    window.removeEventListener('blur', handleDownloadComplete)
})

const hasReportedEnd = ref(false)

// تغییر ۴: اصلاح منطق Watcher
watch(currentTime, (time) => {
    if (props.lesson?.completed === true) return

    // اگر کاربر در حال جابجایی دستی (Seeking) است، اصلا بررسی نکن
    if (seeking.value) return

    if (duration.value > 0 && !hasReportedEnd.value && props.lesson.id) {
        const remaining = duration.value - time

        // شرط اضافه: اطمینان حاصل کن که remaining منطقی است و پرش لحظه ای نیست
        if (remaining <= 2) {
            hasReportedEnd.value = true
            router.post(route('web.courses.lesson.completed', props.lesson.id), [], {
                preserveScroll: true,
                preserveState: true,
            })
        }
    }
})

watch(currentTime, (time, oldTime) => {
    if (props.lesson?.completed === true) return

    // اگر کاربر دستی به عقب برگشت (نه باگ سیستم)، فلگ را ریست کن
    // اضافه کردن شرط !seeking.value برای اطمینان بیشتر
    if (time < oldTime && hasReportedEnd.value && props.lesson.id && !seeking.value) {
        // یک فاصله ایمنی: فقط اگر فاصله معناداری از پایان ویدیو داریم ریست کن
        if (duration.value - time > 5) {
            hasReportedEnd.value = false
        }
    }
})
</script>

<style scoped>
/* استایل‌ها همان هستند */
/* --- Main Variables --- */
:root {
    --primary-green: #00e676;
    --primary-glow: rgba(0, 230, 118, 0.4);
    --glass-bg: rgba(20, 20, 20, 0.75);
    --hover-bg: rgba(255, 255, 255, 0.1);
}
.video-container {
    position: relative;
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
    background-color: #000;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    aspect-ratio: 16 / 9;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    group: video-group;
}
.video-player {
    width: 100%;
    height: 100%;
    display: block;
    cursor: pointer;
    object-fit: contain;
}
.video-controls {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 0 15px 10px 15px;
    background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.6) 60%, transparent 100%);
    z-index: 10;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    backdrop-filter: blur(2px);
    direction: ltr;
    text-align: left;
}
.video-controls.controls-hidden {
    opacity: 0;
    transform: translateY(20px);
    pointer-events: none;
}
.controls-wrapper {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.controls-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    height: 40px;
}
.controls-left, .controls-right {
    display: flex;
    align-items: center;
    gap: 8px;
}
.control-btn {
    background: transparent;
    border: none;
    color: white;
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s, transform 0.1s;
}
.control-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #00e676;
}
.control-btn:hover .v-icon {
    color: #00e676 !important;
}
.control-btn:active {
    transform: scale(0.95);
}
.speed-text {
    font-size: 13px;
    font-weight: 600;
    color: white;
}
.control-btn:hover .speed-text {
    color: #00e676;
}
.progress-container {
    width: 100%;
    height: 16px;
    display: flex;
    align-items: center;
    cursor: pointer;
}
.progress-bar {
    position: relative;
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    transition: height 0.1s ease;
}
.progress-container:hover .progress-bar {
    height: 6px;
}
.progress-buffered {
    /*position: absolute;
    top: 0;
    height: 100%;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 2px;
    pointer-events: none;
    z-index: 1;*/
}
.progress-played {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: #00e676;
    border-radius: 2px;
    pointer-events: none;
    z-index: 2;
    box-shadow: 0 0 10px rgba(0, 230, 118, 0.5);
}
.progress-thumb {
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 14px;
    height: 14px;
    background: #fff;
    border-radius: 50%;
    z-index: 5;
    pointer-events: none;
    transition: transform 0.15s ease;
    box-shadow: 0 0 0 3px rgba(0, 230, 118, 0.3);
}
.progress-container:hover .progress-thumb {
    transform: translate(-50%, -50%) scale(1);
}
.progress-slider {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
    margin: 0;
}
.volume-control {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 5px;
}
.volume-slider-wrapper {
    width: 80px;
    height: 30px;
    display: flex;
    align-items: center;
}
.volume-slider {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    outline: none;
    cursor: pointer;
    background-image: linear-gradient(#00e676, #00e676);
    background-repeat: no-repeat;
}
.volume-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 12px;
    height: 12px;
    background: #fff;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 0 5px rgba(0, 230, 118, 0.8);
    transition: transform 0.1s;
}
.volume-slider::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}
.volume-slider::-moz-range-thumb {
    width: 12px;
    height: 12px;
    background: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 0 5px rgba(0, 230, 118, 0.8);
    transition: transform 0.1s;
}
.volume-slider::-moz-range-thumb:hover {
    transform: scale(1.2);
}
.time-display {
    font-size: 13px;
    font-family: monospace;
    color: #eee;
    margin: 0 8px;
    font-weight: 500;
}
.time-separator {
    margin: 0 4px;
    opacity: 0.6;
}
.overlay-play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70px;
    height: 70px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    backdrop-filter: blur(4px);
    border: 2px solid rgba(255, 255, 255, 0.1);
    transition: all 0.2s ease;
    z-index: 5;
}
.overlay-play:hover {
    background: #00e676;
    transform: translate(-50%, -50%) scale(1.1);
    border-color: #00e676;
}
.overlay-play:hover .v-icon {
    color: black !important;
}
.loading-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 4;
    backdrop-filter: blur(2px);
}
.speed-menu-container {
    z-index: 9999 !important;
}
.speed-menu {
    background: rgba(20, 20, 20, 0.95) !important;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px !important;
    overflow: hidden;
    padding: 4px !important;
}
.speed-item {
    color: #ccc !important;
    border-radius: 4px !important;
    margin-bottom: 2px;
    transition: background 0.2s;
}
.speed-item:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    color: white !important;
}
.speed-item.active {
    background: #00e676 !important;
    color: black !important;
    font-weight: 700;
}
:fullscreen .video-container {
    width: 100%;
    height: 100%;
    max-width: none;
    border-radius: 0;
    background: black;
}
:fullscreen .video-player {
    object-fit: contain;
}
:fullscreen .video-controls {
    padding-bottom: 25px;
    background: linear-gradient(to top, rgba(0,0,0,0.95), transparent);
}
</style>
