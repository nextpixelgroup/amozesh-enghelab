<template>
    <Head title="404 صفحه پیدا نشد"/>
    <PanelLayout>
        <v-empty-state
            headline="Whoops, 404"
            title="Page not found"
            text="The page you were looking for does not exist"
            image="https://vuetifyjs.b-cdn.net/docs/images/logos/v.png"
        ></v-empty-state>
    </PanelLayout>
</template>
<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import PanelLayout from "@/Layouts/PanelLayout.vue";
</script>
