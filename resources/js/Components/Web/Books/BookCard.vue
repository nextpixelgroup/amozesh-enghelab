<template>
    <Link :href="book.url" class="zo-book">
        <img :src="book.thumbnail" alt="">
        <div class="zo-content">
            <h2>{{book.title}}</h2>
            <div class="zo-meta">
                <div class="zo-star">
                    <i class="mdi mdi-star-outline"></i>
                    <span>{{ book.rate }}</span>
                </div>
                <div class="zo-price">
                    <strong>2.450.000</strong>
                    <small>تومان</small>
                </div>
            </div>
            <div class="zo-add">
                <i class="mdi mdi-cart-outline"></i>
                <span>خرید آنلاین</span>
            </div>
        </div>
    </Link>
</template>
<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    book: Object
})
</script>
