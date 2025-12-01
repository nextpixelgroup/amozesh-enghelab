<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9 v-col-12">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-page-layout-sidebar-right"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">لیست برگه‌ها</strong>
                            <span>در این بخش می توانید برگه‌ها را مدیریت کنید.</span>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-3 elevation-2">
            <v-table :loading="isLoading">
                <thead>
                <tr>
                    <th class="text-center">شناسه</th>
                    <th class="text-center">عنوان</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in pages"
                    :key="item.name"
                >
                    <td class="text-center">{{item.id}}</td>
                    <td class="text-center">
                        <strong class="d-block">{{item.title}}</strong>
                    </td>
                    <td class="text-center">
                        <span :class="`zo-status zo-${item.status.value}`">{{ item.status.title }}</span>
                    </td>
                    <td class="text-center">
                        <Link :href="route('admin.pages.edit', item.id)">
                            <v-btn icon="mdi-eye" size="small" color="primary"></v-btn>
                        </Link>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination
                rounded="circle"
                v-if="props.pages?.meta.last_page > 1"
                v-model="currentPage"
                :length="props.pages?.meta.last_page"
                @update:model-value="changePage"
                class="mt-4"
            />
        </v-card>
    </AdminLayout>
</template>
<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3'
import {ref, watch} from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import usePageTitle from "@/Composables/usePageTitle.js";
const {adminPageTitle} = usePageTitle('برگه ها');

// Props
const props = defineProps({
    pages: Object,
})
const currentPage = ref(props.pages?.meta.current_page)
const pages = ref(props.pages.data);

const filters = ref();
const isLoading = ref(false)
const search = () => {
    isLoading.value = true;
    //pages.value = [];
    try {
        router.get(route('admin.pages.index'),
            {
                ...filters.value,
                page: 1 // همیشه به صفحه اول برود
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['pages'],
                onFinish: () => {
                    isLoading.value = false;
                }
            },
        );
    } catch (error) {
        console.error('خطا در دریافت اطلاعات:', error);
    }
};

const changePage = async (page) => {
    try {
        const query = {
            ...filters.value,  // Keep existing filters
            page  // Update only the page number
        };

        router.get(route('admin.pages.index'),
            query,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['pages'],
                onSuccess: () => {
                    window.scrollTo({top: 0, behavior: 'smooth'});
                }
            }
        );
    } catch (error) {
        console.error('خطا در دریافت اطلاعات:', error);
    }
};

const handleClear = (field) => {
    filters.value[field] = null;
    search();
};

watch(() => props.pages, (newVal) => {
    pages.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});

</script>

