<template>
    <div>
        <div class="zo-intro-section" @click="dialog = true">
            <div class="zo-player">
                <img src="/assets/img/site/player.svg" alt="">
            </div>
            <div class="zo-thumbnail">
                <img :src="course.poster" alt="">
            </div>
            <div class="zo-content">
                <h1>{{course.title}}</h1>
                <p>{{course.summary}}</p>
            </div>
        </div>
    </div>

    <v-dialog
        v-model="dialog"
        width="auto"
    >
        <v-card
            max-width="400"
            prepend-icon="mdi-update"
            text="Your application will relaunch automatically after the update is complete."
            title="Update in progress"
        >
            <template v-slot:actions>
                <v-btn
                    class="ms-auto"
                    text="Ok"
                    @click="dialog = false"
                ></v-btn>
            </template>
        </v-card>
    </v-dialog>
</template>
<script setup>
import {ref} from "vue";

const props = defineProps({
    course: {
        type: Object,
        required: true
    }
})
const dialog = ref(false)
</script>
<style scoped>
.zo-intro-section {
    position: relative;
}

.zo-intro-section .zo-player {
    margin: 0 auto;
    position: absolute;
    top: 50%;
    right: 0;
    left: 0;
    transform: translateY(-50%);
    text-align: center;
    z-index: 15
}

.zo-intro-section .zo-thumbnail img {
    width: 100%;
    border-radius: .75rem
}

.zo-intro-section .zo-content {
    padding: 0 30px;
    position: absolute;
    right: 0;
    bottom: 30px;
    left: 0;
    color: rgb(255, 255, 255)
}

.zo-intro-section .zo-content h1 {
    display: block;
    margin: 0 0 5px;
    padding: 0 15px 0 0;
    position: relative;
    font-size: 1.5rem
}

.zo-intro-section .zo-content h1:before {
    content: '';
    width: 2.5px;
    height: 75%;
    position: absolute;
    top: 5px;
    right: 0;
    background: rgb(200, 160, 100)
}
</style>
