<template>
    <AdminLayout>
        <Head title="Courses"/>
    </AdminLayout>
</template>
<script>
import {Head} from '@inertiajs/vue3'
import {defineComponent} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";

export default defineComponent({
    components: {AdminLayout, Head}
})
</script>
