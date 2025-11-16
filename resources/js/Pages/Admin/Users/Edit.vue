<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-account-circle"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">ویرایش کاربر</strong>
                            <span>در این بخش می توانید اطلاعات و لاگ های کاربر را مشاهده می کنید.</span>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <v-form @submit.prevent="updateUser">
            <v-card class="pa-5 mb-3 elevation-2">
                <v-row dense>
                    <v-col class="v-col-12">
                        <v-sheet
                            elevation="0"
                            class="pa-4 rounded-lg bg-grey-lighten-4 mb-4"
                        >
                            <div class="d-flex align-center">
                                <v-icon
                                    icon="mdi-account-details"
                                    color="primary"
                                    class="ml-2"
                                    size="24"
                                ></v-icon>
                                <h3 class="font-weight-medium">مشخصات کاربر</h3>
                            </div>
                        </v-sheet>
                    </v-col>
                    <v-col class="v-col-12">
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
                    <v-col class="v-col-12"></v-col>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.firstname"
                            variant="outlined"
                            density="comfortable"
                            label="نام"
                            prepend-inner-icon="mdi-account"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12" v-if="form.role !== 'institution'">
                        <v-text-field
                            type="text"
                            v-model="form.lastname"
                            variant="outlined"
                            density="comfortable"
                            label="نام خانوادگی"
                            prepend-inner-icon="mdi-account-circle"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.mobile"
                            variant="outlined"
                            density="comfortable"
                            label="تلفن همراه"
                            prepend-inner-icon="mdi-cellphone-settings"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-text-field
                            type="email"
                            v-model="form.email"
                            variant="outlined"
                            density="comfortable"
                            label="ایمیل"
                            prepend-inner-icon="mdi-email"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-8 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.address"
                            variant="outlined"
                            density="comfortable"
                            label="آدرس"
                            prepend-inner-icon="mdi-map-marker-radius"
                            hide-details
                        />
                    </v-col>

                </v-row>
            </v-card>
            <v-card class="pa-5 mb-3 elevation-2">
                <v-row dense>
                    <v-col class="v-col-12">
                        <v-sheet
                            elevation="0"
                            class="pa-4 rounded-lg bg-grey-lighten-4 mb-4"
                        >
                            <div class="d-flex align-center">
                                <v-icon
                                    icon="mdi mdi-information-outline"
                                    color="primary"
                                    class="ml-2"
                                    size="24"
                                ></v-icon>
                                <h3 class="font-weight-medium">جزئیات بیشتر</h3>
                            </div>
                        </v-sheet>
                    </v-col>
                    <v-col class="v-col-lg-3 v-col-12" v-if="form.role !== 'institution'">
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
                    <v-col class="v-col-lg-3 v-col-12">
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
                    <v-col class="v-col-lg-3 v-col-12">
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
                    <v-col class="v-col-lg-3 v-col-12" v-if="form.role !== 'institution'">
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
                    <v-col class="v-col-lg-4 v-col-12" v-if="form.role !== 'institution'">
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
                    <v-col class="v-col-lg-3 v-col-12" v-if="form.role === 'user'">
                        <v-text-field
                            type="text"
                            v-model="form.company"
                            variant="outlined"
                            density="comfortable"
                            label="محل فعالیت"
                            prepend-inner-icon="mdi-domain"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-3 v-col-12" v-if="['admin', 'content-manager'].includes(form.role)">
                        <v-text-field
                            type="text"
                            v-model="form.username"
                            variant="outlined"
                            density="comfortable"
                            label="نام کاربری"
                            prepend-inner-icon="mdi-clipboard-account"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-3" v-if="['user', 'admin', 'content-manager'].includes(form.role)">
                        <v-text-field
                            v-model="form.password"
                            variant="outlined"
                            density="comfortable"
                            label="کلمه عبور"
                            :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="showPassword ? 'text' : 'password'"
                            @click:append-inner="togglePasswordVisibility"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-2" v-if="form.role == 'user'">
                        <v-select
                            v-model="form.institution_id"
                            label="موسسه و دانشگاه"
                            variant="outlined"
                            density="comfortable"
                            :items="institutions"
                            item-title="title"
                            item-value="value"
                            prepend-inner-icon="mdi-shield-account"
                            hide-details
                            clearable
                        />
                    </v-col>
                    <v-col class="v-col-12" v-if="['user', 'institution', 'teacher'].includes(form.role)">
                        <ImageUploader
                            v-model:model-value="form.avatar_id"
                            :initialUrl="user.avatar.url"
                            upload-route="admin.upload.books.image"
                            label="فقط فایل تصویری آپلود کنید"
                            title="بارگذاری آواتار"
                            accept="image/*"
                        />
                    </v-col>
                    <v-col class="v-col-12" v-if="form.role === 'teacher'">
                        <ImageUploader
                            v-model:model-value="form.image_id"
                            :initialUrl="user.teacherDetails.image.url"
                            upload-route="admin.upload.books.image"
                            label="فقط فایل تصویری آپلود کنید"
                            title="بارگذاری تصویر پس‌زمینه"
                            accept="image/*"
                        />
                    </v-col>
                    <v-col class="v-col-8" v-if="form.role === 'teacher'">
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
                    <v-col class="v-col-4" v-if="form.role === 'teacher'">
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
                    <v-col class="v-col-4" v-if="form.role === 'teacher'">
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
                    <v-col class="v-col-4" v-if="form.role === 'teacher'">
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
                    <v-col class="v-col-4" v-if="form.role === 'teacher'">
                        <v-text-field
                            v-model="form.teaching"
                            label="زمینه تدریس"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="mdi-book-open-variant"
                        />
                    </v-col>
                    <v-col class="v-col-4" v-if="form.role === 'teacher'">
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
                    <v-col class="v-col-4" v-if="form.role === 'teacher'">
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
                    <v-col class="v-col-12" v-if="form.role === 'teacher'">
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
                <v-row dense>
                    <v-col class="v-col-12 text-left">
                        <v-btn color="primary" type="submit" :loading="isLoading">
                            ویرایش کاربر
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card>
        </v-form>
        <v-divider class="mb-4 mt-4"></v-divider>
        <v-card class="pa-5 mb-3 elevation-2">
            <UserRestrictionComponent
                :user="user"
                :restrictions="restrictions"
                :restrictionTypes="restrictionTypes"
            />
        </v-card>
    </AdminLayout>
</template>
<script setup>
import {Form, Head, Link, router, useForm, usePage} from '@inertiajs/vue3'
import {defineComponent, ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import UserRestrictionComponent from "@/Components/UserRestrictionComponent.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import ImageUploader from "@/Components/ImageUploader.vue";
const {adminPageTitle} = usePageTitle('ویرایش کاربر');

defineComponent({
    components: {AdminLayout, Head}
})

const props = defineProps({
    roles: Object,
    gender: Object,
    years: Object,
    months: Object,
    days: Object,
    user: Object,
    site_url: Object,
    restrictions: Object,
    restrictionTypes: Object,
    institutions: Object,
    degree: String,
})
const roles = ref(props.roles);
const gender = ref(props.gender);
const years = ref(props.years);
const months = ref(props.months);
const days = ref(props.days);
const user = ref(props.user.data);
//console.log(user.value.avatar)
const restrictions = ref(props.restrictions);
const institutions = ref(props.institutions);
const degree = ref(props.degree);
const site_url = ref(props.site_url);
const isLoading = ref(false);
const showPassword = ref(false);
console.log(user.value)
const form = useForm({
    'firstname': user.value.firstname,
    'lastname': user.value.lastname,
    'gender': user.value.gender.value,
    'national_code': user.value.national_code,
    'mobile': user.value.mobile,
    'tel': user.value.tel,
    'email': user.value.email,
    'address': user.value.address,
    'postal_code': user.value.postal_code,
    'birth_day': user.value.birth_date.object.day,
    'birth_month': user.value.birth_date.object.month,
    'birth_year': user.value.birth_date.object.year,
    'company': user.value.company,
    'role': user.value.role.value,
    'username': user.value.username,
    'password': '',
    'slug': user.value.slug,
    'academic_title': user.value.teacherDetails.academic_title,
    'teaching': user.value.teacherDetails?.teaching,
    'job_title': user.value.teacherDetails?.job_title,
    'degree': user.value.teacherDetails?.degree,
    'history': user.value.teacherDetails?.history,
    'skills': user.value.teacherDetails?.skills,
    'bio': user.value.teacherDetails?.bio,
    'institution_id': user.value?.institution_id,
    'avatar_id': user.value?.avatar?.id,
    'image': user.value?.teacherDetails?.image,
    'image_id': user.value?.teacherDetails?.image?.id,
    'educations': user.value.educations && user.value.educations.length > 0
        ? user.value.educations.map(education => ({
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

const viewPage = () => {
    window.open(route('web.teacher.show', form.slug), '_blank');
}

const updateUser = () => {
    form.put(route('admin.users.update', user.value.id), {
        preserveScroll: true,
        onStart: () => {
            isLoading.value = true;
        },
        onSuccess: () => {

        },
        onFinish: () => {
            isLoading.value = false;
        }
    });
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value
}

const addEducation = () => {
    form.educations.push({
        id: null,
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

</script>
