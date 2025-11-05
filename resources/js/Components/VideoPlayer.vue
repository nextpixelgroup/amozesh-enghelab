<template>
    <div class="video-player">
        <video
            ref="video"
            :src="src"
            :poster="poster"
            class="video-element"
            @click="togglePlay"
        ></video>

        <!-- Controls Overlay -->
        <div class="controls-overlay" @click.stop>
            <!-- Play/Pause Button -->
            <button class="control-btn" @click="togglePlay">
                <v-icon large color="white">
                    {{ isPlaying ? 'mdi-pause' : 'mdi-play' }}
                </v-icon>
            </button>

            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="time">{{ formatTime(currentTime) }}</div>
                <input
                    type="range"
                    v-model="currentTime"
                    :max="duration"
                    @input="onSeek"
                    class="progress-slider"
                />
                <div class="time">{{ formatTime(duration) }}</div>
            </div>

            <!-- Right Controls -->
            <div class="right-controls">
                <!-- Playback Speed -->
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <button class="control-btn" v-bind="attrs" v-on="on">
                            {{ playbackRate }}x
                        </button>
                    </template>
                    <v-list>
                        <v-list-item
                            v-for="speed in [0.5, 1, 1.5, 2]"
                            :key="speed"
                            @click="setSpeed(speed)"
                            :class="{ 'active-speed': speed === playbackRate }"
                        >
                            {{ speed }}x
                        </v-list-item>
                    </v-list>
                </v-menu>

                <!-- Volume Control -->
                <div class="volume-control">
                    <button class="control-btn" @click="toggleMute">
                        <v-icon color="white">
                            {{
                                isMuted || volume === 0
                                    ? 'mdi-volume-off'
                                    : volume < 0.5
                                        ? 'mdi-volume-medium'
                                        : 'mdi-volume-high'
                            }}
                        </v-icon>
                    </button>
                    <input
                        type="range"
                        v-model="volume"
                        min="0"
                        max="1"
                        step="0.1"
                        class="volume-slider"
                    />
                </div>

                <!-- Fullscreen Toggle -->
                <button class="control-btn" @click="toggleFullscreen">
                    <v-icon color="white">mdi-fullscreen</v-icon>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useMediaControls } from '@vueuse/core';

const props = defineProps({
    src: {
        type: String,
        required: true
    },
    poster: {
        type: String,
        default: ''
    },
    autoplay: {
        type: Boolean,
        default: false
    }
});

const video = ref(null);

const {
    currentTime,
    duration,
    isPlaying,
    volume,
    isMuted,
    playbackRate,
    togglePlay,
    toggleMute,
    toggleFullscreen
} = useMediaControls(video, {
    src: props.src
});

// Set initial volume
volume.value = 1;

// Handle autoplay
onMounted(() => {
    if (props.autoplay) {
        togglePlay();
    }
});

// Handle seeking
const onSeek = (e) => {
    if (video.value) {
        video.value.currentTime = parseFloat(e.target.value);
    }
};

// Set playback speed
const setSpeed = (speed) => {
    playbackRate.value = speed;
};

// Format time (MM:SS)
const formatTime = (time) => {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds.toString().padStart(2, '0')}`;
};
</script>

<style scoped>
.video-player {
    position: relative;
    width: 100%;
    max-width: 100%;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.video-element {
    width: 100%;
    display: block;
    cursor: pointer;
}

.controls-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 16px;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
    display: flex;
    align-items: center;
    gap: 12px;
    transition: opacity 0.3s;
}

.video-player:hover .controls-overlay {
    opacity: 1;
}

.control-btn {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.control-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

.progress-container {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 8px;
    color: white;
}

.progress-slider {
    flex: 1;
    height: 4px;
    border-radius: 2px;
    background: rgba(255, 255, 255, 0.3);
    -webkit-appearance: none;
    appearance: none;
    outline: none;
}

.progress-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #fff;
    cursor: pointer;
}

.volume-control {
    display: flex;
    align-items: center;
    gap: 4px;
}

.volume-slider {
    width: 60px;
    height: 4px;
    -webkit-appearance: none;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
    outline: none;
}

.volume-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #fff;
    cursor: pointer;
}

.right-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.active-speed {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Hide controls when not hovering */
.video-player:not(:hover) .controls-overlay {
    opacity: 0;
}

/* Fullscreen styles */
:fullscreen .controls-overlay {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

:fullscreen .video-element {
    width: 100vw;
    height: 100vh;
    object-fit: contain;
}
</style>
