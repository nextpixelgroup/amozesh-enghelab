<template>
    <a :href="teacher.url" class="zo-prof">
        <figure>
            <img :src="teacher.avatar" alt="">
        </figure>
        <strong>{{teacher.title}}</strong>
        <span>{{teacher.degree}}</span>
        <small>درباره مدرس</small>
    </a>
</template>
<script setup lang="ts">
defineProps({
    teacher: Object
})
</script>
