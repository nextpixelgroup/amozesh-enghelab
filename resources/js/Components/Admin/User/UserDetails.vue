<template>
    <div>
        <v-card class="pa-5 mb-3 elevation-2">
            <v-row dense>
                <v-col cols="12" md="6" lg="3">
                    <v-select v-model="form.role" label="نقش کاربری" variant="outlined" density="comfortable" :items="roles" item-title="title" item-value="value" prepend-inner-icon="mdi-shield-account" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.firstname" label="نام" variant="outlined" density="comfortable" prepend-inner-icon="mdi-account" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.lastname" label="نام خانوادگی" variant="outlined" density="comfortable" prepend-inner-icon="mdi-account-circle" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-select v-model="form.gender" label="جنسیت" variant="outlined" density="comfortable" :items="gender" prepend-inner-icon="mdi-gender-male" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.mobile" label="تلفن همراه" variant="outlined" density="comfortable" prepend-inner-icon="mdi-cellphone-settings" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field type="text" v-model="form.tel" variant="outlined" density="comfortable" label="تلفن ثابت" prepend-inner-icon="mdi-phone-classic" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.email" label="ایمیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-email" hide-details type="email" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field type="text" v-model="form.national_code" label="کدملی" variant="outlined" density="comfortable" prepend-inner-icon="mdi-badge-account" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="6">
                    <v-text-field v-model="form.address" label="آدرس" variant="outlined" density="comfortable" prepend-inner-icon="mdi-map-marker-radius" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="3" lg="3">
                    <v-text-field type="text" v-model="form.postal_code" variant="outlined" density="comfortable" label="کدپستی" prepend-inner-icon="mdi-home-variant" hide-details />
                </v-col>
                <v-col cols="12" md="3" lg="3">
                    <v-text-field type="text" v-model="form.company" variant="outlined" density="comfortable" label="محل فعالیت" prepend-inner-icon="mdi-domain" hide-details />
                </v-col>
                <v-col cols="12" md="5" lg="5">
                    <v-row dense>
                        <v-col class="v-col-4">
                            <v-select v-model="form.birth_day" label="روز تولد" variant="outlined" density="comfortable" :items="days" prepend-inner-icon="mdi-calendar-today" hide-details />
                        </v-col>
                        <v-col class="v-col-4">
                            <v-select v-model="form.birth_month" label="ماه تولد" variant="outlined" density="comfortable" :items="months" prepend-inner-icon="mdi-calendar-month" hide-details />
                        </v-col>
                        <v-col class="v-col-4">
                            <v-select v-model="form.birth_year" label="سال تولد" variant="outlined" density="comfortable" :items="years" prepend-inner-icon="mdi-calendar-badge" hide-details />
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12" md="4" lg="4">
                    <v-select v-model="form.institution_id" label="موسسه و دانشگاه" variant="outlined" density="comfortable" :items="institutions" item-title="title" item-value="value" prepend-inner-icon="mdi-school" hide-details clearable />
                </v-col>
                <v-col cols="6">
                    <ImageUploader v-model:model-value="form.avatar_id" :initial-url="form.avatar?.url" upload-route="admin.upload.users.image" label="فقط فایل تصویری آپلود کنید" title="بارگذاری آواتار" accept="image/*" type="user" />
                </v-col>
                <v-col cols="6">
                    <ImageUploader v-model:model-value="form.national_card_image_id" :initial-url="form.national_card_image?.url" upload-route="admin.upload.users.image" label="فقط فایل تصویری آپلود کنید" title="بارگذاری تصویر کارت ملی" accept="image/*" type="user" />
                </v-col>
            </v-row>
        </v-card>
        <div class="zo-header-section mb-5">
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
                        <v-btn color="primary" type="submit" @click="addEducation">
                            افزودن مدرک تحصیلی
                        </v-btn>
                    </div>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-5 mb-3 elevation-2" v-for="(education, index) in form.educations" :key="index">
            <v-row dense>
                <v-col cols="12" class="d-flex justify-space-between align-center">
                    <h4 class="text-subtitle-1">سابقه تحصیلی {{ index + 1 }}</h4>
                    <v-btn icon="mdi-delete" variant="text" color="error" size="small" @click="removeEducation(index)"></v-btn>
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="education.university" label="نام دانشگاه یا موسسه" variant="outlined" prepend-inner-icon="mdi-school" hide-details clearable></v-text-field>
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="education.city" label="شهر محل تحصیل" variant="outlined" prepend-inner-icon="mdi-map-marker" hide-details clearable></v-text-field>
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="education.field_of_study" label="رشته تحصیلی" variant="outlined" prepend-inner-icon="mdi-human-male-board" hide-details clearable></v-text-field>
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-select v-model="education.degree" label="مدرک تحصیلی" variant="outlined" density="comfortable" :items="degree" prepend-inner-icon="mdi-calendar-badge" hide-details />
                </v-col>
                <v-col cols="12" lg="6">
                    <v-row dense>
                        <v-col cols="12" md="6">
                            <v-select v-model="education.start_month" label="ماه شروع تحصیل" variant="outlined" density="comfortable" :items="months" prepend-inner-icon="mdi-calendar-month" hide-details />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select v-model="education.start_year" label="سال شروع تحصیل" variant="outlined" density="comfortable" :items="years" prepend-inner-icon="mdi-calendar-badge" hide-details />
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12" lg="6">
                    <v-row dense>
                        <v-col cols="12" md="6">
                            <v-select v-model="education.end_month" label="ماه پایان تحصیل" variant="outlined" density="comfortable" :items="months" prepend-inner-icon="mdi-calendar-month" hide-details :disabled="education.is_studying" />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select v-model="education.end_year" label="سال پایان تحصیل" variant="outlined" density="comfortable" :items="years" prepend-inner-icon="mdi-calendar-badge" hide-details :disabled="education.is_studying" />
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-textarea v-model="education.description" label="توضیحات" variant="outlined" rows="2" prepend-inner-icon="mdi-text" hide-details></v-textarea>
                </v-col>
                <v-col cols="12">
                    <v-checkbox v-model="education.is_studying" label="در حال تحصیل هستم" hide-details @update:model-value="handleStudyingChange(education)"></v-checkbox>
                </v-col>
            </v-row>
        </v-card>
    </div>
</template>
<script setup>
import ImageUploader from "@/Components/ImageUploader.vue";
import { ref } from "vue";

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    roles: {
        type: Object,
        required: true
    },
    gender: {
        type: Object,
        required: true
    },
    days: {
        type: Object,
        required: true
    },
    months: {
        type: Object,
        required: true
    },
    years: {
        type: Object,
        required: true
    },
    degree: {
        type: Object,
        required: true
    },
    institutions: {
        type: Object,
        required: true
    },
})
const addEducation = () => {
    props.form.educations.push({
        university: '',
        field_of_study: '',
        degree: '',
        start_month: '',
        start_year: '',
        end_month: '',
        end_year: '',
        is_studying: false,
        description: '',
        city: '',
    });
};
const removeEducation = (index) => {
    props.form.educations.splice(index, 1);
};

// Initialize with one empty education if empty
const handleStudyingChange = (education) => {
    if (education.is_studying) {
        education.end_month = '';
        education.end_year = '';
    }
};

</script>
