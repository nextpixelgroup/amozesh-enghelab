<template>
    <v-row dense>
        <v-col cols="12">
            <v-select
                v-model="form.role"
                label="نقش کاربری"
                variant="outlined"
                density="comfortable"
                :items="roles"
                item-title="title"
                item-value="value"
                prepend-inner-icon="mdi-shield-account"
                hide-details
            />
        </v-col>

        <v-col cols="12" lg="4">
            <v-text-field
                v-model="form.firstname"
                label="نام"
                variant="outlined"
                density="comfortable"
                prepend-inner-icon="mdi-account"
                hide-details
                type="text"
            />
        </v-col>

        <v-col cols="12" lg="4">
            <v-text-field
                v-model="form.lastname"
                label="نام خانوادگی"
                variant="outlined"
                density="comfortable"
                prepend-inner-icon="mdi-account-circle"
                hide-details
                type="text"
            />
        </v-col>

        <v-col cols="12" lg="4">
            <v-text-field
                v-model="form.mobile"
                label="تلفن همراه"
                variant="outlined"
                density="comfortable"
                prepend-inner-icon="mdi-cellphone-settings"
                hide-details
                type="text"
            />
        </v-col>

        <v-col cols="12">
            <v-text-field
                v-model="form.email"
                label="ایمیل"
                variant="outlined"
                density="comfortable"
                prepend-inner-icon="mdi-email"
                hide-details
                type="email"
            />
        </v-col>

        <v-col cols="12">
            <v-text-field
                v-model="form.address"
                label="آدرس"
                variant="outlined"
                density="comfortable"
                prepend-inner-icon="mdi-map-marker-radius"
                hide-details
                type="text"
            />
        </v-col>
        <v-col class="v-col-lg-3 v-col-12">
            <v-text-field
                type="text"
                v-model="form.national_code"
                label="کدملی"
                variant="outlined"
                density="comfortable"
                prepend-inner-icon="mdi-badge-account"
                hide-details
            />
        </v-col>
        <v-col class="v-col-lg-6 v-col-12">
            <v-text-field
                type="text"
                v-model="form.tel"
                variant="outlined"
                density="comfortable"
                label="تلفن ثابت"
                prepend-inner-icon="mdi-phone-classic"
                hide-details
            />
        </v-col>
        <v-col class="v-col-lg-6 v-col-12">
            <v-text-field
                type="text"
                v-model="form.postal_code"
                variant="outlined"
                density="comfortable"
                label="کدپستی"
                prepend-inner-icon="mdi-home-variant"
                hide-details
            />
        </v-col>
        <v-col class="v-col-lg-3 v-col-12">
            <v-select
                v-model="form.gender"
                label="جنسیت"
                variant="outlined"
                density="comfortable"
                :items="gender"
                prepend-inner-icon="mdi-gender-male"
                hide-details
            />
        </v-col>
        <v-col class="v-col-lg-4 v-col-12">
            <v-row dense>
                <v-col class="v-col-4">
                    <v-select
                        v-model="form.birth_day"
                        label="روز تولد"
                        variant="outlined"
                        density="comfortable"
                        :items="days"
                        prepend-inner-icon="mdi-calendar-today"
                        hide-details
                    />
                </v-col>
                <v-col class="v-col-4">
                    <v-select
                        v-model="form.birth_month"
                        label="ماه تولد"
                        variant="outlined"
                        density="comfortable"
                        :items="months"
                        prepend-inner-icon="mdi-calendar-month"
                        hide-details
                    />
                </v-col>
                <v-col class="v-col-4">
                    <v-select
                        v-model="form.birth_year"
                        label="سال تولد"
                        variant="outlined"
                        density="comfortable"
                        :items="years"
                        prepend-inner-icon="mdi-calendar-badge"
                        hide-details
                    />
                </v-col>
            </v-row>
        </v-col>


        <v-col class="v-col-12">
            <ImageUploader
                v-model:model-value="form.avatar_id"
                upload-route="admin.upload.users.image"
                label="فقط فایل تصویری آپلود کنید"
                title="بارگذاری آواتار"
                accept="image/*"
            />
        </v-col>
        <v-col class="v-col-12">
            <ImageUploader
                v-model:model-value="form.image_id"
                upload-route="admin.upload.users.image"
                label="فقط فایل تصویری آپلود کنید"
                title="بارگذاری تصویر پس‌زمینه"
                accept="image/*"
            />
        </v-col>
        <v-col class="v-col-8">
            <v-text-field
                type="text"
                v-model="form.slug"
                variant="outlined"
                density="comfortable"
                label="نامک"
                :suffix="site_url+'/t/'"
                dir="ltr"
                prepend-inner-icon="mdi-link"
                hide-details
            />
        </v-col>
        <v-col class="v-col-4">
            <v-text-field
                v-model="form.academic_title"
                type="text"
                variant="outlined"
                density="comfortable"
                label="عنوان علمی یا تخصصی"
                prepend-inner-icon="mdi-flask-outline"
                hide-details
            />
        </v-col>
        <v-col class="v-col-4">
            <v-text-field
                type="text"
                variant="outlined"
                density="comfortable"
                label="عنوان شغلی"
                v-model="form.job_title"
                prepend-inner-icon="mdi-briefcase"
                hide-details
            />
        </v-col>
        <v-col class="v-col-4">
            <v-text-field
                type="text"
                variant="outlined"
                density="comfortable"
                label="مدرک تحصیلی"
                v-model="form.degree"
                prepend-inner-icon="mdi-certificate"
                hide-details
            />
        </v-col>
        <v-col class="v-col-4">
            <v-text-field
                v-model="form.teaching"
                label="زمینه تدریس"
                variant="outlined"
                density="comfortable"
                prepend-inner-icon="mdi-book-open-variant"
            />
        </v-col>
        <v-col class="v-col-4">
            <v-text-field
                type="text"
                variant="outlined"
                density="comfortable"
                label="سوابق تدریس"
                v-model="form.history"
                prepend-inner-icon="mdi-clipboard-text-outline"
                hide-details
            />
        </v-col>
        <v-col class="v-col-4">
            <v-text-field
                type="text"
                variant="outlined"
                density="comfortable"
                label="مهارت ها و تخصص"
                v-model="form.skills"
                prepend-inner-icon="mdi-book-open-variant"
                hide-details
            />
        </v-col>
        <v-col class="v-col-12">
            <v-textarea
                type="text"
                variant="outlined"
                density="comfortable"
                label="بیوگرافی"
                v-model="form.bio"
                prepend-inner-icon="mdi-account-details-outline"
                hide-details
            />
        </v-col>

    </v-row>
    <v-row dense v-if="form.role === 'user'">
        <v-card class="mb-4" variant="outlined">
            <v-card-title class="d-flex align-center bg-grey-lighten-4">
                <v-icon icon="mdi-school" class="ml-2"></v-icon>
                سوابق تحصیلی
                <v-spacer></v-spacer>
                <v-btn
                    color="primary"
                    variant="text"
                    size="small"
                    @click="addEducation"
                    prepend-icon="mdi-plus"
                >
                    افزودن مدرک تحصیلی
                </v-btn>
            </v-card-title>

            <v-card-text>
                <v-row v-for="(education, index) in form.educations" :key="index" class="mb-4">
                    <v-col cols="12" class="d-flex justify-space-between align-center">
                        <h4 class="text-subtitle-1">سابقه تحصیلی {{ index + 1 }}</h4>
                        <v-btn
                            icon="mdi-delete"
                            variant="text"
                            color="error"
                            size="small"
                            @click="removeEducation(index)"
                        ></v-btn>
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="education.university"
                            label="نام دانشگاه یا موسسه"
                            variant="outlined"
                            density="comfortable"
                            hide-details
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="education.city"
                            label="شهر محل تحصیل"
                            variant="outlined"
                            density="comfortable"
                            hide-details
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="education.field_of_study"
                            label="رشته تحصیلی"
                            variant="outlined"
                            density="comfortable"
                            hide-details
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-select
                            v-model="education.degree"
                            label="مدرک تحصیلی"
                            variant="outlined"
                            density="comfortable"
                            :items="degree"
                            prepend-inner-icon="mdi-calendar-badge"
                            hide-details
                        />
                    </v-col>

                    <v-row dense>
                        <v-col class="v-col-6">
                            <v-select
                                v-model="education.start_month"
                                label="ماه شروع تحصیل"
                                variant="outlined"
                                density="comfortable"
                                :items="months"
                                prepend-inner-icon="mdi-calendar-month"
                                hide-details
                            />
                        </v-col>
                        <v-col class="v-col-6">
                            <v-select
                                v-model="education.start_year"
                                label="سال شروع تحصیل"
                                variant="outlined"
                                density="comfortable"
                                :items="years"
                                prepend-inner-icon="mdi-calendar-badge"
                                hide-details
                            />
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col class="v-col-6">
                            <v-select
                                v-model="education.end_month"
                                label="ماه پایان تحصیل"
                                variant="outlined"
                                density="comfortable"
                                :items="months"
                                prepend-inner-icon="mdi-calendar-month"
                                hide-details
                                :disabled="education.is_studying"
                            />
                        </v-col>
                        <v-col class="v-col-6">
                            <v-select
                                v-model="education.end_year"
                                label="سال پایان تحصیل"
                                variant="outlined"
                                density="comfortable"
                                :items="years"
                                prepend-inner-icon="mdi-calendar-badge"
                                hide-details
                                :disabled="education.is_studying"
                            />
                        </v-col>
                    </v-row>

                    <v-col cols="12" md="3" class="d-flex align-center">
                        <v-checkbox
                            v-model="education.is_studying"
                            label="در حال تحصیل هستم"
                            hide-details
                            @update:model-value="handleStudyingChange(education)"


                        ></v-checkbox>
                    </v-col>

                    <v-col cols="12">
                        <v-textarea
                            v-model="education.description"
                            label="توضیحات"
                            variant="outlined"
                            rows="2"
                            hide-details
                        ></v-textarea>
                    </v-col>

                    <v-divider v-if="index < form.educations.length - 1" class="my-2"></v-divider>
                </v-row>
            </v-card-text>
        </v-card>
    </v-row>
</template>
<script setup>
import ImageUploader from "@/Components/ImageUploader.vue";
const props = defineProps({
    roles: {
        type: Object,
        required: true
    },
    form: {
        type: Object,
        required: true
    },
    gender: {
        type: Object,
        required: true
    },
    years: {
        type: Object,
        required: true
    },
    months: {
        type: Object,
        required: true
    },
    days: {
        type: Object,
        required: true
    },
    site_url: {
        type: Object,
        required: true
    },
})
</script>
