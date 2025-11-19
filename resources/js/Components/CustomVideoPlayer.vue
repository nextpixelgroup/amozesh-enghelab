<template>
    <div ref="videoContainer" class="video-container" @mousemove="handleControlInteraction">
        <!-- Overlay Play Button - Only show when not playing AND not buffering -->
        <div
            v-if="!playing && !isBuffering"
            class="overlay-play"
            @click="playing = true"
        >
            <v-icon size="72" color="white">mdi-play-circle-outline</v-icon>
        </div>
        <video
            ref="video"
            class="video-player"
            :poster="poster"
            @click="playing = !playing"
            @waiting="isBuffering = true"
            @playing="isBuffering = false"
            @canplay="isBuffering = false"
        ></video>

        <!-- Loading Overlay - Show on top of video when buffering -->
        <div v-if="isBuffering" class="loading-overlay">
            <v-progress-circular
                indeterminate
                color="#0075ff"
                size="64"
            />
        </div>

        <div class="video-controls" :class="{ 'controls-hidden': !showControls }" @mousemove="handleControlInteraction">
            <!-- Controls wrapper for better hover handling -->
            <div class="controls-wrapper">
                <div class="progress-container">
                    <div class="progress-bar" @click="onProgressBarClick">
                        <div
                            v-for="(range, index) in bufferedRanges"
                            :key="index"
                            class="progress-buffered"
                            :style="{
                                width: range.width + '%',
                                left: range.left + '%'
                            }"
                        ></div>
                        <div
                            class="progress-played"
                            :style="{ width: progressPercentage + '%' }"
                        ></div>
                        <div
                            class="progress-thumb"
                            :style="{ left: progressPercentage + '%' }"
                        ></div>
                        <input
                            type="range"
                            v-model="currentTime"
                            :max="duration"
                            @input="onSeek"
                            class="progress-slider"
                        />
                    </div>

                </div>
                <div class="controls-row">
                    <div class="controls-left">
                        <!-- Fullscreen Button -->
                        <button @click="toggleFullscreen" class="control-btn">
                            <v-icon color="white">
                                {{ isFullscreen ? 'mdi-fullscreen-exit' : 'mdi-fullscreen' }}
                            </v-icon>
                        </button>

                        <!-- Download Button -->
                        <a
                            :href="route('web.courses.download.video',filename)"
                            class="control-btn"
                            title="دانلود ویدیو"
                            @click="handleDownload"
                        >
                            <v-progress-circular
                                v-if="isDownloading"
                                indeterminate
                                size="24"
                                width="2"
                                color="white"
                            />
                            <v-icon v-else color="white">mdi-download</v-icon>
                        </a>
                        <!-- Playback Speed -->
                        <v-menu
                            location="top"
                            offset="6"
                            :attach="videoContainer"
                            content-class="speed-menu-container"
                            :close-on-content-click="false"
                        >
                            <template #activator="{ props }">
                                <button v-bind="props" class="control-btn" @click.stop>
                                    <v-icon color="white">mdi-speedometer</v-icon>
                                </button>
                            </template>

                            <v-list class="speed-menu">
                                <v-list-item
                                    v-for="speed in speeds"
                                    :key="speed"
                                    @click="setPlaybackSpeed(speed)"
                                    :class="{ active: rate === speed }"
                                    class="speed-item"
                                >
                                    <v-list-item-title>{{ speed }}x</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>



                    </div>

                    <div class="controls-right">




                        <!-- Volume Control -->
                        <div class="volume-control">
                            <div class="volume-slider-container">
                                <input
                                    type="range"
                                    min="0"
                                    max="1"
                                    step="0.01"
                                    v-model="volume"
                                    class="volume-slider"
                                    style="transform: rotateY(180deg);"
                                />
                            </div>
                            <v-icon color="white" size="20">mdi-volume-high</v-icon>
                        </div>
                        <div class="time-display">
                            {{ formatTime(currentTime) }} / {{ formatTime(duration) }}
                        </div>
                        <!-- Forward 10s -->
                        <button @click="skip(10)" class="control-btn">
                            <v-icon color="white">mdi-fast-forward-10</v-icon>
                        </button>
                        <!-- Back 10s -->
                        <button @click="skip(-10)" class="control-btn">
                            <v-icon color="white">mdi-rewind-10</v-icon>
                        </button>

                        <!-- Play/Pause -->
                        <button @click="playing = !playing" class="control-btn">
                            <v-icon color="white">
                                {{ playing ? 'mdi-pause' : 'mdi-play' }}
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
const props = defineProps({
    'src' : {
        type: String,
        required: true
    },
    'poster' : {
        type: String,
        required: true
    },
    'filename': {
        type: String,
        required: true
    }
});
const video = ref(null)
const isBuffering = ref(false)
const isDownloading = ref(false)

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
const speeds = [1, 1.5, 2, 3]

const hideControls = () => {
    if (isFullscreen.value) {
        showControls.value = false
        document.body.style.cursor = 'none'
    }
}

const showControlsWithTimer = () => {
    if (isFullscreen.value) {
        showControls.value = true
        document.body.style.cursor = 'auto'

        if (controlsTimeout) clearTimeout(controlsTimeout)
        controlsTimeout = setTimeout(hideControls, 3000)
    }
}

const handleUserActivity = (e) => {
    // Don't reset timer if clicking on speed menu
    if (e.target.closest('.speed-menu-container') || e.target.closest('.speed-menu')) {
        return
    }

    if (isFullscreen.value) {
        showControlsWithTimer()
    }
}

const setPlaybackSpeed = (speed) => {
    rate.value = speed
    // Keep controls visible after selecting speed
    if (isFullscreen.value) {
        showControlsWithTimer()
    }
}

const handleSpeedButtonClick = (e) => {
    e.stopPropagation()
    showControlsWithTimer()
}

// Watch for fullscreen changes
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

// Clean up
onBeforeUnmount(() => {
    if (controlsTimeout) clearTimeout(controlsTimeout)
    window.removeEventListener('mousemove', handleUserActivity)
    window.removeEventListener('mousedown', handleUserActivity)
    window.removeEventListener('keydown', handleUserActivity)
    document.body.style.cursor = 'auto'
})
// Video container and fullscreen setup is now moved above
const bufferedRanges = computed(() => {
    if (!buffered.value || !buffered.value.length || duration.value === 0) return []

    return buffered.value.map(range => ({
        left: (range.start / duration.value) * 100,
        width: ((range.end - range.start) / duration.value) * 100
    }))
})

// Calculate progress percentage
const progressPercentage = computed(() => {
    return duration.value > 0 ? (currentTime.value / duration.value) * 100 : 0
})

// Calculate buffered style
const bufferedStyle = computed(() => {
    if (!video.value || !buffered.value || buffered.value.length === 0) return 'width: 0%'

    // Get the last buffered range (most likely the one we're currently playing)
    const lastRange = buffered.value.length - 1
    const bufferedEnd = buffered.value.end(lastRange)
    const bufferedPercent = (bufferedEnd / duration.value) * 100

    return `width: ${bufferedPercent}%`
})

// Format time (MM:SS)
const formatTime = (time) => {
    const minutes = Math.floor(time / 60)
    const seconds = Math.floor(time % 60)
    return `${minutes}:${seconds.toString().padStart(2, '0')}`
}

// Handle seeking
const onSeek = (e) => {
    if (video.value) {
        video.value.currentTime = parseFloat(e.target.value)
    }
}

// Handle click on progress bar
const onProgressBarClick = (e) => {

    const progressBar = e.currentTarget
    const clickPosition = (e.clientX - progressBar.getBoundingClientRect().left) / progressBar.offsetWidth
    const newTime = clickPosition * duration.value
    currentTime.value = newTime
    if (video.value) {
        video.value.currentTime = newTime
    }
    isBuffering.value = true
}

const seekStep = 10 // مقدار جا‌به‌جایی ثانیه‌ها

const handleDownload = (e) => {
    isDownloading.value = true

    // Reset the loading state after a short delay in case the download doesn't start
    const timer = setTimeout(() => {
        isDownloading.value = false
    }, 2000) // 5 seconds timeout

    // This will be called when the download actually starts
    const onDownloadStart = () => {
        clearTimeout(timer)
        isDownloading.value = false
        window.removeEventListener('blur', onDownloadStart)
    }

    // When the window loses focus (download starts), we know the download has begun
    window.addEventListener('blur', onDownloadStart)

    // If the click doesn't trigger a download, clean up
    setTimeout(() => {
        window.removeEventListener('blur', onDownloadStart)
    }, 1000)
}

const handleKey = (e) => {
    if (!video.value) return

    switch (e.key) {
        case ' ': // Space
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
            volume.value = Math.min(1, volume.value + 0.05)
            break

        case 'ArrowDown':
            volume.value = Math.max(0, volume.value - 0.05)
            break
    }
}

const skip = (seconds) => {
    if (!video.value) return
    video.value.currentTime = Math.min(
        Math.max(0, video.value.currentTime + seconds),
        duration.value
    )
}

// Set initial volume and start time
onMounted(() => {
    volume.value = 1
    currentTime.value = 0
    window.addEventListener('keydown', handleKey)
    window.addEventListener('blur', handleDownloadComplete)
})

const handleDownloadComplete = () => {
    isDownloading.value = false
}

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKey)
    window.removeEventListener('blur', handleDownloadComplete)
})

</script>

<style scoped>
/* Controls Layout */
.controls-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding: 5px 10px;
    box-sizing: border-box;
}

.controls-left, .controls-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.control-btn {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
}

.control-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

.volume-control {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 0 5px;
}

.volume-slider {
    width: 80px;
    cursor: pointer;
}


.volume-slider-container {
    display: flex;
    align-items: center;
}

/* Progress Bar */
.progress-container {
    width: 100%;
    padding: 10px 10px 5px;
    box-sizing: border-box;
}

.progress-bar {
    position: relative;
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
    cursor: pointer;
    overflow: hidden;
}

.progress-played {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: #ff5722;
    border-radius: 3px;
    pointer-events: none;
}

.progress-buffered {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
    pointer-events: none;
}

.time-display {
    font-size: 12px;
    color: white;
    text-align: right;
    padding: 2px 5px 0 0;
    text-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
}

/* Hide default range input */
.progress-slider {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    -webkit-appearance: none;
}

/* Fullscreen styles */
:fullscreen .video-container {
    background-color: black;
    display: flex;
    align-items: center;
    justify-content: center;
}

:fullscreen .video-player {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

:fullscreen .video-controls {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
}

/* Speed menu */
.speed-menu-container {
    position: absolute !important;
    z-index: 2 !important;
    pointer-events: auto !important;
}

.speed-menu {
    background: rgba(0, 0, 0, 0.9) !important;
    border-radius: 4px;
    min-width: 80px;
    pointer-events: auto !important;
}

.speed-item {
    color: white !important;
    font-size: 14px;
    padding: 4px 16px;
    cursor: pointer;
}

.speed-item:hover {
    background: rgba(255, 255, 255, 0.1) !important;
}

.speed-item.active {
    background: rgba(255, 255, 255, 0.15) !important;
    font-weight: 500;
}

.speed-item.active {
    background: #ff5722 !important;
    color: white !important;
}

/* Loading overlay */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.5);
    z-index: 2;
}

/* Play overlay */
.overlay-play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.overlay-play:hover {
    opacity: 1;
}

/* Video container */
.video-container {
    position: relative;
    width: 100%;
    background: #000;
    overflow: hidden;
}

.video-player {
    width: 100%;
    display: block;
}

/* Video controls */
.video-controls {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
    padding: 0;
    z-index: 10;
    transition: opacity 0.3s ease;
}

.video-controls.controls-hidden {
    opacity: 0;
    pointer-events: none;
}

.video-container:hover .video-controls {
    opacity: 1;
}

/* Hide controls when video is not playing */
.video-container:not(.playing) .video-controls {
    opacity: 1;
}
.video-container {
    position: relative;
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    background-color: #000;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Ensure proper stacking context */
    z-index: 1;
}

.video-player {
    width: 100%;
    display: block;
    cursor: pointer;
    aspect-ratio: 16 / 9; /* Maintain aspect ratio */
}

.video-controls {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 10;
    pointer-events: auto;
}

/* Controls visibility */
.video-controls {
    opacity: 1;
    transform: translateY(0);
    transition: opacity 0.2s ease, transform 0.2s ease;
    pointer-events: auto;
    will-change: opacity, transform;
}

.video-controls.controls-hidden {
    opacity: 0 !important;
    transform: translateY(10px) !important;
    pointer-events: none !important;
    transition: opacity 0.3s ease, transform 0.3s ease !important;
}

/* Fullscreen specific styles */
:fullscreen .video-controls {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
    background: rgba(0, 0, 0, 0.9);
}

:fullscreen .video-container:hover .video-controls {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.controls-wrapper {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
}

/* Fullscreen styles */
:fullscreen .video-container {
    background-color: black;
    display: flex;
    align-items: center;
    justify-content: center;
}

:fullscreen .video-player {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

:fullscreen .video-controls {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 2147483647; /* Maximum z-index value */
    background: rgba(0, 0, 0, 0.7);
    padding: 15px 20px;
}

:fullscreen .video-container:hover .video-controls.controls-hidden {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
    background: rgba(0, 0, 0, 0.7);
    transition: opacity 0.2s ease, transform 0.2s ease;
}

:fullscreen .overlay-play,
:fullscreen .loading-overlay {
    position: fixed;
    z-index: 2147483646; /* Just below the controls */
}

.control-btn {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
}

.control-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

.progress-container {
    width: 100%;
    padding: 0 10px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.progress-bar {
    position: relative;
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 2px;
    cursor: pointer;
    overflow: hidden;
}

.progress-buffered {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    pointer-events: none;
    transition: width 0.2s ease;
}

.progress-played {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: #0075ff;
    border-radius: 2px;
    pointer-events: none;
    transition: width 0.1s linear;
    z-index: 2;
}

/* ✅ دایره‌ی سر نوار */
.progress-thumb {
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 10px;
    height: 14px;
    background: #fff;
    border: 0px solid #0075ff;
    border-radius: 50%;
    z-index: 5;
    pointer-events: none;
    transition: transform 0.1s ease;
}

.progress-bar:hover .progress-thumb {
    transform: translate(-50%, -50%) scale(1.1);
}

.progress-slider {
    position: relative;
    width: 100%;
    height: 100%;
    margin: 0;
    opacity: 0;
    cursor: pointer;
    z-index: 3;
}

.progress-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #fff;
    cursor: pointer;
    position: relative;
    z-index: 4;
}

.progress-slider::-moz-range-thumb {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #fff;
    cursor: pointer;
    border: none;
    position: relative;
    z-index: 4;
}

.time-display {
    color: white;
    font-size: 0.8rem;
    opacity: 0.8;
    white-space: nowrap;
}

.volume-control {
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 120px;
}

.volume-slider {
    width: 80px;
    cursor: pointer;
}

.speed-menu {
    min-width: 90px;
    background: rgba(30, 30, 30, 0.95);
    backdrop-filter: blur(6px);
}

.speed-item {
    color: #eee;
    cursor: pointer;
}

.speed-item:hover {
    background: rgba(255, 255, 255, 0.15);
}

.speed-item.active {
    background: #0075ff !important;
    color: white !important;
    font-weight: bold;
}
.overlay-play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 20;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.35);
    padding: 40px;
    border-radius: 50%;
    transition: 0.25s ease;
}

.overlay-play:hover {
    background: rgba(0,0,0,0.55);
}
</style>
