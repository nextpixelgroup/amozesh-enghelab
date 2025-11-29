<template>
    <Link :href="book.url" class="zo-book">
        <div class="zo-content">
            <img :src="book.thumbnail" alt="" class="img-fluid">
            <div class="zo-text">
                <h2>{{book.title}}</h2>
                <div class="zo-sub">
                    {{book.subtitle}}
                </div>
                <p>{{book.summary}} </p>
                <div class="zo-meta">
                    <div class="zo-price" v-if="!book.is_stock">
                        <div class="zo-sale">
                            <strong>ناموجود</strong>
                        </div>
                    </div>
                    <div class="zo-price" v-else-if="book.special_price.length">
                        <div class="zo-regular">
                            <span>{{book.price}}</span>
                        </div>
                        <div class="zo-sale">
                            <strong>{{book.special_price}}</strong>
                            <small>تومان</small>
                        </div>
                    </div>
                    <div class="zo-price" v-else>
                        <div class="zo-sale">
                            <strong>{{book.price}}</strong>
                            <small>تومان</small>
                        </div>
                    </div>
                    <div class="zo-add">
                        <i class="mdi mdi-cart-outline"></i>
                        <span>خرید آنلاین</span>
                    </div>
                </div>
            </div>
        </div>
    </Link>
</template>
<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    book: {
        type: Object,
        required: true
    }
})
</script>
