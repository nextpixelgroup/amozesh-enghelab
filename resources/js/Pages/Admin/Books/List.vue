<script setup>
import {Head, router} from '@inertiajs/vue3'
import {ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";

const books = ref([
    {
        "id": "1",
        "row": "1",
        "image": '/assets/img/books/1.webp',
        "title": "کتاب اسلام و مسیحیت؛ گفت‌وگوی تمدن‌ها",
        "subtitle": "کتاب منتظران منجی در ادیان ابراهیمی - بررسی تطبیقی مفهوم نجات و منجی‌باوری",
        "author": " دکتر امیر علما",
        "status": "publish",
        "status_name": "منتشر شده",
        "price": "175000",
        "sale_price": "159000",
        "stock": "8"
    },
    {
        "id": "2",
        "row": "2",
        "image": '/assets/img/books/2.webp',
        "title": "کتاب جهان پس از ظهور",
        "subtitle": "کتاب جهان پس از ظهور - ترسیم چشم‌انداز آخرالزمان در روایات اسلامی",
        "author": " دکتر مریم عظیمیان",
        "status": "archive",
        "status_name": "آرشیو",
        "price": "245000",
        "sale_price": "190000",
        "stock": "9"
    },
    {
        "id": "3",
        "row": "3",
        "image": '/assets/img/books/3.webp',
        "title": "کتاب موعود در آیینه ادیان",
        "subtitle": "کتاب موعود در آیینه ادیان - تحلیل تاریخی و اعتقادی مهدویت در ادیان مختلف",
        "author": " دکتر حسین نوابیان",
        "status": "draft",
        "status_name": "پیش نویس",
        "price": "550000",
        "sale_price": "",
        "stock": "10"
    },
    {
        "id": "4",
        "row": "4",
        "image": '/assets/img/books/4.webp',
        "title": "کتاب اسلام و مسیحیت؛ گفت‌وگوی تمدن‌ها",
        "subtitle": "کتاب منتظران منجی در ادیان ابراهیمی - بررسی تطبیقی مفهوم نجات و منجی‌باوری",
        "author": " دکتر امیر علما",
        "status": "publish",
        "status_name": "منتشر شده",
        "price": "175000",
        "sale_price": "159000",
        "stock": "8"
    },
    {
        "id": "5",
        "row": "5",
        "image": '/assets/img/books/6.webp',
        "title": "کتاب فلسفه انتظار و مهدویت",
        "subtitle": "کتاب ادیان در گذر تاریخ - تحول مفاهیم دینی از باستان تا معاصر",
        "author": " دکتر نعیم محقق",
        "status": "publish",
        "status_name": "منتشر شده",
        "price": "345000",
        "sale_price": "295000",
        "stock": "9"
    },
])
</script>

<template>
    <AdminLayout>
        <Head title="کتب"/>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-book-open-page-variant"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">لیست کتب</strong>
                            <span>کتب تاریخی مهدویت و انقلاب اسلامی ایران</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12 text-left">
                    <v-btn prepend-icon="$plus" class="zo-add" color="primary">افزودن کتاب</v-btn>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-row dense class="align-center">
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select hide-details
                              variant="outlined"
                              density="compact"
                              label="وضعیت انتشار"
                              :items="['منتشر شده', 'پیش نویس', 'آرشیو']"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select hide-details
                              variant="outlined"
                              density="compact"
                              label="نویسنده"
                              :items="['علی مظلوم', 'پوریا کاظمی', 'حسین امیریان']"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-5 v-col-12">
                    <v-text-field
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو عنوان کتاب"
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-1 v-col-12">
                    <v-btn block variant="outlined" color="primary">جستجو</v-btn>
                </v-col>
            </v-row>
        </v-card>
        <v-card class="pa-3 elevation-2">
            <v-table density="compact">
                <thead>
                <tr>
                    <th class="text-center">ردیف</th>
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
                    :key="item.name"
                >
                    <td class="text-center">{{ item.row }}</td>
                    <td class="text-center">
                        <img :src="item.image" :alt="item.title" width="90" height="90">
                    </td>
                    <td>
                        <strong class="d-block">{{ item.title }}</strong>
                        <span class="d-lg-block d-sm-none">{{ item.subtitle }}</span>
                    </td>
                    <td class="text-center">
                        {{ item.author }}
                    </td>
                    <td class="text-center">
                        <span :class="item.status">{{ item.status_name }}</span>
                    </td>
                    <td class="text-center">
                        <div>
                            <template v-if="item.sale_price">
                                <div class="zo-price">
                                    <del>{{ Number(item.price).toLocaleString('fa-IR') }}</del>
                                    <div class="zo-sale">
                                        <strong class="pe-1">
                                            {{ Number(item.sale_price).toLocaleString('fa-IR') }}
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
                        {{ item.stock }}
                    </td>
                    <td>
                        <div class="d-flex ga-1">
                            <v-btn icon="mdi-eye" size="small" color="primary"></v-btn>
                            <v-btn icon="mdi-pencil" size="small" color="secondary"></v-btn>
                        </div>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination rounded="circle" :length="8"></v-pagination>
        </v-card>
    </AdminLayout>
</template>
