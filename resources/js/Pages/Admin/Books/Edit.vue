<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
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
                            >
                                <template v-slot:append-inner>
                                    <v-btn
                                        icon
                                        variant="text"
                                        size="small"
                                        @click="openSlug"
                                        title="باز کردن در تب جدید"
                                    >
                                        <v-icon>mdi-open-in-new</v-icon>
                                    </v-btn>
                                </template>
                            </v-text-field>
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
                            <TipTapEditor
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
                                :items="stock_items"
                                prepend-inner-icon="mdi mdi-warehouse"
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
                                :min="1"
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
                                :min="1"
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
                                prepend-inner-icon="mdi mdi-flag-outline"
                            >
                            </v-select>
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">دسته‌بندی</label>
                            <MultipleSelector
                                v-model="form.category"
                                :items="categories"
                                prepend-inner-icon="mdi-format-list-group-plus"
                            />
                        </v-col>
                        <v-col class="v-col-12">
                            <label class="zo-label">تصویر شاخص</label>
                            <ImageUploader
                                v-model:model-value="form.thumbnail_id"
                                :initialUrl="book.thumbnail.url"
                                upload-route="admin.upload.books.image"
                                label="فقط فایل تصویری آپلود کنید"
                                accept="image/*"
                                type="book"
                            />
                            <div class="text-caption text-grey text-center mt-2">
                                سایز پیشنهادی: ۱۷۵x۲۷۰ پیکسل
                            </div>
                        </v-col>
                        <v-col class="v-col-12">
                            <v-btn
                                block
                                size="large"
                                color="primary"
                                :loading="isLoading"
                                @click="updateBook"
                                :disabled="btnDisabled"
                            >ذخیره تغییرات
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
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import FieldNumber from "@/Components/FieldNumber.vue";
import ImageUploader from "@/Components/ImageUploader.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import MultipleSelector from "@/Components/MultipleSelector.vue";
import TipTapEditor from "@/Components/TipTapEditor.vue";
const {adminPageTitle} = usePageTitle('ویرایش کتاب');

const props = defineProps({
    site_url: String,
    status: Object,
    categories: Object,
    book: Object,
})
const book = props.book.data;
const site_url = props.site_url;
const status = props.status;
const categories = props.categories;
const btnDisabled = ref(false);
const isLoading = ref(false);
const is_stock = ref(null);
if(book.is_stock == 1 && book.stock > 0){
    is_stock.value = 'limited'
}
if(book.is_stock == 1 && book.stock == null){
    is_stock.value = 'yes'
}
else if(book.is_stock == 0){
    is_stock.value = 'no'
}
const form = useForm({
    'title': book.title,
    'subtitle': book.subtitle,
    'slug': book.slug,
    'summary': book.summary,
    'content': book.content,
    'price': book.price,
    'special_price':  book.special_price,
    'publisher': book.publisher,
    'year_published': book.year_published,
    'size': book.size,
    'edition': book.edition,
    'author': book.author,
    'is_stock': is_stock.value,
    'stock': book.stock,
    'max_order': book.max_order,
    'status': book.status.value,
    'category': book.categories.map( item => item.id),
    'thumbnail_id': book.thumbnail.id,
});
const stock_items = [{
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
}];
const updateBook = () => {
    form.put(route('admin.books.update', book.id), {
        preserveScroll: true,
        onStart: () => {
            isLoading.value = true;
            btnDisabled.value = true;
        },
        onSuccess: (response) => {
            form.slug = props.book.data.slug;
        },
        onFinish: () => {
            isLoading.value = false;
            btnDisabled.value = false;
        }
    });
}

const openSlug = () => {
    if (form.slug) {
        const fullUrl = `${site_url}${form.slug}`;
        window.open(fullUrl, '_blank');
    }
};

</script>
