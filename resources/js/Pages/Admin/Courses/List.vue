<template>
    <AdminLayout>
        <Head title="دوره‌های آموزشی"/>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-laptop"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">دوره‌های آموزشی</strong>
                            <span>دوره‌های آموزشی تاریخی مهدویت و انقلاب اسلامی ایران</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12 text-left">
                    <Link :href="route('admin.courses.create')">
                        <v-btn prepend-icon="$plus" class="zo-add" color="primary">افزودن دوره</v-btn>
                    </Link>
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
                    <th class="text-center">شناسه</th>
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
                    v-for="item in courses"
                    :key="item.id"
                >
                    <td class="text-center">{{ item.id }}</td>
                    <td class="text-center">
                        <img :src="item.thumbnail.url" :alt="item.title" width="90" height="90">
                    </td>
                    <td>
                        <strong class="d-block">{{ item.title }}</strong>
                        <span class="d-lg-block d-sm-none">{{ item.subtitle }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center ga-3">
                            <v-avatar>
                                <v-img
                                    :alt="item.title"
                                    :src="item.avatar"
                                ></v-img>
                            </v-avatar>
                            <div>
                                <strong class="d-block">{{ item.teacher?.fullname }}</strong>
                                <small>{{ item.teacher?.degree }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span :class="'zo-status zo-'+item.status.value">{{ item.status.title }}</span>
                    </td>
                    <td class="text-center">
                        {{ item.member }}
                    </td>
                    <td>
                        <span class="d-block">{{ item.published_at.title.split(' ')[0] }}</span>
                        <small>{{ item.published_at.title.split(' ')[1] }}</small>
                    </td>
                    <td>
                        <div class="d-flex ga-1">
                            <Link :href="route('admin.courses.edit',item.id)"><v-btn icon="mdi-pencil" size="small" color="primary"></v-btn></Link>
                            <a :href="route('web.courses.show',item.id)" target="_blank"><v-btn icon="mdi-eye" size="small" color="info"></v-btn></a>
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

const props = defineProps({
    courses: Object
})
const courses = props.courses.data;
console.log(courses)
</script>
