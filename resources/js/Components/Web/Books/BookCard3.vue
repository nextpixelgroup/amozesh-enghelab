<template>
    <Link :href="book.url" class="zo-book">
    <div class="zo-content">
        <div class="zo-thumbnails">
            <div class="zo-back">
                <img :src="book.thumbnail" alt="" class="img-fluid">
            </div>
            <div class="zo-front">
                <img :src="book.thumbnail" alt="" class="img-fluid">
            </div>
        </div>
        <div class="zo-text">
            <v-tooltip :text="book.title" location="top">
                <template v-slot:activator="{ props }">
                    <h2>{{book.title}}</h2>
                </template>
            </v-tooltip>
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
import { Link } from "@inertiajs/vue3";

defineProps({
    book: {
        type: Object,
        required: true
    }
})

</script>
<style scoped>
.zo-book {
    display: block;
    padding: 60px 0
}

.zo-book .zo-content {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 0 0 0 30px;
    position: relative;
    background: var(--White);
    border-radius: 1rem
}

.zo-book .zo-content .zo-thumbnails {
    width: 45%;
    margin: -30px 0;
    position: relative;
    text-align: center;
    z-index: 15
}

.zo-book .zo-content .zo-thumbnails .zo-back {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    transform: rotate(-10deg);
    z-index: -15
}

.zo-book .zo-content .zo-thumbnails .zo-front {
    position: relative;
    transform: rotate(10deg);
    z-index: 15
}

.zo-book .zo-content h2 {
    display: block;
    margin: 0 0 2.5px;
    font-size: 1.25rem;
    color: var(--Primary)
}

.zo-book .zo-content .zo-sub {
    display: block;
    font-size: .90rem;
    margin: 0 0 10px;
    color: var(--Primary)
}

.zo-book .zo-content p {
    margin: 0 0 10px;
    font-size: .95rem;
    text-align: justify;
    color: var(--Sub)
}

.zo-book .zo-content .zo-meta {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin: 15px 0 0
}

.zo-book .zo-content .zo-price {
    display: flex;
    align-items: center;
    gap: 2.5px
}

.zo-book .zo-content .zo-meta .zo-price .zo-regular {
    position: relative;
    font-size: .90rem
}

.zo-book .zo-content .zo-meta .zo-price .zo-regular:before {
    content: '';
    width: 100%;
    height: 1px;
    position: absolute;
    top: 10px;
    left: 0;
    background: rgba(0, 0, 0, .5);
    transform: rotate(10deg);
    opacity: .75
}

.zo-book .zo-content .zo-add {
    width: 200px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--Secondary);
    color: var(--White);
    border-radius: .75rem 0 .75rem 0
}

@media (max-width: 960px) {

    .zo-book .zo-content {
        display: block;
        margin: 15px 0;
        padding: 15px
    }

    .zo-book .zo-content .zo-thumbnails {
        width: 100%;
        margin: 0 0 15px
    }

    .zo-book .zo-content .zo-add {
        font-size: .90rem
    }
}

</style>
