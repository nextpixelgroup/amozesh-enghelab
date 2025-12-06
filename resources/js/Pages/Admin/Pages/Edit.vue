<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <v-row dense class="position-relative">
            <v-col class="v-col-12 v-col-lg-9">
                <v-card class="pa-3 mb-3 elevation-2">
                    <v-row dense>
                        <v-col class="v-col-12">
                            <label class="zo-label">عنوان</label>
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
                                readonly
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
                            <label class="zo-label">محتوا</label>
                            <TipTapEditor v-model="form.content"/>
                        </v-col>
                        <PageMetaFieldsAbout
                            v-if="form.type === 'about'"
                            v-model="form.meta"
                            :institutions="institutions"
                            :teachers="teachers"
                        />
                        <PageMetaFieldsContact
                            v-if="form.type === 'contact'"
                            v-model="form.meta"
                        />
                    </v-row>
                </v-card>
            </v-col>
            <v-col class="v-col-12 v-col-lg-3">
                <v-card class="pa-3 mb-3 elevation-2 position-sticky top-0">
                    <v-row dense>
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
                        <v-col class="v-col-12" v-if="form.type !== 'contact'">
                            <label class="zo-label">تصویر شاخص</label>
                            <ImageUploader
                                v-model:model-value="form.thumbnail_id"
                                :initialUrl="page.thumbnail?.url"
                                upload-route="admin.upload.pages.image"
                                label="فقط فایل تصویری آپلود کنید"
                                accept="image/*"
                                type="page"
                            />

                        </v-col>
                        <v-col class="v-col-12">
                            <v-btn
                                block
                                size="large"
                                color="primary"
                                :loading="isLoading"
                                @click="updatePage"
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
import ImageUploader from "@/Components/ImageUploader.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import PageMetaFieldsAbout from "@/Components/Admin/Page/PageMetaFieldsAbout.vue";
import PageMetaFieldsContact from "@/Components/Admin/Page/PageMetaFieldsContact.vue";
import TipTapEditor from "@/Components/TipTapEditor.vue";
const {adminPageTitle} = usePageTitle('ویرایش برگه');

const props = defineProps({
    site_url: String,
    status: Object,
    page: Object,
    institutions: Object,
    teachers: Object,
})
const page = ref(props.page.data);
const site_url = props.site_url;
const status = props.status;
const btnDisabled = ref(false);
const isLoading = ref(false);
const meta = ref([]);
if(page.value.type == 'about'){
    meta.value = {
        institutions: page.value.meta.institutions,
            teachers: page.value.meta.teachers,
        section1Title: page.value.meta.section1Title,
        section1Content: page.value.meta.section1Content,
        section1Thumbnail: {
        id: page.value.meta?.section1Thumbnail?.id,
            url: page.value.meta?.section1Thumbnail?.url,
    },
        section2Title: page.value.meta.section2Title,
            section2Content: page.value.meta.section2Content,
    }
}
else if(page.value.type == 'contact'){
    meta.value = {
        address: page.value?.meta?.address,
        tel: page.value?.meta?.tel,
        email: page.value?.meta?.email,
        mapImage: {
            id: page.value?.meta?.mapImage?.id,
            url: page.value?.meta?.mapImage?.url,
        }
    }

}
const form = useForm({
    'type': page.value.type,
    'title': page.value.title,
    'content': page.value.content,
    'status': page.value.status.value,
    'slug': page.value.slug,
    'thumbnail_id': page.value.thumbnail?.id ?? null,
    'meta': meta.value
});
const updatePage = () => {
    form.put(route('admin.pages.update', page.value.id), {
        preserveScroll: true,
        onStart: () => {
            isLoading.value = true;
            btnDisabled.value = true;
        },
        onSuccess: (response) => {
            form.slug = props.page.data.slug;
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
