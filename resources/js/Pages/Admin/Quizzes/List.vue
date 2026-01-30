<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <div class="zo-info d-lg-flex d-sm-none">
                <div class="zo-icon elevation-4">
                    <i class="mdi mdi-message-text-fast"></i>
                </div>
                <div class="zo-name">
                    <strong class="d-block mb-1">آزمون‌ها</strong>
                    <span>در این بخش می توانید آزمون‌ها را مدیریت کنید.</span>
                </div>
            </div>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-row dense class="align-center">
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select
                        v-model="filters.status"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="وضعیت"
                        :items="status"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('status')"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-8 v-col-12">
                    <v-text-field
                        v-model="filters.search"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('search')"
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-1 v-col-12">
                    <v-btn
                        block
                        ariant="outlined"
                        color="primary"
                        @click="search"
                        :loading="isLoadingBtn"
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
                    <th class="text-center">دوره</th>
                    <th class="text-center">کاربر</th>
                    <th class="text-center">درخواست گواهینامه</th>
                    <th class="text-center">تاریخ</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in quizzes"
                    :key="item.id"
                >
                    <td class="text-center">{{ item.id }}</td>
                    <td class="text-center">
                        {{ item.course.title }}
                    </td>
                    <td class="text-center">
                        <strong>{{ item.user?.name }}</strong>
                    </td>
                    <td class="text-center">
                        <v-icon color="green" v-if="item.requestedCertificate">mdi mdi-check</v-icon>
                        <v-icon color="red" v-else>mdi mdi-close</v-icon>
                    </td>
                    <td>
                        <span class="d-block">{{ item.created_at.title.split(' ')[0] }}</span>
                        <small>{{ item.created_at.title.split(' ')[1] }}</small>
                    </td>
                    <td class="text-center">
                        <span :class="'zo-status zo-'+item.status.value">{{ item.status.title }}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-center ga-1">
                            <v-tooltip text="نمایش" location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('admin.quizzes.edit',item.id)">
                                        <v-btn
                                            icon="mdi-eye"
                                            size="small"
                                             color="rgb(105, 5, 50)"
                                            v-bind="props"
                                        >
                                        </v-btn>
                                    </Link>
                                </template>
                            </v-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination
                rounded="circle"
                v-if="props.tickets?.meta.last_page > 1"
                v-model="currentPage"
                :length="props.tickets?.meta.last_page"
                @update:model-value="changePage"
                class="mt-4"
            />
        </v-card>
    </AdminLayout>
</template>
<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3'
import {computed, ref, watch} from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import usePageTitle from "@/Composables/usePageTitle.js";
const {adminPageTitle} = usePageTitle('آزمون‌ها');

const props = defineProps({
    quizzes: Object,
    status: Object,
})
const currentPage = ref(props.quizzes?.meta.current_page)
const quizzes = computed(() => props.quizzes.data)
const dialog = ref(false)
const isLoadingBtn = ref(false)
const message = ref('')
const status = ref(props.status)

const page = usePage();
const query = new URLSearchParams(page.url.split('?')[1])
const filters = ref({
    search: query.get('search') ?? '',
});
const search = () => {
    isLoadingBtn.value = true;
    try {
        router.get(route('admin.quizzes.index'),
            {
                ...filters.value,
                page: 1 // همیشه به صفحه اول برود
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['quizzes'],
                onFinish: () => {
                    isLoadingBtn.value = false;
                }
            },
        );
    } catch (error) {
        console.error('خطا در دریافت اطلاعات:', error);
    }
};
const handleClear = (field) => {
    filters.value[field] = null;
    search();
};


const archive = async (item) => {

    const confirm = await $confirm('آیا از آرشیو پیام اطمینان دارید؟');
    if (confirm) {
        try {
            isLoading.value = true
            router.put(route('admin.tickets.archive', item.id), {}, {
                preserveScroll: true,
                preserveState: true,
                onStart: () => {
                    loadingItems.value = {...loadingItems.value, [item.id]: true}
                },
                onSuccess: () => {
                },
                onFinish: () => {
                    const newLoading = {...loadingItems.value}
                    delete newLoading[item.id]
                    loadingItems.value = newLoading
                    isLoading.value = false
                }
            })
        } catch (error) {
            console.error('Error Archive:', error)
        }
    }

}

const changePage = async (page) => {
    try {
        const query = {
            ...filters.value,  // Keep existing filters
            page  // Update only the page number
        };

        router.get(route('admin.tickets.index'),
            query,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['tickets'],
                onSuccess: () => {
                    window.scrollTo({top: 0, behavior: 'smooth'});
                }
            }
        );
    } catch (error) {
        console.error('خطا در دریافت اطلاعات:', error);
    }
};

watch(() => props.tickets, (newVal) => {
    tickets.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});
</script>
<style scoped>
    .zo-completed {
        background: rgba(5, 105, 60, .05);
        color: rgb(5, 105, 60);
        border: 1px solid rgb(5, 105, 60)
    }
    .zo-review {
        background: rgba(200, 160, 100, .05);
        color: rgb(200,160,100);
        border: 1px solid rgb(200,160,100)
    }
</style>
