<template>
    <Link :href="book.url" class="zo-book">
        <v-row dense class="align-end">
            <v-col md="5" cols="12">
                <img :src="book.thumbnail" alt="">
            </v-col>
            <v-col md="6" cols="12">
                <div class="zo-content">
                    <h2>{{book.title}}</h2>
                    <div class="zo-meta">
                        <div class="zo-star">
                            <i class="mdi mdi-star-outline"></i>
                            <span>{{book.rate}}</span>
                        </div>
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
                    </div>
                    <div class="zo-add">
                        <i class="mdi mdi-cart-outline"></i>
                        <span>خرید آنلاین</span>
                    </div>
                </div>
            </v-col>
        </v-row>
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
