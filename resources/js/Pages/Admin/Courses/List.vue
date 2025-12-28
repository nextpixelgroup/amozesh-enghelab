<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-laptop"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">دوره‌های آموزشی</strong>
                            <span>در این بخش می توانید دوره‌های آموزشی را مدیریت کنید.</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12 text-left">
                    <Link :href="route('admin.courses.create')">
                        <v-btn prepend-icon="$plus" class="zo-add" color="primary">افزودن دوره</v-btn>
                    </Link>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-row dense class="align-center">
                <v-col class="v-col-lg-2 v-col-12">
                    <v-select
                        v-model="filters.status"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="وضعیت انتشار"
                        :items="status"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('status')"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-2 v-col-12">
                    <v-select
                        v-model="filters.teacher"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو مدرس"
                        :items="teachers"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('teacher')"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select
                        v-model="filters.category"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="دسته‌بندی‌ها"
                        :items="categories"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('category')"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-4 v-col-12">
                    <v-text-field
                        v-model="filters.search"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو عنوان دوره"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('category')"
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-1 v-col-12">
                    <v-btn
                        block
                        variant="outlined"
                        color="primary"
                        @click="search"
                        :loading="isLoading">جستجو
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
                    <th class="text-center">مدرس</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">دانشجو</th>
                    <th class="text-center">تاریخ انتشار</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in courses"
                    :key="item.id"
                >
                    <td class="text-center">{{ item.id }}</td>
                    <td class="text-center">
                        <img :src="item.thumbnail.url ?? '/assets/img/default.svg'" :alt="item.title" width="90"
                             height="90">
                    </td>
                    <td>
                        <strong class="d-block">{{ item.title }}</strong>
                        <span class="d-lg-block d-sm-none">{{ item.subtitle }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-center ga-3">
                            <v-avatar>
                                <v-img
                                    :alt="item.title"
                                    :src="item.teacher?.avatar.url ?? '/assets/img/default-teacher.svg'"
                                ></v-img>
                            </v-avatar>
                            <div>
                                <strong class="d-block">{{ item.teacher?.fullname }}</strong>
                                <small v-if="item.teacher?.degree">{{ item.teacher?.degree }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span :class="'zo-status zo-'+item.status.value">{{ item.status.title }}</span>
                    </td>
                    <td class="text-center">
                        {{ item.member }}
                    </td>
                    <td class="text-center">
                        <span>{{ item.published_at.title.split(' ')[0] }} - </span>
                        <span>{{ item.published_at.title.split(' ')[1] }}</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-center ga-1">
                            <Link :href="route('admin.courses.edit',item.id)">
                                <v-btn icon="mdi-pencil" size="small" color="primary"></v-btn>
                            </Link>
                            <a :href="route('web.courses.show',item.slug)" target="_blank">
                                <v-btn icon="mdi-eye" size="small" color="rgb(105, 5, 50)"></v-btn>
                            </a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination
                rounded="circle"
                v-if="length > 1"
                v-model="currentPage"
                :length="length"
                @update:model-value="changePage"
                class="mt-4"
            />
        </v-card>
    </AdminLayout>
</template>
<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3'
import {computed, ref, watch} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import usePageTitle from "@/Composables/usePageTitle.js";

const {adminPageTitle} = usePageTitle('دوره‌های آموزشی');
const props = defineProps({
    courses: Object,
    status: Object,
    teachers: Object,
    categories: Object,
})
const currentPage = computed( () => props.courses?.meta.current_page)
const length = computed( () => props.courses?.meta.last_page)
const courses = computed(() => props.courses.data);
const status = ref(props.status)
const teachers = ref(props.teachers)
const categories = ref(props.categories)
const isLoading = ref(false)
const page = usePage();
const query = new URLSearchParams(page.url.split('?')[1])
const filters = ref({
    status: query.get('status') ?? '',
    teacher: query.get('teacher') ?? '',
    category: query.get('category') ?? '',
    search: query.get('search') ?? '',
});
const search = () => {
    isLoading.value = true;
    try {
        router.get(route('admin.courses.index'),
            {
                ...filters.value,
                page: 1 // همیشه به صفحه اول برود
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['courses'],
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

        router.get(route('admin.courses.index'),
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

const handleClear = (field) => {
    filters.value[field] = null;
    search();
};

watch(() => props.courses, (newVal) => {
    courses.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});
</script>
<style scoped>
    img {
        display: block;
        margin: 5px auto
    }
</style>