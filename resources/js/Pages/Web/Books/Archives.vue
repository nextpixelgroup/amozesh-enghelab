<template>
    <WebLayout>
        <div class="zo-books-section">
            <v-container>
                <v-row dense>
                    <v-col cols=12>
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#">خانه</a>
                                    </li>
                                    <li>
                                        <a href="#">فروشگاه کتاب</a>
                                    </li>
                                    <li>
                                        <span>کتب معارف اسلامی</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <div class="zo-actions">
                <v-container class="py-1">
                    <v-row class="align-center">
                        <v-col class="v-col-md-8 v-col-12">
                            <select name="" id="">
                                <option value=""></option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                            <div class="zo-search">
                                <div class="zo-search">
                                    <div class="zo-select">
                                        <v-select v-model="model" hide-details :items="category" variant="solo"></v-select>
                                    </div>
                                    <div class="zo-input">
                                        <v-text-field hide-details placeholder="جستجو" variant="solo"></v-text-field>
                                        <span class="zo-icon">
                                            <img src="assets/img/site/c-search.svg" alt="" class="img-fluid" />
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </v-col>
                        <v-col class="v-col-md-4 v-col-12">
                            <div class="zo-sort">
                                <v-menu>
                                    <template #activator="{ props }">
                                        <v-btn v-bind="props" variant="tonal" icon="mdi-filter-variant" class="w-10 h-10"></v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-item v-for="(item, index) in sort" :key="index" :value="index">
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
                <v-row dense>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/16.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/17.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/18.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/19.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/20.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/21.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/22.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/23.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/24.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/19.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/16.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                    <v-col lg="3" md="6" cols="12">
                        <a href="#" class="zo-book">
                            <img src="assets/img/sample/18.png" alt="">
                            <div class="zo-content">
                                <h2>تربیت کودک</h2>
                                <div class="zo-meta">
                                    <div class="zo-star">
                                        <i class="mdi mdi-star-outline"></i>
                                        <span>۴.۹</span>
                                    </div>
                                    <div class="zo-price">
                                        <strong>2.450.000</strong>
                                        <small>تومان</small>
                                    </div>
                                </div>
                                <div class="zo-add">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>خرید آنلاین</span>
                                </div>
                            </div>
                        </a>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <v-pagination v-model="page" :length="8" rounded="circle"></v-pagination>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup>
import { ref } from 'vue'
import WebLayout from "@/Layouts/WebLayout.vue";

const sort = ref([
    { title: 'جدیدترین' },
    { title: 'قدیمی‌ترین' },
])

const category = ref([
    'دسته‌بندی',
    'فرهنگ و رسانه',
    'انقلاب ایران',
    'تفکر ذهن',
])

const model = ref('دسته‌بندی')
const page = ref(1)

</script>