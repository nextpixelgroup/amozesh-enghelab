<template>
    <WebLayout>
        <div class="zo-courses-section">
            <div class="zo-content">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="6" cols="12">
                            <div class="zo-text">
                                <h1>دوره‌های آموزشی</h1>
                                <p>
                                    در این بخش توضیحات مربوط به دوره‌ها یا اطلاعاتی کلی که اعتماد ساز در زمینه دوره‌ها
                                    باشد قرار می‌گیرد. همچنین می‌توانید متن توضیحاتی مربوط به اساتید یا دوره‌هایی که در
                                    گذشته برگزار شده قرار دهید که اعداد و آماری که در مقابل نمایش داده شده است، بهتر درک
                                    شود.
                                </p>
                            </div>
                        </v-col>
                        <v-col lg="6" cols="12">
                            <div class="zo-stats">
                                <v-row class="align-center">
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="assets/img/site/stat-course.svg" alt="" class="img-fluid"/>
                                            <strong>76</strong>
                                            <span>دوره‌های آموزشی</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="assets/img/site/stat-star.svg" alt="" class="img-fluid"/>
                                            <strong>76</strong>
                                            <span>میانگین امتیازات دوره‌ها</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="assets/img/site/stat-students.svg" alt="" class="img-fluid"/>
                                            <strong>76</strong>
                                            <span>کل شرکت کنندگان</span>
                                        </div>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <div class="zo-stat">
                                            <img src="assets/img/site/stat-lessons.svg" alt="" class="img-fluid"/>
                                            <strong>1200</strong>
                                            <span>سرفصل آموزشی</span>
                                        </div>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-actions">
                <v-container class="py-1">
                    <v-row class="align-center">
                        <v-col md="8" cols="12">
                            <div class="zo-search">
                                <div class="zo-select">
                                    <v-select
                                        v-model="model"
                                        hide-details
                                        :items="category"
                                        variant="solo"
                                    ></v-select>
                                </div>
                                <div class="zo-input">
                                    <v-text-field hide-details placeholder="جستجو" variant="solo"></v-text-field>
                                    <span class="zo-icon">
                                        <img src="assets/img/site/c-search.svg" alt="" class="img-fluid"/>
                                    </span>
                                </div>
                            </div>
                        </v-col>
                        <v-col md="4" cols="12">
                            <div class="zo-sort">
                                <v-menu>
                                    <template #activator="{ props }">
                                        <v-btn
                                            v-bind="props"
                                            variant="tonal"
                                            icon="mdi-filter-variant"
                                            class="w-10 h-10"
                                        ></v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-item
                                            v-for="(item, index) in sort"
                                            :key="index"
                                            :value="index"
                                        >
                                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <v-container>
                <v-row class="align-center">
                    <v-col
                        v-for="(course, index) in courses"
                        :key="index"
                        cols="12"
                        sm="6"
                        md="4"
                        lg="3"
                    >
                        <a href="#" class="zo-course">
                            <figure>
                                <div class="zo-thumbnail">
                                    <img :src="course.image" alt="" class="img-fluid"/>
                                </div>
                                <div class="zo-category">
                                    <img src="assets/img/site/c-cat.svg" alt="" class="img-fluid"/>
                                    <span>{{ course.category }}</span>
                                </div>
                            </figure>
                            <div class="zo-content">
                                <h2>{{ course.title }}</h2>
                                <span class="zo-prof">{{ course.professor }}</span>
                                <ul>
                                    <li>
                                        <img src="assets/img/site/c-clock.svg" alt="" class="img-fluid"/>
                                        <span>{{ course.time }}</span>
                                    </li>
                                    <li>
                                        <img src="assets/img/site/c-students.svg" alt="" class="img-fluid"/>
                                        <span>{{ course.students }} دانشجو</span>
                                    </li>
                                </ul>
                                <div class="zo-price">
                                    <span>قیمت</span>
                                    <strong>{{ course.price }}</strong>
                                </div>
                                <div class="zo-more">اطلاعات بیشتر</div>
                            </div>
                        </a>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <v-pagination
                            v-model="page"
                            :length="8"
                            rounded="circle"
                        ></v-pagination>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>

<script setup>
import {ref} from 'vue'
import WebLayout from '@/Layouts/WebLayout.vue'

const sort = ref([
    {title: 'جدیدترین'},
    {title: 'قدیمی‌ترین'},
])

const category = ref([
    'دسته‌بندی',
    'فرهنگ و رسانه',
    'انقلاب ایران',
    'تفکر ذهن',
])

const model = ref('دسته‌بندی')
const page = ref(1)

const courses = ref([
    {
        image: 'assets/img/sample/1.png',
        category: 'فلسفه',
        title: 'دوره کتاب چهل حکمت نبوی (ص)',
        professor: 'عباسی علیزاده',
        time: '۰۳:۵۸',
        students: '۲.۱۲۳',
        price: 'رایگان',
    },
    {
        image: 'assets/img/sample/2.png',
        category: 'فلسفه',
        title: 'دوره کتاب چهل حکمت نبوی (ص)',
        professor: 'عباسی علیزاده',
        time: '۰۳:۵۸',
        students: '۲.۱۲۳',
        price: 'رایگان',
    },
])
</script>
