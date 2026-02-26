<template>
    <a :href="course.url" :class="['zo-course', progress === 100 ? '' : '']">
        <div v-if="progress === 100" class="course-badge completed">
            تکمیل شده
        </div>
        <div v-else class="course-badge enrolled">
            درحال یادگیری
        </div>
        <figure>
            <div class="zo-thumbnail">
                <img :src="course.thumbnail" alt="" class="img-fluid" />
            </div>
        </figure>
        <div class="zo-details">
            <v-tooltip :text="course.title" location="top">
                <template v-slot:activator="{ props }">
                    <h2 v-bind="props">{{ course.title }}</h2>
                </template>
            </v-tooltip>
            <span class="zo-prof">{{ course.teacher }}</span>
            <ul>
                <li>
                    <img src="/assets/img/site/c-clock.svg" alt="" class="img-fluid" />
                    <span class="zo-duration">{{ course.duration }}</span>
                </li>
                <li>
                    <img src="/assets/img/site/c-students.svg" alt="" class="img-fluid" />
                    <span class="zo-students">{{ course.students > 0 ? course.students : '0' }}</span>
                </li>
            </ul>
            <div class="zo-price">
                <span>قیمت</span>
                <strong>{{ course.price }}</strong>
            </div>
            <div class="zo-more">اطلاعات بیشتر</div>
            <v-progress-linear color="secondary" :model-value="progress" height="20" reverse class="mt-1">
                <strong :class="progressTextClass">{{`${progress}%`}}</strong>
            </v-progress-linear>
        </div>
    </a>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    course: {
        type: Object,
        required: true
    }
})

const progress = ref(props.course.progress)
const progressTextClass = computed(() => {
    return progress.value > 50 ? 'text-white' : 'text-dark';
})

</script>
<style scoped>
.v-progress-linear {
    border-radius: 300px
}

.v-progress-linear :deep(strong) {
    font-size: .75rem
}

</style>
