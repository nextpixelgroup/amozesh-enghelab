<script>
import { Head } from '@inertiajs/vue3'
import {defineComponent} from "vue";
import AppLayout from "../../Layouts/AppLayout.vue";

export default defineComponent({
    components: {AppLayout, Head}
})


</script>

<template>
    <Head title="Contact" />
    <AppLayout>
        <h1>Contact</h1>
        <p>Hello, Contact to your first Inertia app!</p>
    </AppLayout>
</template>
