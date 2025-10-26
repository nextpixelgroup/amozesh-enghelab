<script>
import { Head } from '@inertiajs/vue3'
import {defineComponent} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";

export default defineComponent({
    components: {AdminLayout, Head}
})


</script>

<template>
    <AdminLayout>
        <Head title="Users" />
        <h1>Users</h1>
        <p>Hello, Users to your first Inertia app!</p>
    </AdminLayout>
</template>
