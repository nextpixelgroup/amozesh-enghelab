<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <div class="zo-info d-lg-flex d-sm-none">
                <div class="zo-icon elevation-4">
                    <i class="mdi mdi-bell-ring"></i>
                </div>
                <div class="zo-name">
                    <strong class="d-block mb-1">تماس‌باما</strong>
                    <span>در این بخش می توانید پیام ها را مدیریت کنید.</span>
                </div>
            </div>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-row dense class="align-center">
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select
                        v-model="filters.type"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="نوع"
                        :items="types"
                        clearable
                        @update:model-value="(val) => val === null && handleClear('type')"
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
                    <th class="text-center">نام</th>
                    <th class="text-center">شماره موبایل</th>
                    <th class="text-center">پست الکترونیک</th>
                    <th class="text-center">تاریخ ارسال</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in messages"
                    :key="item.id"
                >
                    <td class="text-center">{{ item.id }}</td>
                    <td class="text-center">
                        <strong>{{ item.name }}</strong>
                    </td>
                    <td class="text-center">
                        {{ item.mobile }}
                    </td>
                    <td class="text-center">
                        {{ item.email }}
                    </td>
                    <td>
                        <span class="d-block">{{ item.created_at.title.split(' ')[0] }}</span>
                        <small>{{ item.created_at.title.split(' ')[1] }}</small>
                    </td>
                    <td class="text-center">
                        <span v-if="item.read_at.value" class="'zo-status zo-archive'">آرشیو</span>
                        <span v-else :class="'zo-status zo-unread'">خوانده نشده</span>
                    </td>
                    <td>
                        <div class="d-flex justify-center ga-1">
                            <v-tooltip text="نمایش پیام" location="top">
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        icon="mdi-eye"
                                        size="small"
                                        color="primary"
                                        @click="openDialog(item)"
                                        v-bind="props"
                                    >
                                    </v-btn>
                                </template>
                            </v-tooltip>
                            <v-tooltip location="top" text="آرشیو پیام">
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        icon="mdi-archive"
                                        size="small"
                                        color="secondary"
                                        v-bind="props"
                                        :loading="loadingItems[item.id]"
                                        :disabled="isLoading"
                                        @click="archive(item)"
                                    />
                                </template>
                            </v-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination
                rounded="circle"
                v-if="props.message?.meta.last_page > 1"
                v-model="currentPage"
                :length="props.message?.meta.last_page"
                @update:model-value="changePage"
                class="mt-4"
            />
        </v-card>
        <v-dialog v-model="dialog" max-width="600">
            <v-card>
                <v-card-title class="d-flex justify-space-between align-center">
                    <strong>متن پیام</strong>
                    <v-btn icon flat size="small" @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>{{ message }}</v-card-text>
            </v-card>
        </v-dialog>
    </AdminLayout>
</template>
<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3'
import {computed, ref, watch} from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import usePageTitle from "@/Composables/usePageTitle.js";
const {adminPageTitle} = usePageTitle('تماس‌باما');

const props = defineProps({
    messages: Object
})
const currentPage = ref(props.messages?.meta.current_page)
const loadingItems = ref({})
const isLoading = ref(false)
const isLoadingBtn = ref(false)
console.log(props.messages)
const messages = computed(() => props.messages.data)
const dialog = ref(false)
const message = ref('')
const types = [
    {
        'title': 'همه',
        'value': 'all',
    },
    {
        'title': 'آرشیو',
        'value': 'read',
    }
]

const page = usePage();
const query = new URLSearchParams(page.url.split('?')[1])
const filters = ref({
    type: query.get('type') ?? '',
    search: query.get('search') ?? '',
});
const search = () => {
    isLoadingBtn.value = true;
    try {
        router.get(route('admin.contacts.index'),
            {
                ...filters.value,
                page: 1 // همیشه به صفحه اول برود
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['messages'],
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

function openDialog(item) {
    message.value = item.message
    dialog.value = true
}

const archive = async (item) => {

    const confirm = await $confirm('آیا از آرشیو پیام اطمینان دارید؟');
    if (confirm) {
        try {
            isLoading.value = true
            router.put(route('admin.contacts.archive', item.id), {}, {
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

        router.get(route('admin.contacts.index'),
            query,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['messages'],
                onSuccess: () => {
                    window.scrollTo({top: 0, behavior: 'smooth'});
                }
            }
        );
    } catch (error) {
        console.error('خطا در دریافت اطلاعات:', error);
    }
};

watch(() => props.messages, (newVal) => {
    messages.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});
</script>
