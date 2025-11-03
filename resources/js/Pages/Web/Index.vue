<script>
import { Head } from '@inertiajs/vue3'
import {defineComponent} from "vue";
import AppLayout from "../../Layouts/AppLayout.vue";

export default defineComponent({
    components: {AppLayout, Head}
})


</script>

<template>
    <Head title="Welcome" />
    <AppLayout>
        <h1>Welcome</h1>
        <h2>Welcome</h2>
        <p>Hello, welcome to your first Inertia app!</p>
    </AppLayout>
</template>
