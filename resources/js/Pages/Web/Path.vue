<template>
    <Head title="Path" />
    <WebLayout>
        <div v-for="course in courses">
            <CourseCard :course="course"/>
        </div>
    </WebLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3'
import WebLayout from "@/Layouts/WebLayout.vue";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";

const courses = [
    {
        thumbnail: '/assets/img/default.svg',
        category: 'انقلاب',
        title: 'انقلاب اسلامی',
        teacher: 'علی مظلوم',
        duration: '12:45',
        students: 12,
        price: '10,000'
    }
]

</script>
