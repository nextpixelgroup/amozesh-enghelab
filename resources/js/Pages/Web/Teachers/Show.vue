<template>
    <Head title="مدرس"/>
    <v-container class="mt-5">
        سیبسی
    </v-container>
</template>
<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
const props = defineProps({
    teacher: Object
});
const teacher = props.teacher;
</script>
