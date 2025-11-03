<template>
    <h1>{{book.title}}</h1>
    <h2>{{book.subtitle}}</h2>
    <p v-html="book.content"></p>
    <p>{{book.price}}</p>
    <p>{{book.special_price}}</p>
    <p>{{book.publisher}}</p>
    <p>{{book.year_published}}</p>
    <p>{{book.size}}</p>
    <p>{{book.edition}}</p>
    <p>{{book.author}}</p>
    <p>{{book.is_stock}}</p>
    <p>{{book.stock}}</p>
    <p>{{book.max_order}}</p>
    <p>{{book.status}}</p>
    <p>{{book.category}}</p>
    <p>{{book.thumbnail_id}}</p>
</template>

<script setup>
const props = defineProps({
    book: Object
});
const book = props.book;
</script>
