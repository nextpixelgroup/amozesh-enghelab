<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9 v-col-12">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-account-circle"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">لیست کاربران</strong>
                            <span>در این بخش می توانید کاربران را مدیریت کنید.</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12 text-left">
                    <Link :href="route('admin.users.create')">
                        <v-btn prepend-icon="$plus" class="zo-add" color="primary">افزودن کاربر</v-btn>
                    </Link>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-row dense class="align-center">
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select
                        hide-details
                        v-model="filters.status"
                        variant="outlined"
                        density="compact"
                        label="وضعیت"
                        :items="status"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('status')"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select
                        v-model="filters.role"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="نقش کاربری"
                        :items="roles"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('role')"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-5 v-col-12">
                    <v-text-field
                        v-model="filters.search"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو کاربر"
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
                    >
                        جستجو
                    </v-btn>
                </v-col>
            </v-row>
        </v-card>
        <v-card class="pa-3 elevation-2">
            <v-table :loading="isLoading">
                <thead>
                <tr>
                    <th class="text-center">شناسه</th>
                    <th class="text-center">نام</th>
                    <th class="text-center">نقش کاربری</th>
                    <th class="text-center">شماره همراه</th>
                    <th class="text-center">ایمیل</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in users"
                    :key="item.name"
                >
                    <td class="text-center">{{ item.id }}</td>
                    <td class="text-center">
                        <strong class="d-block">{{ item.fullname }}</strong>
                    </td>
                    <td class="text-center">
                        {{ item.role.title }}
                    </td>
                    <td class="text-center">
                        {{ item.mobile ?? '-' }}
                    </td>
                    <td class="text-center">
                        {{ item.email ?? '-' }}
                    </td>
                    <td class="text-center">
                        <span :class="`zo-status status-${item.status.value}`">{{ item.status.title }}</span>
                    </td>
                    <td class="text-center">
                        <Link :href="route('admin.users.edit', item.id)">
                            <v-btn icon="mdi-eye" size="small" color="primary"></v-btn>
                        </Link>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination
                rounded="circle"
                v-if="props.users?.meta.last_page > 1"
                v-model="currentPage"
                :length="props.users?.meta.last_page"
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
const {adminPageTitle} = usePageTitle('کاربران');

const page = usePage();
const query = new URLSearchParams(page.url.split('?')[1])

// Props
const props = defineProps({
    users: Object,
    roles: Object,
    restrict: Object,
})
const currentPage = ref(props.users?.meta.current_page)
const users = ref(props.users.data);
const roles = ref(props.roles);
const status = ref([
    {
        'title': 'بن شده',
        'value': 'ban',
    }
]);
const restrict = ref(props.restrict);

const filters = ref({
    role: query.get('role') ?? '',
    search: query.get('search') ?? '',
});
const isLoading = ref(false)
const search = () => {
    isLoading.value = true;
    //users.value = [];
    try {
        router.get(route('admin.users.index'),
            {
                ...filters.value,
                page: 1 // همیشه به صفحه اول برود
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['users'],
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

        router.get(route('admin.users.index'),
            query,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['users'],
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

watch(() => props.users, (newVal) => {
    users.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});

</script>
