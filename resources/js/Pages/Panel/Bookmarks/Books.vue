<template>
<WebLayout>
    <PanelLayout>
        Books
    </PanelLayout>
</WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";
defineProps({
    books : {
        type: Object,
        default: []
    }
})
</script>
