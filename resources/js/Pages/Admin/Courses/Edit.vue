<template>
    <SearchableSortableList
        v-model="selectedBooks"
        :items="bookItems"
        label="جستجوی دوره‌ها..."
        empty-message="هیچ پیش‌نیازی انتخاب نشده است."
    />
    <v-btn @click="add"/>
</template>

<script setup>
import SearchableSortableList from '@/components/SearchableSelect.vue'
import {ref} from "vue";

const bookItems = [
    { value: 1, title: 'انقلاب اسلامی ایران — دکتر منوچهر محمدی' },
    { value: 2, title: 'تحلیل انقلاب اسلامی ایران — دکتر محسن حمیدزاده' },
    { value: 3, title: 'ریشه‌های انقلاب ایران — نیکی آر. کدی' },
    // ...
]

const selectedBooks = ref([])
const add = () => {
    console.log(selectedBooks.value)
}
</script>
