<template>
    <Link :href="course.url" class="zo-course">
        <figure>
            <div class="zo-thumbnail">
                <img :src="course.thumbnail" alt="" class="img-fluid"/>
            </div>
            <div class="zo-category"  v-if="course.category">
                <img src="assets/img/site/c-cat.svg" alt="" class="img-fluid"/>
                <span>{{ course.category }}</span>
            </div>
        </figure>
        <div class="zo-content">
            <h2>{{ course.title }}</h2>
            <span class="zo-prof">{{ course.teacher }}</span>
            <ul>
                <li>
                    <img src="assets/img/site/c-clock.svg" alt="" class="img-fluid"/>
                    <span>{{ course.duration }}</span>
                </li>
                <li>
                    <img src="assets/img/site/c-students.svg" alt="" class="img-fluid"/>
                    <span>{{ course.students > 0 ? course.students : 'بدون' }} دانشجو</span>
                </li>
            </ul>
            <div class="zo-price">
                <span>قیمت</span>
                <strong>{{ course.price }}</strong>
            </div>
            <div class="zo-more">اطلاعات بیشتر</div>
        </div>
    </Link>
</template>
<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
const props = defineProps({
    course: {
        type: Object,
        required: true
    }
})
</script>
