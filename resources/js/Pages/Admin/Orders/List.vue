<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-12">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-cart"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">لیست سفارش‌ها</strong>
                            <span>در این بخش می توانید سفارشات را مدیریت کنید.</span>
                        </div>
                    </div>
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
                    />
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
                    />
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
                    <th class="text-center">شماره سفارش</th>
                    <th class="text-center">نام و نام خانوادگی</th>
                    <th class="text-center">شماره</th>
                    <th class="text-center">مبلغ</th>
                    <th class="text-center">تعداد</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">تاریخ</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in orders" :key="order.id">
                    <td class="text-center">{{order.reference_id}}</td>
                    <td class="text-center"><strong>{{order.user.fullname}}</strong></td>
                    <td class="text-center">{{ order.user.mobile }}</td>
                    <td class="text-center">
                        <div class="zo-price">
                            <del v-if="order.original_total > order.total - order.shipping_cost">{{numberFormat(order.original_total)}}</del>
                            <div class="zo-sale">
                                <strong class="pe-1">{{numberFormat(order.total-order.shipping_cost)}}</strong>
                                <small>تومان</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">{{order.qty}}</td>
                    <td class="text-center">
                        <span :class="`zo-status zo-${order.status.value}`">
                            {{order.status.title}}
                        </span>
                    </td>
                    <td class="text-center">
                        {{order.created_at.title.split(' ')[0]}}
                        <br>
                        {{order.created_at.title.split(' ')[1]}}
                    </td>
                    <td class="text-center">
                        <Link :href="order.url">
                            <v-btn icon="mdi-eye" size="small" color="primary"></v-btn>
                        </Link>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination
                rounded="circle"
                v-if="props.orders?.meta.last_page > 1"
                v-model="currentPage"
                :length="props.orders?.meta.last_page"
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
import usePageTitle from "@/Composables/usePageTitle.js";
import {numberFormat} from "../../../utils/helpers.js";
import {route} from "ziggy-js";
const {adminPageTitle} = usePageTitle('سفارشات');
const props = defineProps({
    orders: Object,
    status: Object,
})
const orders = computed( () => props.orders.data);

const currentPage = ref(props.orders?.meta.current_page)
const page = usePage();
const query = new URLSearchParams(page.url.split('?')[1])
const filters = ref({
    status: query.get('status') ?? '',
    search: query.get('search') ?? '',
});
const isLoading = ref(false)
const search = () => {
    isLoading.value = true;
    try {
        router.get(route('admin.orders.index'),
            {
                ...filters.value,
                page: 1 // همیشه به صفحه اول برود
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['orders'],
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

        router.get(route('admin.orders.index'),
            query,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['orders'],
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

watch(() => props.orders, (newVal) => {
    orders.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});
</script>
