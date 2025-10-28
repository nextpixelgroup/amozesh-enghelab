<template>
    <Head title="404 صفحه پیدا نشد"/>
    <AdminLayout>
        <v-empty-state
            headline="خطای ۴۰۵"
            title="چیزی که در اینجا دنبالش هستی پیدا نمیشه!"
            text=""
            image="https://vuetifyjs.b-cdn.net/docs/images/logos/v.png"
            action-text="برگشت"
            @click:action="goBack"
        ></v-empty-state>
    </AdminLayout>
</template>
<script setup lang="ts">
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
const goBack = () => {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = route('admin.courses');
    }
};
</script>
