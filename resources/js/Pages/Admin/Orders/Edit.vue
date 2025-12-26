<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <!-- هدر سفارش -->
        <div class="mb-5">
            <v-card class="d-flex align-center px-4 py-3 bg-white" border elevation="1">
                <v-avatar color="primary" variant="tonal" class="rounded me-4">
                    <v-icon>mdi-cart</v-icon>
                </v-avatar>
                <div>
                    <h2 class="text-h6 font-weight-bold">
                        جزئیات سفارش <span class="text-primary">{{ orderData.reference_id }}</span>
                    </h2>
                    <div class="text-caption text-grey-darken-1 mt-1">
                        {{ orderData.user.fullname }} | {{ orderDate }}
                    </div>
                </div>
                <v-spacer></v-spacer>
                <!-- نمایش وضعیت فعلی -->
                <v-chip :color="getStatusColor(orderData.status)" label variant="flat" size="small">
                    {{  props.status.filter( (status) => status.value == orderData.status)[0].title }}
                </v-chip>
            </v-card>
        </div>

        <v-row>
            <!-- ستون محتوا (جدول و اطلاعات) -->
            <v-col cols="12" lg="9">
                <!-- جدول محصولات -->
                <v-card class="elevation-2 mb-4" border>
                    <v-table hover>
                        <thead class="bg-grey-lighten-4">
                        <tr>
                            <th class="text-center">شناسه</th>
                            <th class="text-start">کتاب</th>
                            <th class="text-center">قیمت واحد</th>
                            <th class="text-center">تعداد</th>
                            <th class="text-center">قیمت کل</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in orderData.products" :key="product.id">
                            <td class="text-center text-grey">{{ product.id }}</td>
                            <td>
                                <div class="font-weight-bold">{{ product.title }}</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-center">
                                    <del v-if="product.price > product.sale_price" class="text-caption text-error">
                                        {{ numberFormat(product.price) }}
                                    </del>
                                    <span class="font-weight-bold">{{ numberFormat(product.sale_price) }} <small class="text-grey">تومان</small></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <v-chip size="x-small" variant="outlined">{{ product.qty }}</v-chip>
                            </td>
                            <td class="text-center font-weight-bold">
                                {{ numberFormat(product.sale_price * product.qty) }} <small class="text-grey">تومان</small>
                            </td>
                        </tr>
                        </tbody>
                        <!-- فوتر جدول (جمع کل) -->
                        <tfoot class="bg-grey-lighten-5">
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-center text-caption">تعداد کل: <strong>{{ totalQty }}</strong></td>
                            <td class="text-center">
                                <div class="d-flex justify-space-between px-4 py-1">
                                    <span class="text-grey-darken-2">جمع کل:</span>
                                    <strong>{{ numberFormat(orderData.total - orderData.shipping_cost) }}</strong>
                                </div>
                                <div class="d-flex justify-space-between px-4 py-1">
                                    <span class="text-grey-darken-2">هزینه ارسال:</span>
                                    <strong>{{ numberFormat(orderData.shipping_cost) }}</strong>
                                </div>
                                <v-divider class="my-1"></v-divider>
                                <div class="d-flex justify-space-between px-4 py-2 text-primary bg-primary-lighten-5 rounded">
                                    <span>پرداختی نهایی:</span>
                                    <strong class="text-h6">{{ numberFormat(orderData.total) }} <small class="text-body-2">تومان</small></strong>
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                    </v-table>
                </v-card>

                <!-- اطلاعات خریدار و ارسال (بخش بازنویسی شده) -->
                <v-card class="pa-4" elevation="1" border>
                    <v-row dense>
                        <v-col
                            v-for="(info, index) in infoItems"
                            :key="index"
                            cols="12"
                            :md="info.cols || 4"
                            sm="6"
                        >
                            <div class="d-flex align-start ga-3 pa-2 rounded hover-bg">
                                <v-icon :color="info.color || 'teal-darken-2'" size="large">{{ info.icon }}</v-icon>
                                <div>
                                    <small class="d-block text-grey-darken-1">{{ info.label }}</small>
                                    <strong class="text-body-2">{{ info.value || '---' }}</strong>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>

            <!-- ستون سایدبار (مدیریت) -->
            <v-col cols="12" lg="3">
                <div class="sticky-sidebar">
                    <!-- کارت اقدام مدیریتی -->
                    <v-card elevation="2" border>
                        <v-card-item class="bg-blue-grey-lighten-5 py-3">
                            <template v-slot:prepend>
                                <v-icon color="blue-grey-darken-2">mdi-shield-edit</v-icon>
                            </template>
                            <v-card-title class="text-subtitle-1 font-weight-bold text-blue-grey-darken-3">
                                اقدام مدیریتی
                            </v-card-title>
                        </v-card-item>
                        <v-card-text class="pa-4">
                            <v-select
                                v-model="form.status"
                                :items="status"
                                variant="outlined"
                                density="compact"
                                label="تغییر وضعیت سفارش"
                                bg-color="white"
                                class="mb-4"
                                :disabled="form.processing"
                            />
                            <v-textarea
                                v-model="form.note"
                                variant="outlined"
                                density="comfortable"
                                label="یادداشت مدیر (اختیاری)"
                                placeholder="توضیحات خود را اینجا بنویسید..."
                                rows="3"
                                auto-grow
                                hide-details
                                class="mb-4"
                                :disabled="form.processing"
                            />
                            <v-btn
                                block
                                color="primary"
                                height="44"
                                :loading="form.processing"
                                @click="updateOrder"
                            >
                                <v-icon start>mdi-content-save</v-icon>
                                ثبت تغییرات
                            </v-btn>
                        </v-card-text>
                    </v-card>

                    <!-- تاریخچه یادداشت‌ها -->
                    <v-card
                        v-if="orderData.notes && orderData.notes.length > 0"
                        elevation="2"
                        border
                        class="mt-4"
                    >
                        <v-card-item class="bg-grey-lighten-4 py-3">
                            <template v-slot:prepend>
                                <v-icon color="grey-darken-2">mdi-history</v-icon>
                            </template>
                            <v-card-title class="text-subtitle-2 font-weight-bold text-grey-darken-3">
                                سوابق و یادداشت‌ها
                            </v-card-title>
                        </v-card-item>

                        <v-divider></v-divider>

                        <v-card-text class="pa-0 scrollable-notes">
                            <v-timeline density="compact" side="end" truncate-line="both" class="px-3 py-2">
                                <v-timeline-item
                                    v-for="(note, i) in orderData.notes"
                                    :key="i"
                                    dot-color="blue-grey-lighten-3"
                                    size="x-small"
                                    width="100%"
                                >
                                    <div class="note-container w-100">
                                        <div class="d-flex justify-space-between align-center mb-1">
                                            <span class="text-caption font-weight-bold text-blue-grey-darken-3">
                                                {{ note.creator }}
                                            </span>
                                            <span class="text-caption text-grey" dir="ltr">
                                                {{ note.created_at.title }}
                                            </span>
                                        </div>
                                        <div class="text-caption text-grey-darken-4 bg-grey-lighten-5 pa-2 rounded border note-text">
                                            {{ note.message }}
                                        </div>
                                    </div>
                                </v-timeline-item>
                            </v-timeline>
                        </v-card-text>
                    </v-card>
                </div>
            </v-col>
        </v-row>
    </AdminLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import { computed } from "vue";
import { numberFormat } from "../../../utils/helpers.js";
import { route } from "ziggy-js";

// Page Setup
const { adminPageTitle } = usePageTitle('جزئیات سفارش');

// Props
const props = defineProps({
    order: Object,
    status: Object, // بهتر است آرایه باشد: [{title: '..', value: '..'}]
});


// Computed Properties
// استفاده از computed برای orderData باعث می‌شود اگر inertia پراپ‌ها را آپدیت کرد، UI هم آپدیت شود.
const orderData = computed(() => props.order.data);

const totalQty = computed(() => {
    return orderData.value.products.reduce((sum, product) => sum + product.qty, 0);
});

// محاسبه تاریخ برای نمایش تمیزتر در هدر
const orderDate = computed(() => {
    const title = orderData.value.created_at?.title;
    if (!title) return '';
    const parts = title.split(' ');
    return parts.length > 1 ? `${parts[0]} - ساعت ${parts[1]}` : title;
});

// ساختار آیتم‌های اطلاعاتی برای حلقه v-for (تمیز کردن تمپلیت)
const infoItems = computed(() => [
    { label: 'خریدار', value: orderData.value.fullname, icon: 'mdi-account-circle' },
    { label: 'شماره موبایل', value: orderData.value.mobile, icon: 'mdi-cellphone' },
    { label: 'نوع ارسال', value: orderData.value.shipping, icon: 'mdi-truck-delivery-outline' },
    { label: 'کد پستی', value: orderData.value.postal_code, icon: 'mdi-mailbox' },
    { label: 'کد تراکنش', value: orderData.value.payment?.transaction_id, icon: 'mdi-barcode' },
    { label: 'کد رهگیری', value: orderData.value.payment?.reference_id, icon: 'mdi-receipt-text' },
    { label: 'آدرس', value: orderData.value.address, icon: 'mdi-map-marker', cols: 12 }, // تمام عرض
    { label: 'یادداشت خریدار', value: orderData.value.user_note, icon: 'mdi-comment-text-outline', cols: 12, color: 'amber-darken-3' },
]);
// Helper
const getStatusColor = (status) => {
    // منطق رنگ‌بندی ساده (می‌توانید بر اساس ولیوهای خودتان تغییر دهید)
    const map = {
        'pending': 'warning',
        'paid': 'success',
        'processing': 'info',
        'completed': 'success',
        'canceled': 'error',
        'refunded': 'grey'
    };
    return map[status] || 'grey';
};

// Form
const form = useForm({
    note: '',
    status: orderData.value.status,
});

const updateOrder = () => {
    form.put(route('admin.orders.update', orderData.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            // نیازی به آپدیت دستی orderData نیست، اینرشا پراپ‌ها را ریلود می‌کند
            form.note = '';
        },
    });
};
</script>

<style scoped>
/* حل مشکل عرض کامل تایم‌لاین */
:deep(.v-timeline-item__body) {
    width: 100% !important;
    padding-inline-start: 12px;
}

.note-text {
    white-space: pre-wrap;
    word-break: break-word;
    text-align: right;
    line-height: 1.6;
}

.scrollable-notes {
    max-height: 400px;
    overflow-y: auto;
}

/* افکت هاور برای باکس‌های اطلاعات */
.hover-bg {
    transition: background-color 0.2s;
}
.hover-bg:hover {
    background-color: #f5f5f5;
}

/* استیکی شدن سایدبار */
.sticky-sidebar {
    position: sticky;
    top: 20px; /* فاصله از بالا */
}
</style>
