<script>
import { Head } from '@inertiajs/vue3'
import {defineComponent} from "vue";
import AdminLayout from '@/Layouts/AdminLayout.vue'

export default defineComponent({
    components: {AdminLayout, Head}
})


</script>

<template>
    <AdminLayout>
        <Head title="Courses" />
        <h1>Courses</h1>
        <p>Hello, Courses to your first Inertia app!</p>
    </AdminLayout>
</template>
