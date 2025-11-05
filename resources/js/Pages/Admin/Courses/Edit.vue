<template>
    <div ref="videoContainer" class="video-container">
        <video
            ref="video"
            class="video-player"
            @click="playing = !playing"
            @waiting="isBuffering = true"
            @playing="isBuffering = false"
            @canplay="isBuffering = false"
        ></video>

        <!-- Loading Overlay -->
        <div v-if="isBuffering" class="loading-overlay">
            <v-progress-circular
                indeterminate
                color="primary"
                size="64"
            />
        </div>

        <div class="video-controls">
            <!-- Fullscreen Button -->
            <button @click="toggleFullscreen" class="control-btn">
                <v-icon color="white">
                    {{ isFullscreen ? 'mdi-fullscreen-exit' : 'mdi-fullscreen' }}
                </v-icon>
            </button>
            <!-- Download Button -->
            <a :href="videoSrc" download class="control-btn" title="دانلود ویدیو">
                <v-icon color="white">mdi-download</v-icon>
            </a>
            <!-- Play/Pause Button -->
            <button @click="playing = !playing" class="control-btn">
                <v-icon color="white">
                    {{ playing ? 'mdi-pause' : 'mdi-play' }}
                </v-icon>
            </button>
            <div class="volume-control">
                <v-icon color="white" size="20">mdi-volume-high</v-icon>
                <input
                    type="range"
                    min="0"
                    max="1"
                    step="0.01"
                    v-model="volume"
                    class="volume-slider"
                />
            </div>

            <!-- Playback Speed -->
            <v-menu location="top" offset="6">
                <template #activator="{ props }">
                    <button v-bind="props" class="control-btn">
                        <v-icon color="white">mdi-speedometer</v-icon>
                    </button>
                </template>

                <v-list class="speed-menu">
                    <v-list-item
                        v-for="speed in speeds"
                        :key="speed"
                        @click="rate = speed"
                        :class="{ active: rate === speed }"
                        class="speed-item"
                    >
                        <v-list-item-title>{{ speed }}x</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
            <!-- Progress Bar -->
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
                    <input
                        type="range"
                        v-model="currentTime"
                        :max="duration"
                        @input="onSeek"
                        class="progress-slider"
                    />
                </div>
                <div class="time-display">
                    {{ formatTime(currentTime) }} / {{ formatTime(duration) }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useFullscreen, useMediaControls} from '@vueuse/core'
import { ref, onMounted, computed } from 'vue'

const video = ref(null)
const isBuffering = ref(false)
const videoSrc = 'https://dl.enghelab.ir/uploads/videos/test.mp4'

const {
    playing,
    currentTime,
    duration,
    volume,
    buffered,
    rate
} = useMediaControls(video, {
    src: videoSrc,
})
const videoContainer = ref(null) // کانتینر کل پلیر

const { isFullscreen, toggle: toggleFullscreen } = useFullscreen(videoContainer)
const speeds = [1, 1.5, 2, 3]
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

// Set initial volume and start time
onMounted(() => {
    volume.value = 1
    currentTime.value = 0
})
</script>

<style scoped>
.video-container {
    position: relative;
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.video-player {
    width: 100%;
    display: block;
    cursor: pointer;
    aspect-ratio: 16 / 9; /* Maintain aspect ratio */
}

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
    z-index: 10;
}

.video-controls {
    display: flex;
    align-items: center;
    padding: 10px;
    background: rgba(0, 0, 0, 0.7);
    gap: 10px;
    position: relative;
    z-index: 2;
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
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.progress-bar {
    position: relative;
    width: 100%;
    height: 4px;
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
    background: #4CAF50;
    border-radius: 2px;
    pointer-events: none;
    transition: width 0.1s linear;
    z-index: 2;
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
    background: #4CAF50 !important;
    color: white !important;
    font-weight: bold;
}
</style>
