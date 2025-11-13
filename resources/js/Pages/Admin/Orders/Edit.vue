<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9 v-col-12">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-cart"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">جزئیات سفارش <span>1794175</span></strong>
                            <span>امیر محبیان - 1404/09/02 ساعت 6:10 ب.ظ</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-card class="pa-3 elevation-2">
                        <v-select hide-details
                                  variant="outlined"
                                  density="compact"
                                  label="وضعیت"
                                  clearable
                        >
                        </v-select>
                    </v-card>
                </v-col>
            </v-row>
        </div>
        <v-card class="elevation-2 px-3 mb-3">
            <v-table>
                <thead>
                <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">کتاب</th>
                    <th class="text-center">قیمت تک</th>
                    <th class="text-center">تعداد</th>
                    <th class="text-center">قیمت کل</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-center">
                        <strong>کتاب اسلام و مسیحیت؛ گفت‌وگوی تمدن‌ها</strong>
                    </td>
                    <td class="text-center">
                        <div class="zo-price">
                            <div class="zo-sale">
                                <strong class="pe-1">214,350</strong>
                                <small>تومان</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">3</td>
                    <td class="text-center">
                        <div class="zo-price">
                            <strong class="pe-1">643,050</strong>
                            <small>تومان</small>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td class="text-center">
                        <strong>کتاب جهان پس از ظهور</strong>
                    </td>
                    <td class="text-center">
                        <div class="zo-price">
                            <div class="zo-sale">
                                <strong class="pe-1">189,350</strong>
                                <small>تومان</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">3</td>
                    <td class="text-center">
                        <div class="zo-price">
                            <strong class="pe-1">568,050</strong>
                            <small>تومان</small>
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
                            <small class="d-block">کاربر</small>
                            <strong>امیر محبیان</strong>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col">
                    <div class="d-flex align-center ga-3">
                        <i class="mdi mdi-school"></i>
                        <div>
                            <small class="d-block">نوع کاربری</small>
                            <strong>عادی</strong>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col">
                    <div class="d-flex align-center ga-3">
                        <i class="mdi mdi-cellphone-settings"></i>
                        <div>
                            <small class="d-block">شماره موبایل</small>
                            <strong>09332775003</strong>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col">
                    <div class="d-flex align-center ga-3">
                        <i class="mdi mdi-airplane-takeoff"></i>
                        <div>
                            <small class="d-block">نوع ارسال</small>
                            <strong>پست پیشتاز</strong>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col">
                    <div class="d-flex align-center ga-3">
                        <i class="mdi mdi-barcode"></i>
                        <div>
                            <small class="d-block">کد رهگیری بانک</small>
                            <strong>198757569</strong>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col">
                    <div class="d-flex align-center ga-3">
                        <i class="mdi mdi-map-marker-radius"></i>
                        <div>
                            <small class="d-block">کد پستی</small>
                            <strong>09216897159</strong>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-12">
                </v-col>
                <v-col class="v-col-lg-4 v-col-12">
                    <div class="d-flex align-center ga-3">
                        <i class="mdi mdi-map-marker-radius"></i>
                        <div>
                            <small class="d-block">آدرس</small>
                            <strong>خراسان رضوی - مشهد - راهنمایی 23 پلاک 17</strong>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-5 v-col-12">
                    <div class="d-flex align-center ga-3">
                        <i class="mdi mdi-comment"></i>
                        <div>
                            <small class="d-block">یادداشت مشتری</small>
                            <strong>
                                خواهشمندم هنگام ارسال، پیامکی شامل کد رهگیری برایم ارسال شود.
                            </strong>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-card>
    </AdminLayout>
</template>
<script setup>
import {Head} from '@inertiajs/vue3'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import usePageTitle from "@/Composables/usePageTitle.js";

const {adminPageTitle} = usePageTitle('جزئیات سفارش');
</script>
<style scoped>
.zo-order-section i {
    font-size: 1.5rem;
    color: rgb(5, 105, 60);
    text-shadow: 0 0 5px rgba(5, 105, 60, .25);
}
</style>
