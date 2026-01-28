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
                    <div class="progress-bar" @click="onProgressBarClick">
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
                    <div class="controls-left">
                        <!-- Fullscreen -->
                        <button @click="toggleFullscreen" class="control-btn" title="تمام صفحه">
                            <v-icon color="white" size="24">
                                {{ isFullscreen ? 'mdi-fullscreen-exit' : 'mdi-fullscreen' }}
                            </v-icon>
                        </button>

                        <!-- Download -->
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

                        <!-- Speed -->
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
                    </div>

                    <div class="controls-right">
                        <!-- Volume -->
                        <div class="volume-control">
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
                            <v-icon color="white" size="22" class="mr-1">
                                {{ volume === 0 ? 'mdi-volume-off' : 'mdi-volume-high' }}
                            </v-icon>
                        </div>

                        <!-- Time -->
                        <div class="time-display">
                            <span>{{ formatTime(currentTime) }}</span>
                            <span class="time-separator">/</span>
                            <span>{{ formatTime(duration) }}</span>
                        </div>

                        <button @click="skip(10)" class="control-btn mini-btn" title="10 ثانیه بعد">
                            <v-icon color="white" size="20">mdi-fast-forward-10</v-icon>
                        </button>


                        <!-- Play/Pause (Small) -->
                        <button @click="playing = !playing" class="control-btn play-pause-btn">
                            <v-icon color="white" size="28">
                                {{ playing ? 'mdi-pause' : 'mdi-play' }}
                            </v-icon>
                        </button>
                        <!-- Skip Buttons -->
                        <button @click="skip(-10)" class="control-btn mini-btn" title="10 ثانیه قبل">
                            <v-icon color="white" size="20">mdi-rewind-10</v-icon>
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

const {
    playing,
    currentTime,
    duration,
    volume,
    buffered,
    rate
} = useMediaControls(video, {
    src: props.src,
})

const videoContainer = ref(null)
const { isFullscreen, toggle: toggleFullscreen } = useFullscreen(videoContainer)
const showControls = ref(true)
let controlsTimeout
const speeds = [0.75, 1, 1.5, 2, 3]

const updateBuffered = () => {
    if (!video.value) return
    bufferedTimeRanges.value = video.value.buffered
}

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

const handleControlInteraction = () => {
    showControlsWithTimer()
}

const handleUserActivity = (e) => {
    // Don't reset timer if clicking on speed menu
    if (e.target.closest('.speed-menu-container') || e.target.closest('.speed-menu')) {
        return
    }
    showControlsWithTimer()
}

const setPlaybackSpeed = (speed) => {
    rate.value = speed
    showControlsWithTimer()
}

// --- Watchers اصلاح شده ---
watch(isFullscreen, (newVal) => {
    if (newVal) {
        // When entering fullscreen
        showControlsWithTimer()
        window.addEventListener('mousemove', handleUserActivity)
        window.addEventListener('mousedown', handleUserActivity)
        window.addEventListener('keydown', handleUserActivity)
    } else {
        // When exiting fullscreen
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
    currentTime.value = Number(e.target.value)
}

const onProgressBarClick = (e) => {
    const progressBar = e.currentTarget
    const clickPosition = (e.clientX - progressBar.getBoundingClientRect().left) / progressBar.offsetWidth
    const newTime = clickPosition * duration.value
    currentTime.value = newTime
    if (video.value) video.value.currentTime = newTime
}

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
    // Only handle keys if video is focused or fullscreen
    if (!video.value) return

    switch (e.key) {
        case ' ':
        case 'k':
            e.preventDefault()
            playing.value = !playing.value
            break
        case 'ArrowLeft':
            video.value.currentTime = Math.max(0, video.value.currentTime - seekStep)
            break
        case 'ArrowRight':
            video.value.currentTime = Math.min(duration.value, video.value.currentTime + seekStep)
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
    if (!video.value) return
    video.value.currentTime = Math.min(Math.max(0, video.value.currentTime + seconds), duration.value)
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
watch(currentTime, (time) => {
    if (props.lesson?.completed === true) return
    if (duration.value > 0 && !hasReportedEnd.value && props.lesson.id) {
        const remaining = duration.value - time
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
    if (time < oldTime && hasReportedEnd.value && props.lesson.id) {
        hasReportedEnd.value = false
    }
})
</script>

<style scoped>
/* --- Main Variables --- */
:root {
    --primary-green: #00e676; /* Vibrant Neon Green */
    --primary-glow: rgba(0, 230, 118, 0.4);
    --glass-bg: rgba(20, 20, 20, 0.75);
    --hover-bg: rgba(255, 255, 255, 0.1);
}

/* --- Container & Video --- */
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

/* --- Controls Bar --- */
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
    /* Glassmorphism Effect */
    backdrop-filter: blur(2px);
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

/* --- Buttons --- */
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
    color: #00e676; /* Green text on hover */
}

.control-btn:hover .v-icon {
    color: #00e676 !important; /* Green icon on hover */
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

/* --- Progress Bar (THE GREEN PART) --- */
.progress-container {
    width: 100%;
    height: 16px; /* Bigger touch area */
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

/* Buffered (Loaded) */
.progress-buffered {
    position: absolute;
    top: 0;
    height: 100%;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 2px;
    pointer-events: none;
    z-index: 1;
}

/* Played (The Green Line) */
.progress-played {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: #00e676; /* Green Color */
    border-radius: 2px;
    pointer-events: none;
    z-index: 2;
    box-shadow: 0 0 10px rgba(0, 230, 118, 0.5); /* Glowing effect */
}

/* Thumb (The Circle) */
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
    box-shadow: 0 0 0 3px rgba(0, 230, 118, 0.3); /* Green ring */
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

/* --- Volume Control (Custom Style) --- */
.volume-control {
    display: flex;
    align-items: center;
    gap: 8px; /* کمی فاصله بیشتر */
    margin: 0 5px;
}

.volume-slider-wrapper {
    width: 80px; /* کمی عریض‌تر برای کنترل راحت‌تر */
    height: 30px;
    display: flex;
    align-items: center;
}

.volume-slider {
    -webkit-appearance: none; /* حذف استایل پیش‌فرض کروم */
    appearance: none;
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.2); /* رنگ طوسی پس‌زمینه */
    border-radius: 2px;
    outline: none;
    cursor: pointer;

    /* تنظیم گرادینت برای بخش پر شده */
    background-image: linear-gradient(#00e676, #00e676);
    background-repeat: no-repeat;
    /* background-size توسط Vue در HTML تنظیم می‌شود */
}

/* استایل دایره (Thumb) در کروم و سافاری */
.volume-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 12px;
    height: 12px;
    background: #fff;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 0 5px rgba(0, 230, 118, 0.8); /* سایه سبز نئونی */
    transition: transform 0.1s;
}

.volume-slider::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

/* استایل دایره (Thumb) در فایرفاکس */
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

/* --- Time Display --- */
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

/* --- Overlays --- */
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
    background: #00e676; /* Green background on hover */
    transform: translate(-50%, -50%) scale(1.1);
    border-color: #00e676;
}
.overlay-play:hover .v-icon {
    color: black !important; /* Black icon on green bg */
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

/* --- Speed Menu --- */
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

/* --- Fullscreen Overrides --- */
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
    padding-bottom: 25px; /* More padding in fullscreen */
    background: linear-gradient(to top, rgba(0,0,0,0.95), transparent);
}
</style>
