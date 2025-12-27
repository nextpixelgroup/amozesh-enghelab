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
                            <v-col cols="12" md="6" lg="3">
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
                            <v-col cols="12" md="6" lg="3">
                                <v-select
                                    v-model="form.gender"
                                    label="جنسیت"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-gender-male"
                                    hide-details
                                    :items="gender"
                                />
                            </v-col>
                            <v-col cols="12" md="6" lg="3">
                                <v-text-field
                                    v-model="user.data.mobile"
                                    label="تلفن همراه"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-cellphone-settings"
                                    hide-details
                                    type="text"
                                    readonly
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
                            <v-col cols="12" lg="6">
                                <v-row dense>
                                    <v-col cols="12" md="4">
                                        <v-select
                                            v-model="form.birth_day"
                                            label="روز تولد"
                                            variant="outlined"
                                            density="comfortable"
                                            prepend-inner-icon="mdi-calendar-today"
                                            hide-details
                                            :items="days"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-select
                                            v-model="form.birth_month"
                                            label="ماه تولد"
                                            variant="outlined"
                                            density="comfortable"
                                            prepend-inner-icon="mdi-calendar-month"
                                            hide-details
                                            :items="months"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-select
                                            v-model="form.birth_year"
                                            label="سال تولد"
                                            variant="outlined"
                                            density="comfortable"
                                            prepend-inner-icon="mdi-calendar-badge"
                                            hide-details
                                            :items="years"
                                        />
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" md="6" lg="3">
                                <v-text-field
                                    v-model="form.national_code"
                                    type="text"
                                    label="کدملی"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-badge-account"
                                    hide-details
                                />
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-autocomplete
                                    v-model="form.province"
                                    label="استان"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-map-plus"
                                    hide-details
                                    :items="provincesList"
                                    item-title="title"
                                    item-value="value"
                                    @update:model-value="onProvinceChange"
                                    clearable
                                />
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-autocomplete
                                    v-model="form.city"
                                    label="شهر"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-map-legend"
                                    hide-details
                                    :items="citiesList"
                                    item-title="title"
                                    item-value="value"
                                    :disabled="!form.province"
                                    clearable
                                />
                            </v-col>
                            <v-col cols="12" lg="4">
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
                                    v-model="form.postal_code"
                                    type="text"
                                    variant="outlined"
                                    density="comfortable"
                                    label="کدپستی"
                                    prepend-inner-icon="mdi-home-variant"
                                    hide-details
                                />
                            </v-col>
                            <v-col cols="12">
                                <ImageUploader
                                    v-model:model-value="form.national_card_image_id"
                                    :initial-url="user.data.national_card_image?.url"
                                    upload-route="panel.upload.users.image"
                                    remove-route="panel.media.destroy"
                                    label="فقط فایل تصویری آپلود کنید"
                                    title="بارگذاری تصویر کارت ملی"
                                    accept="image/*"
                                    type="nationalCardImage"
                                    :maxSize="maxSizeUpload"
                                />

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
                                            <v-btn
                                                class="zo-action"
                                                color="primary"
                                                variant="tonal"
                                                type="button"
                                                @click="addEducation"
                                                prepend-icon="mdi-plus"
                                            >
                                                افزودن مدرک تحصیلی
                                            </v-btn>
                                        </div>
                                    </v-col>
                                </v-row>
                            </div>
                            <v-card class="zo-card pa-6" v-for="(education, index) in form.educations" :key="index">
                                <v-row dense>
                                    <v-col cols="12" class="d-flex justify-space-between align-center">
                                        <h6>سابقه تحصیلی {{ index + 1 }}</h6>
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
                                        <v-select v-model="education.degree" :items="degree" label="مدرک تحصیلی" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-badge" hide-details />
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <v-row dense>
                                            <v-col cols="12" md="6">
                                                <v-select v-model="education.start_month" :items="months"  label="ماه شروع تحصیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-month" hide-details />
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-select v-model="education.start_year" :items="years" label="سال شروع تحصیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-calendar-badge" hide-details />
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <v-row dense>
                                            <v-col cols="12" md="6">
                                                <v-select
                                                    v-model="education.end_month"
                                                    :items="months"
                                                    label="ماه پایان تحصیل"
                                                    variant="outlined"
                                                    density="comfortable"
                                                    prepend-inner-icon="mdi-calendar-month"
                                                    hide-details
                                                    :disabled="education.is_studying"
                                                />
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-select
                                                    v-model="education.end_year"
                                                    :items="years"
                                                    label="سال پایان تحصیل"
                                                    variant="outlined"
                                                    density="comfortable"
                                                    prepend-inner-icon="mdi-calendar-badge"
                                                    hide-details
                                                    :disabled="education.is_studying"
                                                />
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
                        </v-col>
                        <v-col cols="12">
                            <div class="text-end">
                                <v-btn
                                    class="zo-action"
                                    color="primary"
                                    type="submit"
                                    @click="updateProfile"
                                    :loading="isLoading"
                                    :disabled="isLoading"
                                >
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
import {useForm} from "@inertiajs/vue3";
import { ref, computed } from 'vue';
import {route} from "ziggy-js";
const maxSizeUpload = ref(1024 * 1024); // 1MB
const isLoading = ref(false);

const props = defineProps({
    user: Object,
    gender: Object,
    email: String,
    years: String,
    months: String,
    days: String,
    provinces : Object,
    cities: Object,
    degree: Object
});

const provincesList = computed(() => {
    return Object.entries(props.provinces).map(([value, title]) => ({
        title,
        value
    }));
});

// لیست شهرهای فیلتر شده بر اساس استان انتخاب شده
const citiesList = computed(() => {
    if (!props.cities || !form.province) return [];

    const provinceData = props.cities[form.province];
    if (!provinceData) return [];

    return Object.entries(provinceData.cities).map(([value, title]) => ({
        title,
        value
    }));
});

const form = useForm({
    firstname: props.user.data.firstname,
    lastname: props.user.data.lastname,
    gender: props.user.data.gender,
    email: props.user.data.email,
    birth_day: props.user.data.birth_date.object.day,
    birth_month: props.user.data.birth_date.object.month,
    birth_year: props.user.data.birth_date.object.year,
    national_code: props.user.data.national_code,
    address: props.user.data.address,
    postal_code: props.user.data.postal_code,
    province: props.user.data.province?.value || '',
    city: props.user.data.city?.value || '',
    national_card_image_id: props.user.data.national_card_image?.id,
    educations: props.user.data.educations && props.user.data.educations.length > 0
        ? props.user.data.educations.map(education => ({
            id: education.id || null,
            university: education.university || '',
            city: education.city || '',
            field_of_study: education.field_of_study || '',
            degree: education.degree || '',
            start_year: education.start_date?.object?.year || null,
            start_month: education.start_date?.object?.month || null,
            end_year: education.is_studying ? null : (education.end_date?.object?.year || null),
            end_month: education.is_studying ? null : (education.end_date?.object?.month || null),
            is_studying: education.is_studying || false,
            description: education.description || ''
        }))
        : []
});
const onProvinceChange = () => {
    form.city = ''; // Reset city when province changes
};

const addEducation = () => {
    form.educations.push({
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
    form.educations.splice(index, 1);
};

// Initialize with one empty education if empty
const handleStudyingChange = (education) => {
    if (education.is_studying) {
        education.end_month = '';
        education.end_year = '';
    }
};

const updateProfile = () => {
    form.put(route('panel.profile.update'), {
        preserveScroll: true,
        preserveState: true,
        onStart: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            isLoading.value = false;
        },
        onFinish: () => {
            isLoading.value = false;
        },
        onError: () => {
            isLoading.value = false;
        }
    })
}

</script>
