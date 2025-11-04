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
                            <v-badge
                                v-if="fileUploaded"
                                location="top left"
                                color="error"
                                :model-value="true"
                                class="thumbnail-badge"
                            >
                                <img
                                    :src="thumbnailUrl"
                                    width="100%"
                                    height="auto"
                                    class="mb-4"
                                    style="border: 1px solid #ddd; border-radius: 4px;"
                                />
                                <template #badge>
                                    <v-btn
                                        icon
                                        size="x-small"
                                        variant="flat"
                                        color="error"
                                        @click.stop="removeThumbnail"
                                        class="delete-btn"
                                    >
                                        <v-icon size="small">mdi-close</v-icon>
                                    </v-btn>
                                </template>
                            </v-badge>
                            <v-file-upload
                                v-if="!fileUploaded"
                                v-model="thumbnail"
                                density="comfortable"
                                variant="comfortable"
                                title="بارگذاری تصویر شاخص"
                                @change="uploadThumbnail"
                                :disabled="fileUploading"
                                accept="image/*"
                                label="فقط فایل تصویری آپلود کنید"
                                :rules="[v => !v || v.type.startsWith('image/') || 'فقط فایل تصویری مجاز است']"
                            >
                            </v-file-upload>

                            <!-- نوار پیشرفت -->
                            <v-progress-linear
                                v-if="progress > 0 && !fileUploaded"
                                :model-value="progress"
                                color="primary"
                                height="8"
                                class="mt-3"
                                striped
                                rounded
                                reactive
                            >
                            </v-progress-linear>

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
    <ShowMessage
        v-model:show="showMessage"
        :message="message"
        :type="messageType"
    />
</template>

<script setup>
import {reactive, ref} from 'vue';
import Editor from '@tinymce/tinymce-vue'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {VFileUpload} from 'vuetify/labs/VFileUpload'
import {Head, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import FieldNumber from "@/Components/FieldNumber.vue";
import ShowMessage from "@/Components/ShowMessage.vue";
import {filter} from "vuedraggable/dist/vuedraggable.common.js";
// Message handling
const showMessage = ref(false)
const message = ref('')
const messageType = ref('')

// Show message function
const showToast = (msg, type = 'error') => {
    message.value = msg
    messageType.value = type
    showMessage.value = true
}
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
        onFinish: () => {
            isLoading.value = false;
            btnDisabled.value = false;
        }
    })
}


const thumbnail = ref(null)
const thumbnailUrl = ref(book.thumbnail.url)
const progress = ref(0)
const fileUploading = ref(false);
const fileUploaded = ref(book.thumbnail.id ?? true);

const uploadThumbnail = async () => {
    if (!thumbnail.value) return

    const file = thumbnail.value

    if (!file) return;

    // Check if file is an image
    if (!file.type.startsWith('image/')) {
        showToast('لطفاً فقط فایل تصویری آپلود کنید', 'error')

        thumbnail.value = null;
        return;
    }

    // Check file size (5MB max)
    const maxSize = 5 * 1024 * 1024; // 5MB
    if (file.size > maxSize) {
        showToast('حجم فایل نباید بیشتر از 5 مگابایت باشد', 'error')
        thumbnail.value = null;
        return;
    }

    const formData = new FormData()
    formData.append('file', file)

    progress.value = 0

    try {
        fileUploading.value = true;
        btnDisabled.value = true;
        const response = await axios.post(route('admin.books.upload'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onUploadProgress: (e) => {
                if (e.total) {
                    progress.value = (e.loaded / e.total) * 100
                }
            },
        })
        const result = response.data;
        fileUploading.value = false;
        fileUploaded.value = true;
        thumbnailUrl.value = result.data.url
        form.thumbnail_id = result.data.id
        progress.value = 0
        btnDisabled.value = false
    } catch (err) {
        fileUploading.value = false;
        showToast('خطا در آپلود فایل!', 'error')
        progress.value = 0
        btnDisabled.value = false
    }
}

const removeThumbnail = () => {
    thumbnail.value = null;
    thumbnailUrl.value = '';
    fileUploaded.value = false;
    form.thumbnail_id = null;
}

const openSlug = () => {
    if (form.slug) {
        const fullUrl = `${site_url}${form.slug}`;
        window.open(fullUrl, '_blank');
    }
};

</script>

<style scoped>
.thumbnail-badge {
    width: 100%;
}

.thumbnail-badge :deep(.v-badge__badge) {
    cursor: pointer;
    top: 0;
    right: -1px !important;
}

.thumbnail-badge :deep(.v-badge__badge .delete-btn) {
    width: 24px;
    height: 24px;
}

.thumbnail-badge :deep(.v-badge__badge .v-btn) {
    width: 100%;
    height: 100%;
}
</style>
