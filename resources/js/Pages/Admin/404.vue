<template>
    <Head title="404 صفحه پیدا نشد"/>
    <AdminLayout>
        <v-empty-state
            headline="خطای ۴۰۴"
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
    window.history.length > 1 ? router.back() : router.visit(route('admin.courses'));
};
</script>
