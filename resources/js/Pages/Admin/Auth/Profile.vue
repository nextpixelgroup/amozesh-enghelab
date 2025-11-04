<template>
    <AdminLayout title="پروفایل">
        <p>{{user.firstname}}</p>
        <p>{{user.lastname}}</p>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    user: Object
})
const user = props.user;
</script>
