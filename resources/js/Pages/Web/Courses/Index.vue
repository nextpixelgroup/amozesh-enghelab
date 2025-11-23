<template>
    <WebLayout>
        <div class="zo-courses-section">
            <div class="zo-content">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="6" cols="12">
                            <div class="zo-text">
                                <h1>دوره‌های آموزشی</h1>
                                <p>
                                    در این بخش توضیحات مربوط به دوره‌ها یا اطلاعاتی کلی که اعتماد ساز در زمینه دوره‌ها
                                    باشد قرار می‌گیرد. همچنین می‌توانید متن توضیحاتی مربوط به اساتید یا دوره‌هایی که در
                                    گذشته برگزار شده قرار دهید که اعداد و آماری که در مقابل نمایش داده شده است، بهتر درک
                                    شود.
                                </p>
                            </div>
                        </v-col>
                        <v-col lg="6" cols="12">
                            <div class="zo-stats">
                                <v-row class="align-center">
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="assets/img/site/stat-course.svg" alt="" class="img-fluid"/>
                                            <strong>{{stats.courses}}</strong>
                                            <span>دوره‌های آموزشی</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="assets/img/site/stat-star.svg" alt="" class="img-fluid"/>
                                            <strong>{{stats.ratings}}</strong>
                                            <span>میانگین امتیازات دوره‌ها</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="assets/img/site/stat-students.svg" alt="" class="img-fluid"/>
                                            <strong>{{stats.students}}</strong>
                                            <span>کل شرکت کنندگان</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="assets/img/site/stat-lessons.svg" alt="" class="img-fluid"/>
                                            <strong>{{stats.seasons}}</strong>
                                            <span>سرفصل آموزشی</span>
                                        </div>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-actions">
                <v-container class="py-1">
                    <v-row class="align-center">
                        <v-col md="8" cols="12">
                            <div class="zo-search">
                                <div class="zo-select">
                                    <v-select
                                        v-model="filters.category"
                                        :label="filters.category ? '' : 'دسته‌بندی'"
                                        :items="categories"
                                        item-title="title"
                                        item-value="value"
                                        variant="solo"
                                        :clearable="filters.category !== 'all'"
                                        hide-details
                                        @update:model-value="search('category')"
                                        :loading="isCategoryLoading"
                                        :disabled="disabled"
                                    />
                                </div>
                                <div class="zo-input">
                                    <v-text-field
                                        v-model="filters.search"
                                        hide-details
                                        placeholder="جستجو"
                                        variant="solo"
                                        @update:model-value="search('search')"
                                        :loading="isSearchLoading"
                                    />
                                    <span class="zo-icon">
                                        <img src="assets/img/site/c-search.svg" alt="" class="img-fluid"/>
                                    </span>
                                </div>
                            </div>
                        </v-col>
                        <v-col md="4" cols="12">
                            <div class="zo-sort">
                                <v-menu :disabled="disabled">
                                    <template #activator="{ props }">
                                        <v-btn
                                            v-bind="props"
                                            variant="tonal"
                                            icon="mdi-filter-variant"
                                            class="w-10 h-10"
                                            :loading="isSortLoading"
                                        ></v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-item
                                            v-for="(item, index) in sorts"
                                            :key="index"
                                            :value="index"
                                            v-model="sort"
                                            @click="search('sort', item.value)"
                                            :class="{ 'bg-primary text-white': filters.sort === item.value }"
                                        >
                                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <v-container>
                <v-row class="align-center">
                    <v-col
                        v-for="(course, index) in courses"
                        :key="index"
                        cols="12"
                        sm="6"
                        md="4"
                        lg="3"
                    >
                        <CourseCard :course="course"/>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <Pagination
                            v-model="currentPage"
                            :length="lastPage"
                            @changePage="changePage"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>

<script setup>
import {computed, ref, watch} from 'vue'
import WebLayout from '@/Layouts/WebLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import {Link, router, usePage} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";
const page = usePage();
const query = new URLSearchParams(page.url.split('?')[1])
const filters = ref({
    category: query.get('category') ? query.get('category') : 'all',
    sort: query.get('sort') ?? '',
    search: query.get('search') ?? '',
});
const categories = ref(page.props.categories);
const courses = computed( () => page.props.courses.data);
const stats = ref(page.props.stats);
const sort = ref('desc');
const sorts = ref([{title: 'جدیدترین', value: 'desc'}, {title: 'قدیمی‌ترین', value: 'asc'}])
const currentPage = ref(page.props.courses?.meta.current_page)
const lastPage = computed( () => page.props.courses?.meta.last_page)


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

        router.get(route('web.courses.index'),
            {
                ...filters.value,
                page: 1,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['courses'],
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

        router.get(route('web.courses.index'),
            query,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['courses'],
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
watch(() => page.props.courses, (newVal) => {
    courses.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});
</script>
<style>

</style>
