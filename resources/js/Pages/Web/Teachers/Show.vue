<template>
    <Head title="مدرس"/>
    <v-container class="mt-5">
        <h1 class="text-black">Teacher Show</h1>
        <span>{{teacher.firstname}} {{teacher.lastname}}</span>
    </v-container>
</template>
<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
const props = defineProps({
    teacher: Object
});
const teacher = props.teacher;
</script>
