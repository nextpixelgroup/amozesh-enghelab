<script>
import { Head } from '@inertiajs/vue3'
import {defineComponent} from "vue";
import WebLayout from "../../Layouts/WebLayout.vue";

export default defineComponent({
    components: {AppLayout: WebLayout, Head}
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
