<template>
    <WebLayout>
        <PanelLayout>
            <div class="zo-dashboard-section">
                <div class="zo-courses-section">
                    <v-container>
                        <v-row dense class="align-center">
                            <v-col v-for="(course, index) in courses" :key="index" cols="12" sm="6" md="4" lg="3">
                                <CourseCard :course="course" />
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </div>
        </PanelLayout>
    </WebLayout>
</template>
<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import WebLayout from '@/Layouts/WebLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link, router, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";

// ------------------- reactive state -------------------
const page = usePage();

const query = new URLSearchParams(page.url.split('?')[1]);
const filters = ref({
    category: query.get('category') ?? 'all',
    sort: query.get('sort') ?? '',
    search: query.get('search') ?? '',
});

const categories = ref(page.props.categories || []);
const courses = ref(page.props.courses?.data || []);
const stats = ref(page.props.stats || {});
const sort = ref('desc');
const sorts = ref([
    { title: 'جدیدترین', value: 'desc' },
    { title: 'قدیمی‌ترین', value: 'asc' }
]);
const currentPage = ref(page.props.courses?.meta?.current_page || 1);
const lastPage = computed(() => page.props.courses?.meta?.last_page || 1);

const disabled = ref(false);
const isCategoryLoading = ref(false);
const isSearchLoading = ref(false);
const isSortLoading = ref(false);

let searchDebounceTimeout: ReturnType<typeof setTimeout> | null = null;
let activeSearchAbort: (() => void) | null = null;

// ------------------- functions -------------------
const search = (type: 'category' | 'sort' | 'search', value: string | null = null) => {
    const loadingMap = {
        category: isCategoryLoading,
        search: isSearchLoading,
        sort: isSortLoading,
    };

    if (type === 'sort') {
        filters.value.sort = value || '';
    }

    const executeRequest = () => {
        const controller = new AbortController();
        if (type === 'search') activeSearchAbort = () => controller.abort();

        disabled.value = true;
        loadingMap[type].value = true;

        router.get(route('web.courses.index'), {
            ...filters.value,
            page: 1,
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['courses'],
            signal: controller.signal,
            onFinish: () => {
                loadingMap[type].value = false;
                disabled.value = false;
                if (type === 'search') activeSearchAbort = null;
            },
        });
    };

    if (type === 'search') {
        clearTimeout(searchDebounceTimeout!);
        if (activeSearchAbort) activeSearchAbort();

        searchDebounceTimeout = setTimeout(executeRequest, 1000);
    } else {
        try {
            executeRequest();
        } catch (error) {
            console.error('خطا در دریافت اطلاعات:', error);
            loadingMap[type].value = false;
            disabled.value = false;
        }
    }
};

const changePage = async (pageNumber: number) => {
    try {
        const query = {
            ...filters.value,
            page: pageNumber
        };

        router.get(route('web.courses.index'), query, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['courses'],
            onSuccess: () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    } catch (error) {
        console.error('خطا در دریافت اطلاعات:', error);
    }
};

// ------------------- watchers -------------------
watch(() => filters.value.category, (newVal) => {
    if (!newVal) {
        filters.value.category = 'all';
    }
});

watch(() => page.props.courses, (newVal) => {
    courses.value = newVal?.data || [];
    currentPage.value = newVal?.meta?.current_page || 1;
});
</script>
