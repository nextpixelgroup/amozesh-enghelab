<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-book-open-page-variant"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">لیست کتب</strong>
                            <span>در این بخش می توانید کتب را مدیریت کنید.</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12 text-left">
                    <Link :href="route('admin.books.create')">
                        <v-btn prepend-icon="$plus" class="zo-add" color="primary">افزودن کتاب</v-btn>
                    </Link>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-row dense class="align-center">
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select hide-details
                              v-model="filters.status"
                              variant="outlined"
                              density="compact"
                              label="وضعیت انتشار"
                              :items="status"
                              clearable
                              @update:model-value="(val) => val === null && handleClear('status')"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-text-field
                        v-model="filters.author"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="نویسنده"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('author')"
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-5 v-col-12">
                    <v-text-field
                        v-model="filters.search"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو عنوان کتاب"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('search')"
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-1 v-col-12">
                    <v-btn
                        block
                        variant="outlined"
                        color="primary"
                        @click="search"
                        :loading="isLoading"
                    >جستجو
                    </v-btn>
                </v-col>
            </v-row>
        </v-card>
        <v-card class="pa-3 elevation-2">
            <v-table>
                <thead>
                <tr>
                    <th class="text-center">شناسه</th>
                    <th class="text-center">تصویر</th>
                    <th class="text-right">عنوان</th>
                    <th class="text-center">نویسنده</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">قیمت</th>
                    <th class="text-center">موجودی</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in books"
                    :key="item.id"
                >
                    <td class="text-center">{{ item.id }}</td>
                    <td class="text-center">
                        <img :src="item.thumbnail.url ?? '/assets/img/default.svg'" :alt="item.title" width="90" height="135">
                    </td>
                    <td>
                        <strong class="d-block">{{ item.title }}</strong>
                        <span class="d-lg-block d-sm-none">{{ item.subtitle }}</span>
                    </td>
                    <td>
                        {{ item.author }}
                    </td>
                    <td class="text-center">
                        <span :class="'zo-status zo-'+item.status.value">{{ item.status.title }}</span>
                    </td>
                    <td class="text-center">
                        <div>
                            <template v-if="item.special_price">
                                <div class="zo-price">
                                    <del>{{ Number(item.price).toLocaleString('fa-IR') }}</del>
                                    <div class="zo-sale">
                                        <strong class="pe-1">
                                            {{ Number(item.special_price).toLocaleString('fa-IR') }}
                                        </strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div>
                                    <strong class="pe-1">{{ Number(item.price).toLocaleString('fa-IR') }}</strong>
                                    <small>تومان</small>
                                </div>
                            </template>
                        </div>
                    </td>
                    <td class="text-center">
                        {{ item.stock == null ? 'موجود' : item.stock }}
                    </td>
                    <td class="text-center">
                        <div class="d-flex ga-1">
                        <Link :href="route('admin.books.edit', item.id)">
                            <v-btn icon="mdi-pencil" size="small" color="primary"></v-btn>
                        </Link>
                        <a
                            :href="route('web.books.show', item.slug)"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <v-btn icon="mdi-eye" size="small" color="rgb(105, 5, 50)"></v-btn>
                        </a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination
                rounded="circle"
                v-if="props.books?.meta.last_page > 1"
                v-model="currentPage"
                :length="props.books?.meta.last_page"
                @update:model-value="changePage"
                class="mt-4"
            />
        </v-card>
    </AdminLayout>
</template>
<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3'
import {ref, watch} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import usePageTitle from "@/Composables/usePageTitle.js";
const {adminPageTitle} = usePageTitle('کتب');
const props = defineProps({
    books: Object,
    status: Object,
})
const currentPage = ref(props.books?.meta.current_page)
const books = ref(props.books.data)
const status = ref(props.status);

const page = usePage();
const query = new URLSearchParams(page.url.split('?')[1])
const filters = ref({
    status: query.get('status') ?? '',
    author: query.get('author') ?? '',
    search: query.get('search') ?? '',
});
const isLoading = ref(false)
const search = () => {
    isLoading.value = true;
    try {
        router.get(route('admin.books.index'),
            {
                ...filters.value,
                page: 1 // همیشه به صفحه اول برود
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['books'],
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

        router.get(route('admin.books.index'),
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

const handleClear = (field) => {
    filters.value[field] = null;
    search();
};

watch(() => props.books, (newVal) => {
    books.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});
</script>
<style scoped>
    img {
        display: block;
        margin: 5px auto
    }
</style>
