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
                            <strong class="d-block mb-1">جزئیات سفارش <span>{{ order.reference_id }}</span></strong>
                            <span>{{ order.user.fullname }} - {{order.created_at.title.split(' ')[0]}} ساعت {{order.created_at.title.split(' ')[1]}}</span>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <v-row dense>
            <v-col class="v-col-lg-9 v-col-12">
                <v-card class="elevation-2 px-3 mb-3">
                    <v-table>
                        <thead>
                        <tr>
                            <th class="text-center">شناسه</th>
                            <th class="text-center">کتاب</th>
                            <th class="text-center">قیمت واحد</th>
                            <th class="text-center">تعداد</th>
                            <th class="text-center">قیمت کل</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in order.products" :key="product.id">
                            <td class="text-center">{{ product.id }}</td>
                            <td class="text-center">
                                <strong>{{product.title}}</strong>
                            </td>
                            <td class="text-center">
                                <div class="zo-price">
                                    <del class="pe-1" v-if="product.price > product.sale_price">{{ numberFormat(product.price) }}</del>
                                    <div class="zo-sale">
                                        <strong class="pe-1">{{ numberFormat(product.sale_price) }}</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{product.qty}}</td>
                            <td class="text-center">
                                <div class="zo-price">
                                    <del class="pe-1" v-if="product.price > product.sale_price">{{ numberFormat(product.price*product.qty) }}</del>
                                    <div class="zo-sale">
                                        <strong class="pe-1">{{ numberFormat(product.sale_price*product.qty) }}</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><span class="pe-1">تعداد کل:</span></td>
                            <td class="text-center">
                                <strong>{{ totalQty }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><span class="pe-1">جمع:</span></td>
                            <td class="text-center">
                                <div class="zo-price">
                                    <del class="pe-1" v-if="order.discount_total">{{numberFormat(order.original_total)}}</del>
                                    <div class="zo-sale">
                                        <strong class="pe-1">{{numberFormat(order.total-order.shipping_cost)}}</strong>
                                        <small class="text-caption">تومان</small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><span class="pe-1">حمل و نقل:</span></td>
                            <td class="text-center">
                               <div class="zo-price">
                                   <strong class="pe-1">{{numberFormat(order.shipping_cost)}}</strong>
                                   <small class="text-caption">تومان</small>
                               </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><span class="pe-1">جمع پرداختی:</span></td>
                            <td class="text-center">
                                <div class="zo-price">
                                    <strong class="pe-1">{{numberFormat(order.total)}}</strong>
                                    <small class="text-caption">تومان</small>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </v-table>
                </v-card>
                <v-card class="pa-3 zo-order-section">
                    <v-row dense class="align-center">
                        <v-col class="v-col">
                            <div class="d-flex align-center ga-3">
                                <i class="mdi mdi-account-circle"></i>
                                <div>
                                    <small class="d-block">خریدار</small>
                                    <strong>{{order.fullname}}</strong>
                                </div>
                            </div>
                        </v-col>
                        <v-col class="v-col">
                            <div class="d-flex align-center ga-3">
                                <i class="mdi mdi-cellphone-settings"></i>
                                <div>
                                    <small class="d-block">شماره موبایل خریدار</small>
                                    <strong>{{ order.mobile }}</strong>
                                </div>
                            </div>
                        </v-col>
                        <v-col class="v-col">
                            <div class="d-flex align-center ga-3">
                                <i class="mdi mdi-airplane-takeoff"></i>
                                <div>
                                    <small class="d-block">نوع ارسال</small>
                                    <strong>{{ order.shipping }}</strong>
                                </div>
                            </div>
                        </v-col>
                        <v-col class="v-col">
                            <div class="d-flex align-center ga-3">
                                <i class="mdi mdi-barcode"></i>
                                <div>
                                    <small class="d-block">کد تراکنش بانک</small>
                                    <strong>{{order.payment.transaction_id}}</strong>
                                </div>
                            </div>
                        </v-col>
                        <v-col class="v-col">
                            <div class="d-flex align-center ga-3">
                                <i class="mdi mdi-barcode"></i>
                                <div>
                                    <small class="d-block">کد رهگیری بانک</small>
                                    <strong>{{order.payment.reference_id}}</strong>
                                </div>
                            </div>
                        </v-col>
                        <v-col class="v-col">
                            <div class="d-flex align-center ga-3">
                                <i class="mdi mdi-map-marker-radius"></i>
                                <div>
                                    <small class="d-block">کد پستی خریدار</small>
                                    <strong>{{ order.postal_code }}</strong>
                                </div>
                            </div>
                        </v-col>
                        <v-col class="v-col-12">
                        </v-col>
                        <v-col class="v-col-lg-6 v-col-12">
                            <div class="d-flex align-center ga-3">
                                <i class="mdi mdi-map-marker-radius"></i>
                                <div>
                                    <small class="d-block">آدرس خریدار</small>
                                    <strong>{{order.address}}</strong>
                                </div>
                            </div>
                        </v-col>
                        <v-col class="v-col-lg-6 v-col-12">
                            <div class="d-flex align-center ga-3">
                                <i class="mdi mdi-comment"></i>
                                <div>
                                    <small class="d-block">یادداشت خریدار</small>
                                    <strong>{{order.user_note}}</strong>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>
            <v-col class="v-col-lg-3 v-col-12">
                <v-card class="pa-3 elevation-2">
                    <v-select
                        v-model="form.status"
                        :items="status"
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="وضعیت"
                        clearable
                        class="mb-3"
                        :disabled="isLoading"
                    />
                    <v-textarea
                        v-model="form.note"
                        type="textarea"
                        variant="outlined"
                        density="comfortable"
                        label="یادداشت مدیر"
                        prepend-inner-icon="mdi-text"
                        hide-details
                        class="mb-3"
                        :disabled="isLoading"
                    />
                    <v-btn
                        block
                        color="primary"
                        :disabled="isLoading"
                        :loading="isLoading"
                        @click="updateOrder"
                    >
                        ثبت تغییرات
                    </v-btn>
                    <v-card flat class="mt-3 bg-yellow-lighten-4" v-if="order.notes" v-for="note in order.notes">
                        <v-card-text :key="note.id">{{note.creator}}: {{note.message}}</v-card-text>
                    </v-card>
                </v-card>
            </v-col>
        </v-row>
    </AdminLayout>
</template>
<script setup>
import {Head, useForm} from '@inertiajs/vue3'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import {computed, ref} from "vue";
import {numberFormat} from "../../../utils/helpers.js";
import {route} from "ziggy-js";

const {adminPageTitle} = usePageTitle('جزئیات سفارش');

const props = defineProps({
    order: Object,
    status: Object,
});
const isLoading = ref(false);
const order = ref(props.order.data);
const totalQty = computed(() => {
    return order.value.products.reduce((sum, product) => {
        return sum + product.qty;
    }, 0);
});
const form = useForm({
    note: '',
    status: order.value.status,
});

const updateOrder = () => {
    try {
        form.put(route('admin.orders.update',order.value.id), {
            preserveScroll: true,
            preserveState: true,
            onStart: () => {
                isLoading.value = true
            },
            onSuccess: () => {
                form.note = ''
                isLoading.value = false
            },
            onError: () => {
                isLoading.value = false
            }
        })
    }
    catch (error) {
        isLoading.value = false
    }
}

</script>
<style scoped>
.zo-order-section i {
    font-size: 1.5rem;
    color: rgb(5, 105, 60);
    text-shadow: 0 0 5px rgba(5, 105, 60, .25);
}

.zo-order-section strong {
    font-size: .90rem
}
</style>
