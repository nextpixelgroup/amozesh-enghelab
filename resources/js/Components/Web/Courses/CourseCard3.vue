<template>
    <Link :href="course.url" :class="['zo-course', progress === 100 ? 'zo-done' : '']">
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
                    <span>{{ course.duration }}</span>
                </li>
                <li>
                    <img src="/assets/img/site/c-students.svg" alt="" class="img-fluid" />
                    <span>{{ course.students > 0 ? course.students : 'بدون' }} دانشجو</span>
                </li>
            </ul>
            <div class="zo-price">
                <span>قیمت</span>
                <strong>{{ course.price }}</strong>
            </div>
            <div class="zo-more">اطلاعات بیشتر</div>
            <v-progress-linear color="secondary" :model-value="progress" height="15" reverse class="mt-3">
                <strong :class="progressTextClass">{{ progress }}%</strong>
            </v-progress-linear>
        </div>
    </Link>
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
