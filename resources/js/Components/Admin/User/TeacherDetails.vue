<template>
    <div>
        <v-card class="pa-5 mb-3 elevation-2">
            <v-row dense>
                <v-col cols="12" lg="3">
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
                <v-col cols="12" lg="3">
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
                <v-col cols="12" lg="3">
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
                <v-col cols="12" lg="3">
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
                <v-col cols="12" md="6" lg="3">
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
                <v-col cols="12" md="6" lg="3">
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
                <v-col cols="12" md="6" lg="3">
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
                <v-col cols="12" md="6" lg="3">
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
                <v-col cols="12" lg="5">
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
                <v-col cols="12" lg="2">
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
                <v-col cols="12" lg="5">
                    <v-row dense>
                        <v-col cols="12" md="4">
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
                        <v-col cols="12" md="4">
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
                        <v-col cols="12" md="4">
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
                <v-col cols="12" lg="12">
                    <ImageUploader
                        v-model:model-value="form.avatar_id"
                        :initial-url="form.avatar?.url"
                        upload-route="admin.upload.users.image"
                        label="فقط فایل تصویری آپلود کنید"
                        title="بارگذاری آواتار"
                        accept="image/*"
                        type="user"
                    />
                </v-col>
                <v-col cols="12" lg="6">
                    <v-text-field
                        type="text"
                        v-model="form.slug"
                        variant="outlined"
                        density="comfortable"
                        label="نامک"
                        :suffix="site_url+'/teacher/'"
                        dir="ltr"
                        prepend-inner-icon="mdi-link"
                        hide-details
                    />
                </v-col>
                <v-col cols="12" lg="3">
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
                <v-col cols="12" lg="3">
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
                <v-col cols="12" md="6" lg="3">
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
                <v-col cols="12" md="6" lg="3">
                    <v-text-field
                        v-model="form.teaching"
                        label="زمینه تدریس"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-book-open-variant"
                        hide-details
                    />
                </v-col>
                <v-col cols="12" md="6" lg="3">
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
                <v-col cols="12" md="6" lg="3">
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
                <v-col cols="12">
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
        </v-card>
    </div>
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
