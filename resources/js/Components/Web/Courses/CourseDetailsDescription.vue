<template>
    <div class="zo-text-section" v-if="description" id="description">
        <strong class="zo-title">درباره دوره</strong>
        <div v-html="description"></div>
    </div>
</template>
<script setup>

const props = defineProps({
    description: String
})
</script>
