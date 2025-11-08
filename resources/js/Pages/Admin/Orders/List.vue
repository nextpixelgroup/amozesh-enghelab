<template>
    <AdminLayout>
        <Head title="Courses" />
        <h1>Orders</h1>
    </AdminLayout>
</template>
<script>
import { Head } from '@inertiajs/vue3'
import {defineComponent} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";

export default defineComponent({
    components: {AdminLayout, Head}
})


</script>
