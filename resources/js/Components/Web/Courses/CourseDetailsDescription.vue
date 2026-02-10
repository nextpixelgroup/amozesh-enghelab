<template>
    <div class="zo-description-section" v-if="description" id="description">
        <strong class="zo-title">درباره دوره</strong>
        <div class="zo-description-content" v-html="description"></div>
    </div>
</template>

<script setup>
const props = defineProps({
    description: {
        type: String,
        required: true
    }
})
</script>

<style scoped>
.zo-description-section {
    width: 100%;
    display: inline-block;
    margin: 0 0 30px;
}

.zo-description-section .zo-title {
    display: block;
    margin: 0 0 15px;
    font-size: 1.125rem;
    color: var(--Secondary);
}

/* ✅ پاراگراف‌ها: حفظ فاصله و آرایش متن */
.zo-description-section :deep(p) {
    margin: 0 0 1.2rem 0; /* فاصله بین پاراگراف‌ها */
    text-align: justify;
    line-height: 1.9;
}

/* ✅ پاراگراف خالی‌ها فقط فاصله ظریف بدهند */
.zo-description-section :deep(p:empty) {
    margin: 0;
    display: block;
    height: 0.8rem; /* فاصله بصری ملایم بین پاراگراف‌ها */
}

/* ✅ فونت‌ها و سبک هدرها */
.zo-description-section :deep(h1),
.zo-description-section :deep(h2),
.zo-description-section :deep(h3),
.zo-description-section :deep(h4),
.zo-description-section :deep(h5),
.zo-description-section :deep(h6) {
    margin: 0 0 1rem;
    font-weight: 600;
}

.zo-description-section :deep(h1) { font-size: 1.25rem; }
.zo-description-section :deep(h2) { font-size: 1.15rem; }
.zo-description-section :deep(h3) { font-size: 1.10rem; }
.zo-description-section :deep(h4) { font-size: 1.05rem; }
.zo-description-section :deep(h5) { font-size: 1rem; }
.zo-description-section :deep(h6) { font-size: .95rem; }

/* ✅ لیست‌ها */
.zo-description-section :deep(ul) {
    padding: 0 0 15px;
}

.zo-description-section :deep(ul li) {
    width: 100%;
    display: inline-block;
    padding: 1.5px 10px 1.5px 0;
    position: relative;
}

.zo-description-section :deep(ul li:before) {
    content: '';
    width: 5px;
    height: 5px;
    position: absolute;
    top: 9.5px;
    right: 0;
    background: var(--Secondary);
}

/* ✅ فونت‌های داخل span و strong */
.zo-description-section :deep(span) {
    font-size: 16px; /* هماهنگ با خروجی TipTap */
}

.zo-description-section :deep(strong) {
    font-weight: 600;
}

@media (max-width: 960px) {
    .zo-description-section {
        margin: 0 0 5px;
    }
    .zo-description-section :deep(p) {
        line-height: 1.8;
        margin-bottom: 1rem;
    }
}
</style>
