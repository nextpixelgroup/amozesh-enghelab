<template>
    <a :href="course.url" class="zo-course">
        <v-row dense>
            <v-col cols="12" lg="6">
                <figure>
                    <div class="zo-thumbnail">
                        <img :src="course.thumbnail" class="img-fluid"/>
                    </div>
                </figure>
            </v-col>
            <v-col cols="12" lg="6">
                <div class="zo-details">
                    <v-tooltip :text="course.title" location="top">
                        <template v-slot:activator="{ props }">
                            <h2 v-bind="props">{{ course.title }}</h2>
                        </template>
                    </v-tooltip>
                    <span class="zo-prof">{{ course.teacher }}</span>
                    <ul>
                        <li>
                            <img src="/assets/img/site/c-clock.svg"/>
                            <span class="zo-duration">{{ course.duration }}</span>
                        </li>
                        <li>
                            <img src="/assets/img/site/c-students.svg"/>
                            <span class="zo-students">{{ course.students }} دانشجو</span>
                        </li>
                    </ul>
                    <div class="zo-price">
                        <span>قیمت</span>
                        <strong>{{ course.price }}</strong>
                    </div>
                    <div class="zo-more">اطلاعات بیشتر</div>
                </div>
            </v-col>
        </v-row>
    </a>
</template>
<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    course: {
        type: Object,
        required: true
    }
})
</script>
<style scoped>
    .zo-courses-section .zo-course figure {
        margin: 0
    }

    .zo-courses-section .zo-course figure .zo-thumbnail img {
        height: 170px
    }
</style>
