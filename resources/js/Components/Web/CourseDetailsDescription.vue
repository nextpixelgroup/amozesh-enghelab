<template>
    <div class="zo-text-section">
        <strong class="zo-title">درباره دوره</strong>
        <div v-html="description"></div>
    </div>
</template>
<script setup>

const props = defineProps({
    description: String
})
</script>
