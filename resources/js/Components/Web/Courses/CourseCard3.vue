<template>
    <Link :href="course.url" class="zo-course">
    <figure>
        <div class="zo-thumbnail">
            <img :src="course.thumbnail" alt="" class="img-fluid" />
        </div>
    </figure>
    <div class="zo-details">
        <v-tooltip :text="course.title" location="top">
            <template v-slot:activator="{ props }">
                <h2 v-bind="props">Test</h2>
            </template>
        </v-tooltip>
        <span class="zo-prof">Test</span>
        <ul>
            <li>
                <img src="/assets/img/site/c-clock.svg" alt="" class="img-fluid" />
                <span>29</span>
            </li>
            <li>
                <img src="/assets/img/site/c-students.svg" alt="" class="img-fluid" />
                <span>600 دانشجو</span>
            </li>
        </ul>
        <div class="zo-price">
            <span>قیمت</span>
            <strong>Test</strong>
        </div>
        <div class="zo-more">اطلاعات بیشتر</div>
    </div>
    </Link>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    course: {
        type: Object,
        required: true
    }
})

</script>