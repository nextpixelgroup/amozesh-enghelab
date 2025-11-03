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
            <v-table>
                <thead>
                <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">کاربر</th>
                    <th class="text-right">دوره</th>
                    <th class="text-center">نظر</th>
                    <th class="text-center">تاریخ ارسال</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in comments"
                    :key="item.row"
                >
                    <td class="text-center" colspan="1">{{ item.row }}</td>
                    <td width="200px">
                        <div class="d-flex align-items-center ga-3">
                            <v-avatar>
                                <v-img
                                    :alt="item.author"
                                    :src="item.avatar"
                                ></v-img>
                            </v-avatar>
                            <div>
                                <strong class="d-block">{{ item.author }}</strong>
                                <small>{{ item.type }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center" colspan="1">
                        {{ item.course }}
                    </td>
                    <td>
                        {{ item.comment }}
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
import {Head, Link, router} from '@inertiajs/vue3'
import {ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";

const comments = ref([
    {
        "id": "1",
        "row": "1",
        "avatar": '/assets/img/prof/2.jpg',
        "author": "حمید بصیرت منش",
        "type": "دانشگاه امام صادق (ع)",
        "comment": "سلام وقتتون بخیر. جسارتا من نمیتونم ویدیو شماره 3 دوره را مشاهده کنم. لطف می کنید بگین مشکل چی هست؟",
        "course": "کتاب جهان پس از ظهور",
        "date": "1404/10/29",
        "time": "10:12 ب.ظ"
    },
    {
        "id": "2",
        "row": "2",
        "avatar": '/assets/img/prof/3.jpg',
        "author": "محسن سراج",
        "type": "دانشگاه صنعتی امیرکبیر",
        "comment": "دوره بسیار کاربردی و منظم برگزار شد. سپاس از گروه آموزش.",
        "course": "دوره تحلیل تاریخ معاصر ایران",
        "date": "1404/09/10",
        "time": "7:39 ب.ظ"
    },
    {
        "id": "3",
        "row": "3",
        "avatar": '/assets/img/prof/4.jpg',
        "author": "سیدمحمود سادات",
        "type": "دانشگاه فردوسی مشهد",
        "comment": "سلام و عرض ادب، لطفاً لینک جلسه‌ی اول را مجدداً در اختیار بگذارید، لینک فعلی باز نمی‌شود.",
        "course": "دوره تاریخ دفاع مقدس",
        "date": "1404/06/12",
        "time": "7:59 ب.ظ"
    },
    {
        "id": "4",
        "row": "4",
        "avatar": "/assets/img/prof/5.jpg",
        "author": "فاطمه کرمی",
        "type": "دانشگاه تهران",
        "comment": "سلام، فایل جزوه‌ی جلسه‌ی دوم در سامانه بارگذاری نشده. امکان داره بررسی بفرمایید؟",
        "course": "دوره فلسفه اسلامی",
        "date": "1404/07/18",
        "time": "9:45 ق.ظ"
    },
    {
        "id": "5",
        "row": "5",
        "avatar": "/assets/img/prof/6.jpg",
        "author": "علی موسوی‌نیا",
        "type": "دانشگاه علامه طباطبایی",
        "comment": "بسیار دوره مفیدی بود، خصوصاً بخش تحلیل موردی‌ها. تشکر از اساتید محترم.",
        "course": "دوره تحلیل تاریخ معاصر ایران",
        "date": "1404/08/25",
        "time": "6:27 ب.ظ"
    },
    {
        "id": "6",
        "row": "6",
        "avatar": "/assets/img/prof/7.jpg",
        "author": "زهرا رضایی",
        "type": "دانشگاه شهید بهشتی",
        "comment": "سلام وقت بخیر. آزمون پایان دوره چه زمانی برگزار می‌شود؟",
        "course": "کتاب موعود در آیینه ادیان",
        "date": "1404/11/02",
        "time": "11:10 ق.ظ"
    },
    {
        "id": "7",
        "row": "7",
        "avatar": "/assets/img/prof/2.jpg",
        "author": "محمدصادق کیانی",
        "type": "دانشگاه اصفهان",
        "comment": "ممنون از محتوای خوب و استاد گرامی. فقط لطفاً ویدیوها با کیفیت بالاتری قرار بدید.",
        "course": "دوره تحلیل تاریخ معاصر ایران",
        "date": "1404/09/30",
        "time": "8:50 ب.ظ"
    },
    {
        "id": "8",
        "row": "8",
        "avatar": "/assets/img/prof/3.jpg",
        "author": "الهام احمدی",
        "type": "دانشگاه الزهرا (س)",
        "comment": "ثبت‌نام من در دوره انجام شده اما هنوز به محتوای دروس دسترسی ندارم.",
        "course": "دوره تاریخ دفاع مقدس",
        "date": "1404/10/03",
        "time": "5:33 ب.ع"
    }

])
</script>
