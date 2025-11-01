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
                                v-model="form.title"
                                hide-details
                                density="compact"
                                variant="outlined"
                                prepend-inner-icon="mdi-text-short"
                            ></v-text-field>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">نامک</label>
                            <v-text-field
                                v-model="form.slug"
                                hide-details
                                density="compact"
                                variant="outlined"
                                prepend-inner-icon="mdi-link"
                                :suffix="site_url+'/books/'"
                                dir="ltr"
                            ></v-text-field>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">زیرعنوان</label>
                            <v-text-field
                                v-model="form.subtitle"
                                hide-details
                                density="compact"
                                variant="outlined"
                                prepend-inner-icon="mdi-text-short"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">خلاصه توضیحات</label>
                            <v-textarea
                                v-model="form.expert"
                                hide-details
                                density="compact"
                                variant="outlined"
                                prepend-inner-icon="mdi-text-long"
                            >
                            </v-textarea>
                        </v-col>
                        <v-col class="v-col-12">
                            <Editor
                                api-key="kvdbqg230zkimldk8fapggyvjb9gmfa547eveky0zcfgg1zq"
                                v-model="form.content"
                                :init="{
                                    height: 400,
                                    menubar: true,
                                    language: 'fa',
                                    plugins: 'link image media table code lists',
                                  toolbar:
                                    'undo redo | formatselect | bold italic underline | ' +
                                    'alignleft aligncenter alignright | bullist numlist outdent indent | ' +
                                    'image media link code table',
                                  images_upload_url: '/upload/image',
                                  file_picker_types: 'image media',
                                }"
                            />
                        </v-col>
                        <v-col class="v-col-12 v-col-lg-6">
                            <label class="zo-label">قیمت عادی</label>
                            <FieldNumber
                                v-model="form.price"
                                rightHint="تومان"
                                hide-details
                                density="compact"
                                variant="outlined"
                                prepend-inner-icon="mdi-tag-multiple"
                            />
                        </v-col>
                        <v-col class="v-col-12 v-col-lg-6">
                            <label class="zo-label">قیمت ویژه</label>
                            <FieldNumber
                                v-model="form.special_price"
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="250.000"
                                prepend-inner-icon="mdi-tag-multiple"
                                rightHint="تومان"
                            >
                            </FieldNumber>
                        </v-col>
                        <v-col class="v-col-lg-4 v-col-md-6 v-col-12">
                            <label class="zo-label">ناشر</label>
                            <v-text-field
                                v-model="form.publisher"
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
                                v-model="form.year_published"
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
                                v-model="form.size"
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
                                v-model="form.edition"
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
                            <label class="zo-label">موجودی دارد؟</label>
                            <v-select
                                hide-details
                                v-model="form.is_stock"
                                variant="outlined"
                                density="compact"
                                placeholder="موجودی دارد؟"
                                :items="[{
                                    'title' : 'بله',
                                    'value' : true,
                                },
                                {
                                    'title' : 'خیر',
                                    'value' : false,
                                }]"
                            >
                            </v-select>
                        </v-col>
                        <v-col class="v-col-12" v-if="form.is_stock">
                            <label class="zo-label">موجودی انبار</label>
                            <v-number-input
                                v-model="form.stock"
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="0"
                                prepend-inner-icon="mdi-bookshelf"
                            ></v-number-input>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">حداکثر میزان قابل فروش</label>
                            <v-number-input
                                v-model="form.max_order"
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder="1"
                                prepend-inner-icon="mdi-chart-line-variant"
                            ></v-number-input>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">وضعیت</label>
                            <v-select hide-details
                                      v-model="form.status"
                                      variant="outlined"
                                      density="compact"
                                      placeholder="وضعیت"
                                      :items="['پیش نویس', 'منتشر شده', 'آرشیو']"
                            >
                            </v-select>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">دسته‌بندی</label>
                            <v-select
                                v-model="form.category"
                                hide-details
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
                            <v-btn
                                block
                                size="large"
                                color="primary"
                                :loading="isLoading"
                                @click="AddBook"
                            >انتشار کتاب
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>
        </v-row>
    </AdminLayout>
</template>

<script setup>
import {reactive, ref} from 'vue';
import Editor from '@tinymce/tinymce-vue'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {VFileUpload} from 'vuetify/labs/VFileUpload'
import {Head, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import FieldNumber from "@/Components/FieldNumber.vue";

const props = defineProps({
    site_url: String
})
const site_url = props.site_url;
const book = reactive({
    description: '',
});
const isLoading = ref(false);
const form = useForm({
    'title': '',
    'subtitle': '',
    'slug': '',
    'expert': '',
    'content': '',
    'price': null,
    'special_price': null,
    'publisher': '',
    'year_published': '',
    'size': '',
    'edition': '',
    'is_stock': true,
    'stock': null,
    'max_order': null,
    'status': '',
    'category': '',
});

const AddBook = () => {
    form.post(route('admin.books.store'), {
        onStart: () => {
            isLoading.value = true;
        },
        onFinish: () => {
            isLoading.value = false;
        }
    })
}

</script>
