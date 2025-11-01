<script setup>
import {reactive} from 'vue';
import Editor from '@tinymce/tinymce-vue'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {VFileUpload} from 'vuetify/labs/VFileUpload'

const book = reactive({
    description: '',
});

</script>

<template>
    <AdminLayout>
        <Head title="افزودن کتاب"/>
        <v-row dense class="position-relative">
            <v-col class="v-col-12 v-col-lg-9">
                <v-card class="pa-3 mb-3 elevation-2">
                    <v-row dense>
                        <v-col class="v-col-12">
                            <label class="zo-label">عنوان اصلی</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="طرح کلی اندیشه‎ ی اسلامی در قرآن"
                                prepend-inner-icon="mdi-text-short"
                            ></v-text-field>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">لینک کتاب</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="Enghelab.ir/Mybook"
                                prepend-inner-icon="mdi-link"
                            ></v-text-field>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">عنوان فرعی</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="اصول فکری و اعتقادی اسلام در قرآن"
                                prepend-inner-icon="mdi-text-short"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">خلاصه توضیحات</label>
                            <v-textarea
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="قرآن کریم، کتاب هدایت و زندگی، تنها مجموعه‌ای از احکام و داستان‌ها نیست؛ بلکه نظامی منسجم از اندیشه، جهان‌بینی و ارزش‌های الهی را در خود جای داده است."
                                prepend-inner-icon="mdi-text-long"
                            >
                            </v-textarea>
                        </v-col>
                        <v-col class="v-col-12">
                            <Editor
                                api-key="kvdbqg230zkimldk8fapggyvjb9gmfa547eveky0zcfgg1zq"
                                v-model="book.description"
                                :init="{
                                height: 350,
                                menubar: true,
                                language: 'fa',
                                plugins: 'Plugin',
                                toolbar: 'Test',
                                images_upload_url: '/upload/image',
                                file_picker_types: 'image media',
                            }"
                            />
                        </v-col>
                        <v-col class="v-col-12 v-col-lg-6">
                            <label class="zo-label">قیمت عادی</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="275.000"
                                prepend-inner-icon="mdi-tag"
                            >
                                <template #append-inner>
                                    <small class="text-grey-darken-1">تومان</small>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-12 v-col-lg-6">
                            <label class="zo-label">قیمت ویژه</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="250.000"
                                prepend-inner-icon="mdi-tag-multiple"
                            >
                                <template #append-inner>
                                    <small class="text-grey-darken-1">تومان</small>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-lg-4 v-col-md-6 v-col-12">
                            <label class="zo-label">ناشر</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="انتشارات انقلاب اسلامی"
                                prepend-inner-icon="mdi-domain"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-lg-4 v-col-md-6 v-col-12">
                            <label class="zo-label">سال انتشار</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="1404"
                                prepend-inner-icon="mdi-calendar"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-lg-2 v-col-md-6 v-col-12">
                            <label class="zo-label">قطع</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="3"
                                prepend-inner-icon="mdi-leaf"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-lg-2 v-col-md-6 v-col-12">
                            <label class="zo-label">نوبت چاپ</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="چهارم"
                                prepend-inner-icon="mdi-printer"
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>
            <v-col class="v-col-12 v-col-lg-3">
                <v-card class="pa-3 mb-3 elevation-2 position-sticky top-0">
                    <v-row dense>
                        <v-col class="v-col-12">
                            <label class="zo-label">موجودی انبار</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="0"
                                prepend-inner-icon="mdi-bookshelf"
                            ></v-text-field>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">حداکثر میزان قابل فروش</label>
                            <v-text-field
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="1"
                                prepend-inner-icon="mdi-chart-line-variant"
                            ></v-text-field>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">وضعیت</label>
                            <v-select hide-details
                                      variant="outlined"
                                      density="compact"
                                      placeholder="وضعیت"
                                      :items="['پیش نویس', 'منتشر شده', 'آرشیو']"
                            >
                            </v-select>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">دسته‌بندی</label>
                            <v-select hide-details
                                      multiple
                                      variant="outlined"
                                      density="compact"
                                      placeholder="دسته‌بندی"
                                      :items="['انقلاب کهن', 'ولایت فقیه', 'امام خمینی']"
                            >
                            </v-select>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">تصویر شاخص</label>
                            <v-file-upload
                                density="comfortable"
                                variant="comfortable"
                                title="بارگذاری تصویر شاخص"
                            >
                            </v-file-upload>
                        </v-col>
                        <v-col class="v-col-12">
                            <v-btn block size="large" color="primary">انتشار کتاب</v-btn>
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>
        </v-row>
    </AdminLayout>
</template>
