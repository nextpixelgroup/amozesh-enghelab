<template>
    <AdminLayout>
        <Head title="دوره های آموزشی"/>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-laptop"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">دوره های آموزشی</strong>
                            <span>کتب تاریخی مهدویت و انقلاب اسلامی ایران</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12 text-left">
                    <v-btn prepend-icon="$plus" class="zo-add" color="primary">افزودن دوره</v-btn>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-row dense class="align-center">
                <v-col class="v-col-lg-2 v-col-12">
                    <v-select hide-details
                              variant="outlined"
                              density="compact"
                              label="وضعیت انتشار"
                              :items="['منتشر شده', 'پیش نویس', 'آرشیو']"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-2 v-col-12">
                    <v-select hide-details
                              variant="outlined"
                              density="compact"
                              label="جستجو مدرس"
                              :items="['علی مظلوم', 'پوریا کاظمی', 'حسین امیریان']"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select hide-details
                              variant="outlined"
                              density="compact"
                              label="دسته‌بندی‌ها"
                              :items="['انقلاب اسلامی', 'امام خامنه ای', 'امام خمینی']"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-4 v-col-12">
                    <v-text-field
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو عنوان دوره"
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
                    <th class="text-center">مدرس</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">کاربر</th>
                    <th class="text-center">تاریخ انتشار</th>
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
                    <td>
                        <div class="d-flex align-items-center ga-3">
                            <v-avatar>
                                <v-img
                                    :alt="item.prof"
                                    :src="item.avatar"
                                ></v-img>
                            </v-avatar>
                            <div>
                                <strong class="d-block">{{ item.prof }}</strong>
                                <small>{{ item.degree }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span :class="'zo-status zo-'+item.status.value">{{ item.status.title }}</span>
                    </td>
                    <td class="text-center">
                        {{ item.users }}
                    </td>
                    <td>
                        <span class="d-block">{{ item.date }}</span>
                        <small>{{ item.time }}</small>
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
import {Head, router} from '@inertiajs/vue3'
import {ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";

const books = ref([
    {
        "id": "1",
        "row": "1",
        "image": '/assets/img/books/1.webp',
        "title": "دوره مدیریت فرهنگی جبهه انقلاب",
        "subtitle": "تبیین منظومه فکری امامین انقلاب در باب فرهنگ",
        "prof": "دکتر حمید بصیرت منش",
        "degree": "دکترا فقه و حقوق",
        "avatar": "/assets/img/prof/2.jpg",
        "status": "archive",
        "status_name": "آرشیو",
        "users": "312",
        "date": "1404/07/19",
        "time": "9:42 ب.ظ",
    },
    {
        "id": "2",
        "row": "2",
        "image": '/assets/img/books/2.webp',
        "title": "دوره تحلیل تاریخ معاصر ایران",
        "subtitle": "نقش استعمار و تجدد در تحولات معاصر ایران",
        "prof": "دکتر سیدمحمود سادات",
        "degree": "دکترا علوم اجتماعی",
        "avatar": "/assets/img/prof/3.jpg",
        "status": "publish",
        "status_name": "منتشر شده",
        "users": "93",
        "date": "1404/09/23",
        "time": "5:35 ب.ظ",
    },
    {
        "id": "3",
        "row": "3",
        "image": '/assets/img/books/3.webp',
        "title": "دوره تاریخ دفاع مقدس",
        "subtitle": "تاریخ‌نگاری جنگ ایران و عراق؛ رویکردها و چالش‌ها",
        "prof": "دکتر محسن سراج",
        "degree": "دکترا تاریخ معاصر",
        "avatar": "/assets/img/prof/4.jpg",
        "status": "draft",
        "status_name": "پیش نویس",
        "users": "178",
        "date": "1404/03/14",
        "time": "5:35 ب.ظ",
    },
    {
        "id": "4",
        "row": "4",
        "image": '/assets/img/books/4.webp',
        "title": "دوره اندیشه شهید مطهری",
        "subtitle": "نسبت عقل، دین و تجربه در آثار مطهری",
        "prof": "دکتر مریم تجار",
        "degree": "دکترا رسانه و ارتباطات",
        "avatar": "/assets/img/prof/7.jpg",
        "status": "draft",
        "status_name": "پیش نویس",
        "users": "149",
        "date": "1404/02/05",
        "time": "4:25 ب.ظ",
    },
    {
        "id": "5",
        "row": "5",
        "image": '/assets/img/books/6.webp',
        "title": "دوره آموزشی راهبردهای محتوایی راهیان نور",
        "subtitle": "نقش محتوا در تبیین ارزش‌های دفاع مقدس",
        "prof": "هادی بابائی فلاح",
        "degree": "دکترا تاریخ انقلاب",
        "avatar": "/assets/img/prof/2.jpg",
        "status": "publish",
        "status_name": "منتشر شده",
        "users": "179",
        "date": "1404/10/16",
        "time": "6:10 ب.ظ",
    },
])
</script>
