<template>
    <div class="zo-description-section" v-if="description" id="description">
        <strong class="zo-title">درباره دوره</strong>
        <div v-html="description"></div>
    </div>
</template>
<script setup>
const props = defineProps({
    description: String
})

</script>
<style scoped>
.zo-description-section {
    width: 100%;
    display: inline-block;
    margin: 0 0 30px
}

.zo-description-section .zo-title {
    display: block;
    margin: 0 0 15px;
    font-size: 1.125rem;
    color: var(--Secondary);
}

.zo-description-section p {
    margin: 0 0 10px
}

.zo-description-section h1 {
    margin: 0 0 10px;
    font-size: 1.25rem
}

.zo-description-section h2 {
    margin: 0 0 10px;
    font-size: 1.15rem
}

.zo-description-section h3 {
    font-size: 1.10rem
}

.zo-description-section h4 {
    font-size: 1.05rem
}

.zo-description-section h5 {
    font-size: 1rem
}

.zo-description-section h6 {
    font-size: .95rem
}

.zo-description-section ul {
    padding: 0 0 15px
}

.zo-description-section ul li {
    width: 100%;
    display: inline-block;
    padding: 1.5px 10px 1.5px 0;
    position: relative
}

.zo-description-section ul li:before {
    content: '';
    width: 5px;
    height: 5px;
    position: absolute;
    top: 9.5px;
    right: 0;
    background: var(--Secondary)
}

@media (max-width: 960px) {

    .zo-description-section {
        margin: 0 0 5px
    }
}

</style>