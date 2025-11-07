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
                                type="text"
                                v-model="form.slug"
                                hide-details
                                density="compact"
                                variant="outlined"
                                :suffix="site_url"
                                dir="ltr"
                                prepend-inner-icon="mdi-link"
                            />
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
                                v-model="form.summary"
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
                                prepend-inner-icon="mdi-tag-multiple"
                                rightHint="تومان"
                            >
                            </FieldNumber>
                        </v-col>
                        <v-col class="v-col-md-6 v-col-12">
                            <label class="zo-label">ناشر</label>
                            <v-text-field
                                v-model="form.publisher"
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder=""
                                prepend-inner-icon="mdi-domain"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-md-6 v-col-12">
                            <label class="zo-label">نویسنده</label>
                            <v-text-field
                                v-model="form.author"
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder=""
                                prepend-inner-icon="mdi-account-circle"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-md-4 v-col-12">
                            <label class="zo-label">سال انتشار</label>
                            <v-text-field
                                v-model="form.year_published"
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder=""
                                prepend-inner-icon="mdi-calendar"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-md-4 v-col-12">
                            <label class="zo-label">قطع</label>
                            <v-text-field
                                v-model="form.size"
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder=""
                                prepend-inner-icon="mdi-leaf"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-md-4 v-col-12">
                            <label class="zo-label">نوبت چاپ</label>
                            <v-text-field
                                v-model="form.edition"
                                hide-details
                                density="compact"
                                variant="outlined"
                                placeholder=""
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
                            <label class="zo-label">موجودی</label>
                            <v-select
                                hide-details
                                v-model="form.is_stock"
                                variant="outlined"
                                density="compact"
                                :items="[{
                                    'title' : 'موجود',
                                    'value' : 'yes',
                                },
                                {
                                    'title' : 'محدود',
                                    'value' : 'limited',
                                },
                                {
                                    'title' : 'ناموجود',
                                    'value' : 'no',
                                }]"
                            >
                            </v-select>
                        </v-col>
                        <v-col class="v-col-12" v-if="form.is_stock == 'limited'">
                            <label class="zo-label">موجودی انبار</label>
                            <v-number-input
                                v-model="form.stock"
                                hide-details
                                density="compact"
                                variant="outlined"
                                prepend-inner-icon="mdi-bookshelf"
                                min="1"
                            ></v-number-input>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">حداکثر میزان قابل فروش</label>
                            <v-number-input
                                v-model="form.max_order"
                                hide-details
                                density="compact"
                                variant="outlined"
                                prepend-inner-icon="mdi-chart-line-variant"
                                min="1"
                            ></v-number-input>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">وضعیت</label>
                            <v-select
                                hide-details
                                v-model="form.status"
                                variant="outlined"
                                density="compact"
                                placeholder="وضعیت"
                                :items="status"
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
                                :items="categories"
                            >
                            </v-select>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">تصویر شاخص</label>

                            <ThumbnailUploader
                                v-model:model-value="form.thumbnail_id"
                                upload-route="admin.upload.books.image"
                                label="فقط فایل تصویری آپلود کنید"
                                accept="image/*"
                            />

                        </v-col>
                        <v-col class="v-col-12">
                            <v-btn
                                block
                                size="large"
                                color="primary"
                                :loading="isLoading"
                                @click="AddBook"
                                :disabled="btnDisabled"
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
import {ref} from 'vue';
import Editor from '@tinymce/tinymce-vue'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import FieldNumber from "@/Components/FieldNumber.vue";
import ShowMessage from "@/Components/ShowMessage.vue";
import ThumbnailUploader from "@/Components/ThumbnailUploader.vue";


const props = defineProps({
    site_url: String,
    status: Object,
    categories: Object,
})
const site_url = props.site_url;
const status = props.status;
const categories = props.categories;
const btnDisabled = ref(false);
const isLoading = ref(false);
const form = useForm({
    'title': '',
    'subtitle': '',
    'slug': '',
    'summary': '',
    'content': '',
    'price': null,
    'special_price': null,
    'publisher': '',
    'year_published': '',
    'size': '',
    'edition': '',
    'author': '',
    'is_stock': 'yes',
    'stock': null,
    'max_order': null,
    'status': '',
    'category': null,
    'thumbnail_id': null,
});

const AddBook = () => {
    form.post(route('admin.books.store'), {
        onStart: () => {
            isLoading.value = true;
            btnDisabled.value = true;
        },
        onFinish: () => {
            isLoading.value = false;
            btnDisabled.value = false;
        }
    })
}


</script>
