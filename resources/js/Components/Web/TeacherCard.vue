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
<style scoped>
.zo-profs-section .zo-prof {
    display: block;
    margin: 0 0 10px
}

.zo-profs-section .zo-prof figure img {
    width: 200px;
    height: 200px;
    display: block;
    margin: 0 auto 15px;
    border-radius: 50%;
    object-fit: cover;
    transition: all 0.25s ease-in-out
}

.zo-profs-section .zo-prof strong {
    display: block;
    margin: 0 0 5px;
    text-align: center
}

.zo-profs-section .zo-prof span {
    display: block;
    margin: 0 0 10px;
    color: var(--Sub)
}

.zo-profs-section .zo-prof small {
    max-width: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    padding: 5px 10px;
    background: rgba(5, 105, 60, .05);
    color: var(--Primary);
    border-radius: 300px;
    transition: all 0.25s ease-in-out
}

.zo-profs-section .zo-prof:hover figure img {
    box-shadow: 0 5px 15px rgba(0, 0, 0, .085)
}

.zo-profs-section .zo-prof:hover small {
    background: var(--Primary);
    color: var(--White)
}

</style>
