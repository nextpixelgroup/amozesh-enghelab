<template>
    <AdminLayout title="پروفایل">
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-account-circle"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">ویرایش پروفایل کاربری</strong>
                            <span>در این بخش می توانید پروفایل کاربری خود را ویرایش کنید.</span>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-5 mb-3 elevation-2">
            <v-row dense>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field label="نام" variant="outlined" density="comfortable" prepend-inner-icon="mdi-account" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field label="نام خانوادگی" variant="outlined" density="comfortable" prepend-inner-icon="mdi-account-circle" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-select label="جنسیت" variant="outlined" density="comfortable" prepend-inner-icon="mdi-gender-male" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field label="تلفن همراه" variant="outlined" density="comfortable" prepend-inner-icon="mdi-cellphone-settings" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field type="text" variant="outlined" density="comfortable" label="تلفن ثابت" prepend-inner-icon="mdi-phone-classic" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field label="ایمیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-email" hide-details type="email" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field type="text" label="کدملی" variant="outlined" density="comfortable" prepend-inner-icon="mdi-badge-account" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field type="text" variant="outlined" density="comfortable" label="کدپستی" prepend-inner-icon="mdi-home-variant" hide-details />
                </v-col>
                <v-col cols="12" lg="6">
                    <v-text-field label="آدرس" variant="outlined" density="comfortable" prepend-inner-icon="mdi-map-marker-radius" hide-details type="text" />
                </v-col>
                <v-col cols="12" lg="6">
                    <v-row dense>
                        <v-col class="v-col-4">
                            <v-select label="روز تولد" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-today" hide-details />
                        </v-col>
                        <v-col class="v-col-4">
                            <v-select label="ماه تولد" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-month" hide-details />
                        </v-col>
                        <v-col class="v-col-4">
                            <v-select label="سال تولد" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-badge" hide-details />
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="6">
                    <ImageUploader upload-route="admin.upload.users.image" label="فقط فایل تصویری آپلود کنید" title="بارگذاری آواتار" accept="image/*" type="userAvatar" />
                </v-col>
                <v-col cols="6">
                    <ImageUploader upload-route="admin.upload.users.image" label="فقط فایل تصویری آپلود کنید" title="بارگذاری تصویر کارت ملی" accept="image/*" type="userNationalCardImage" />
                </v-col>
            </v-row>
        </v-card>
        <div class="text-end">
            <v-btn color="primary" type="submit">
                ویرایش پروفایل کاربری
            </v-btn>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ImageUploader from "@/Components/ImageUploader.vue";

const props = defineProps({
    user: Object
})
const user = props.user;

</script>