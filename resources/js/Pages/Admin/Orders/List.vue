<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-12">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-book-open-page-variant"></i>
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
                              variant="outlined"
                              density="compact"
                              label="وضعیت"
                              clearable
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-text-field
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="نام و نام خانوادگی"
                        clearable
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-5 v-col-12">
                    <v-text-field
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="شماره سفارش"
                        clearable
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-1 v-col-12">
                    <v-btn
                        block
                        variant="outlined"
                        color="primary"
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
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">نام و نام خانوادگی</th>
                    <th class="text-center">شماره</th>
                    <th class="text-center">مبلغ</th>
                    <th class="text-center">تعداد</th>
                    <th class="text-center">تاریخ</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center">134345454</td>
                    <td class="text-center">
                        <span class="zo-status zo-suspended">
                            غیرفعال
                        </span>
                    </td>
                    <td class="text-center">امیر محبیان</td>
                    <td class="text-center">1794175</td>
                    <td class="text-center">
                        <div class="zo-price">
                            <del>259.500</del>
                            <div class="zo-sale">
                                <strong class="pe-1">214,350</strong>
                                <small>تومان</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">3</td>
                    <td class="text-center">
                        1404/09/02
                        <br>
                        6:10 ب.ظ
                    </td>
                    <td class="text-center">
                        <v-btn icon="mdi-eye" size="small" color="primary"></v-btn>
                    </td>
                </tr>
                </tbody>
            </v-table>
        </v-card>

    </AdminLayout>
</template>
<script setup>
import {Head} from '@inertiajs/vue3'
import {defineComponent} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
const {adminPageTitle} = usePageTitle('سفارشات');
</script>
