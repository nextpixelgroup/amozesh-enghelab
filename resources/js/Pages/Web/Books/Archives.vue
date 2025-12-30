<template>
    <WebLayout>
        <div class="zo-books-section">
            <v-container>
                <v-row dense>
                    <v-col cols=12>
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="/">خانه</a>
                                    </li>
                                    <li>
                                        <a :href="route('web.books.index')">فروشگاه کتاب</a>
                                    </li>
                                    <li>
                                        <span>آرشیو</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <div class="zo-books-archive-section">
                <div class="zo-actions">
                    <v-container class="py-1">
                        <v-row class="justify-space-between align-center">
                            <v-col cols="12" lg="8">
                                <v-row dense class="align-center">
                                    <div class="zo-search">
                                        <div class="zo-select">
                                            <v-select v-model="filters.category" :label="filters.category ? '' : 'دسته‌بندی'" :items="categories" item-title="title" item-value="value" variant="solo" :clearable="filters.category !== 'all'" hide-details @update:model-value="search('category')" :loading="isCategoryLoading" :disabled="disabled" />
                                        </div>
                                        <div class="zo-input">
                                            <v-text-field v-model="filters.search" hide-details placeholder="جستجو" variant="solo" @update:model-value="search('search')" :loading="isSearchLoading" />
                                            <span class="zo-icon">
                                                <img src="/assets/img/site/c-search.svg" alt="" class="img-fluid" />
                                            </span>
                                        </div>
                                    </div>
                                </v-row>
                            </v-col>
                            <v-col cols="12" lg="4">
                                <div class="zo-sort">
                                    <v-menu :disabled="disabled">
                                        <template #activator="{ props }">
                                            <v-btn v-bind="props" variant="tonal" icon="mdi-filter-variant" class="w-10 h-10" :loading="isSortLoading"></v-btn>
                                        </template>
                                        <v-list>
                                            <v-list-item v-for="(item, index) in sorts" :key="index" :value="index" v-model="sort" @click="search('sort', item.value)" :class="{ 'bg-primary text-white': filters.sort === item.value }">
                                                <v-list-item-title>{{ item.title }}</v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </div>
            <v-container>
                <v-row dense>
                    <v-col lg="3" md="6" cols="12" v-for="(book,index) in books">
                        <BookCard :book="book" />
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <Pagination v-model="currentPage" :length="lastPage" @changePage="changePage" />
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup>
import {computed, ref, watch} from 'vue'
import WebLayout from "@/Layouts/WebLayout.vue";
import BookCard from "@/Components/Web/Books/BookCard.vue";
import {router, usePage} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import {route} from "ziggy-js";
const page = usePage();

const categories = ref(page.props.categories);
const books = computed( () => page.props.books.data);

const query = new URLSearchParams(page.url.split('?')[1])
const filters = ref({
    category: query.get('category') ? query.get('category') : 'all',
    sort: query.get('sort') ?? '',
    search: query.get('search') ?? '',
});
const sort = ref('desc');
const sorts = ref([{title: 'جدیدترین', value: 'desc'}, {title: 'قدیمی‌ترین', value: 'asc'}])
const currentPage = ref(page.props.books?.meta.current_page)
const lastPage = computed( () => page.props.books?.meta.last_page)
const disabled = ref(false)
const isCategoryLoading = ref(false)
const isSearchLoading = ref(false)
const isSortLoading = ref(false)
let searchDebounceTimeout = null
let activeSearchAbort = null
const search = (type, value = null) => {
    const loadingMap = {
        category: isCategoryLoading,
        search: isSearchLoading,
        sort: isSortLoading,
    }

    // 🧩 قبل از ارسال درخواست بررسی کنیم که مقدار دسته‌بندی «پیش‌فرض» نباشه
    if (type == 'sort') {
        filters.value.sort = value
    }

    // تابع مشترک برای ارسال ریکویست
    const executeRequest = () => {
        const controller = new AbortController()
        if (type === 'search') activeSearchAbort = () => controller.abort()

        // فقط در هنگام ارسال واقعی درخواست
        disabled.value = true
        loadingMap[type].value = true

        router.get(route('web.books.archives'),
            {
                ...filters.value,
                page: 1,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['books'],
                signal: controller.signal,
                onFinish: () => {
                    loadingMap[type].value = false
                    disabled.value = false
                    if (type === 'search') activeSearchAbort = null
                },
            }
        )
    }

    if (type === 'search') {
        // ⏱ debounce + لغو درخواست قبلی
        clearTimeout(searchDebounceTimeout)
        if (activeSearchAbort) activeSearchAbort()

        searchDebounceTimeout = setTimeout(executeRequest, 1000)
    } else {
        try {
            executeRequest()
        } catch (error) {
            console.error('خطا در دریافت اطلاعات:', error)
            loadingMap[type].value = false
            disabled.value = false
        }
    }
}

const changePage = async (page) => {
    try {
        const query = {
            ...filters.value,  // Keep existing filters
            page  // Update only the page number
        };

        router.get(route('web.books.archives'),
            query,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['books'],
                onSuccess: () => {
                    window.scrollTo({top: 0, behavior: 'smooth'});
                }
            }
        );
    } catch (error) {
        console.error('خطا در دریافت اطلاعات:', error);
    }
};

watch(() => filters.value.category, (newVal) => {
    if (!newVal) {
        // وقتی کاربر دکمه‌ی clear زد
        filters.value.category = 'all'
    }
})
watch(() => page.props.books, (newVal) => {
    books.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});

</script>
<style scoped>
.zo-books-archive-section .zo-actions .zo-sort {
    text-align: left
}

.zo-books-archive-section .zo-actions .zo-sort button {
    border-radius: 0.75rem;
}

.zo-books-archive-section .zo-actions .zo-select :deep(.v-field) {
    background: var(--Secondary);
    color: var(--White);
    box-shadow: none
}

.zo-books-archive-section .zo-actions .zo-search {
    display: flex;
    gap: 10px;
    position: relative;
}

.zo-books-archive-section .zo-actions .zo-search .zo-select :deep(.v-field .v-field__input) {
    height: 40px;
    min-height: 40px;
    padding: 0 15px
}

.zo-books-archive-section .zo-actions .zo-search .zo-input {
    width: 300px;
    position: relative
}

.zo-books-archive-section .zo-actions .zo-search .zo-input :deep(.v-field) {
    background: rgb(240, 240, 240);
    box-shadow: none
}

.zo-books-archive-section .zo-actions .zo-search .zo-input :deep(.v-field .v-field__input) {
    height: 40px;
    min-height: 40px;
    padding: 0 15px
}

.zo-books-archive-section .zo-actions .zo-search .zo-input .zo-icon {
    position: absolute;
    top: 7.5px;
    left: 7.5px
}

</style>