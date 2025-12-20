<template>
    <WebLayout>
        <PanelLayout>
            <div>
                <v-container>
                    <div class="zo-header mb-3">
                        <v-row class="align-center">
                            <v-col cols="12">
                                <div class="zo-info d-lg-flex d-sm-none">
                                    <div class="zo-icon elevation-4">
                                        <i class="mdi mdi-account-circle"></i>
                                    </div>
                                    <div class="zo-name">
                                        <strong class="d-block mb-1">پروفایل کاربری</strong>
                                        <span>در این بخش می توانید اطلاعات کاربری خود را ویرایش کنید.</span>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </div>
                    <v-card class="zo-card pa-6">
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
                                <v-text-field label="ایمیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-email" hide-details type="email" />
                            </v-col>
                            <v-col cols="12" lg="6">
                                <v-row dense>
                                    <v-col cols="12" md="4">
                                        <v-select label="روز تولد" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-today" hide-details />
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-select label="ماه تولد" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-month" hide-details />
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-select label="سال تولد" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-badge" hide-details />
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" md="6" lg="3">
                                <v-text-field type="text" label="کدملی" variant="outlined" density="comfortable" prepend-inner-icon="mdi-badge-account" hide-details />
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-select label="استان" variant="outlined" density="comfortable" prepend-inner-icon="mdi-map-plus" hide-details />
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-select label="شهر" variant="outlined" density="comfortable" prepend-inner-icon="mdi-map-legend" hide-details />
                            </v-col>
                            <v-col cols="12" lg="4">
                                <v-text-field label="آدرس" variant="outlined" density="comfortable" prepend-inner-icon="mdi-map-marker-radius" hide-details type="text" />
                            </v-col>
                            <v-col cols="12" lg="2">
                                <v-text-field type="text" variant="outlined" density="comfortable" label="کدپستی" prepend-inner-icon="mdi-home-variant" hide-details />
                            </v-col>
                            <v-col cols="6">
                                <ImageUploader label="فقط فایل تصویری آپلود کنید" title="بارگذاری آواتار" accept="image/*" type="user" />
                            </v-col>
                            <v-col cols="6">
                                <ImageUploader label="فقط فایل تصویری آپلود کنید" title="بارگذاری تصویر کارت ملی" accept="image/*" type="user" />
                            </v-col>
                        </v-row>
                    </v-card>
                    <v-row dense>
                        <v-col cols="12">
                            <div class="zo-header my-3">
                                <v-row class="align-center">
                                    <v-col cols="12" md="9">
                                        <div class="zo-info d-lg-flex d-sm-none">
                                            <div class="zo-icon elevation-4">
                                                <i class="mdi mdi-school"></i>
                                            </div>
                                            <div class="zo-name">
                                                <strong class="d-block mb-1"> سوابق تحصیلی</strong>
                                                <span>در این بخش می توانید سوابق تحصیلی کاربر را ایجاد کنید.</span>
                                            </div>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" md="3">
                                        <div class="text-end">
                                            <v-btn class="zo-action" color="primary" type="submit">
                                                افزودن مدرک تحصیلی
                                            </v-btn>
                                        </div>
                                    </v-col>
                                </v-row>
                            </div>
                            <v-card class="zo-card pa-6">
                                <v-row dense>
                                    <v-col cols="12" class="d-flex justify-space-between align-center">
                                        <h6>سابقه تحصیلی</h6>
                                        <v-btn icon="mdi-delete" variant="text" color="error" size="small"></v-btn>
                                    </v-col>
                                    <v-col cols="12" md="6" lg="3">
                                        <v-text-field label="نام دانشگاه یا موسسه" variant="outlined" prepend-inner-icon="mdi-school" hide-details clearable></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6" lg="3">
                                        <v-text-field label="شهر محل تحصیل" variant="outlined" prepend-inner-icon="mdi-map-marker" hide-details clearable></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6" lg="3">
                                        <v-text-field label="رشته تحصیلی" variant="outlined" prepend-inner-icon="mdi-human-male-board" hide-details clearable></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6" lg="3">
                                        <v-select label="مدرک تحصیلی" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-badge" hide-details />
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <v-row dense>
                                            <v-col cols="12" md="6">
                                                <v-select label="ماه شروع تحصیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-month" hide-details />
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-select label="سال شروع تحصیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-badge" hide-details />
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <v-row dense>
                                            <v-col cols="12" md="6">
                                                <v-select label="ماه پایان تحصیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-month" hide-details />
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-select label="سال پایان تحصیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-badge" hide-details />
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea label="توضیحات" variant="outlined" rows="2" prepend-inner-icon="mdi-text" hide-details></v-textarea>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-checkbox label="در حال تحصیل هستم" hide-details></v-checkbox>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                        <v-col cols="12">
                            <div class="text-end">
                                <v-btn class="zo-action" color="primary" type="submit">
                                    ویرایش کاربر
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </PanelLayout>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";
import ImageUploader from "@/Components/ImageUploader.vue";

</script>
