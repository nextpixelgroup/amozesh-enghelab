<template>
    <AdminLayout>
        <Head title="نظرها"/>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-book-open-page-variant"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">نظرها</strong>
                            <span>نمایش نظرات کاربران در خصوص دوره‌ها و کتب</span>
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
                              label="وضعیت انتشار"
                              :items="['موفق', 'بررسی', 'حذف']"
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
                    <th class="text-center">کاربر</th>
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
<script setup>
import {Head, Link, router} from '@inertiajs/vue3'
import {ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";

const books = ref([
    {
        "id": "1",
        "row": "1",
        "avatar": '/assets/img/prof/4.jpg',
        "author": "حسین نوابیان",
        "type": "موسسه آموزش عالی فردوس",
        "comment": "سلام خوبین",
        "course": "دوره مدیریت فرهنگی جبهه انقلاب",
        "date": "1404/07/19",
        "time": "9:42 ب.ظ"
    },
    {
        "id": "2",
        "row": "2",
        "avatar": '/assets/img/prof/2.jpg',
        "author": "حسین نوابیان",
        "type": "موسسه آموزش عالی فردوس",
        "comment": "سلام خوبین",
        "course": "دوره مدیریت فرهنگی جبهه انقلاب",
        "date": "1404/07/19",
        "time": "9:42 ب.ظ"
    },
    {
        "id": "3",
        "row": "3",
        "avatar": '/assets/img/prof/3.jpg',
        "author": "حسین نوابیان",
        "type": "موسسه آموزش عالی فردوس",
        "comment": "سلام خوبین",
        "course": "دوره مدیریت فرهنگی جبهه انقلاب",
        "date": "1404/07/19",
        "time": "9:42 ب.ظ"
    },
])
</script>
