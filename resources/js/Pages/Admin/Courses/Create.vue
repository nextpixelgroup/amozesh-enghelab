<template>
    <AdminLayout>
        <v-row dense class="position-relative">
            <v-col class="v-col-12 v-col-lg-9">
                <v-card class="pa-3 mb-3 elevation-2">
                    <v-row dense class="position-relative">
                        <v-col class="v-col-12">
                            <v-text-field
                                v-model="course.title"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="عنوان دوره"
                                type="text"
                                prepend-inner-icon="mdi-text-short"
                            />
                        </v-col>
                        <v-col class="v-col-12">

                            <v-text-field
                                type="text"
                                v-model="course.slug"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="نامک"
                                suffix="https://amozesh.enghelab.test/courses/"
                                dir="ltr"
                                prepend-inner-icon="mdi-link"
                            >
                                <template v-slot:append>
                                    <v-btn
                                        icon
                                        variant="text"
                                        :disabled="!course.slug"
                                        @click=""
                                        title="مشاهده"
                                    >
                                        <v-icon>mdi-open-in-new</v-icon>
                                    </v-btn>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-12 v-col-lg-6">
                            <v-autocomplete
                                v-model="course.category"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="دسته‌بندی‌"
                                :items="categories"
                                multiple
                                clearable
                                prepend-inner-icon="mdi-format-list-group-plus"
                            />
                        </v-col>
                        <v-col class="v-col-12 v-col-lg-6">
                            <v-autocomplete
                                v-model="course.teacher"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="مدرس"
                                :items="teachers"
                                clearable
                                prepend-inner-icon="mdi-human-male-board"
                            />
                        </v-col>
                        <v-col class="v-col-12">
                            <Editor
                                api-key="kvdbqg230zkimldk8fapggyvjb9gmfa547eveky0zcfgg1zq"
                                v-model="course.description"
                                :init="{
                                    height: 400,
                                    menubar: true,
                                    language: 'fa',
                                    plugins: 'link image media table code lists',
                                    images_upload_url: '/upload/image',
                                    file_picker_types: 'image media',
                                }"
                            />
                        </v-col>
                    </v-row>
                </v-card>
                <div class="zo-header-section mb-5">
                    <v-row class="align-center">
                        <v-col class="v-col-12">
                            <div class="zo-info d-lg-flex d-sm-none">
                                <div class="zo-icon elevation-4">
                                    <i class="mdi mdi-play-box-multiple"></i>
                                </div>
                                <div class="zo-name">
                                    <strong class="d-block mb-1">افزودن پیش نیاز</strong>
                                    <span>در این بخش می توانید پیش نیاز دوره را براساس دوره های پیشین ایجاد کنید.</span>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <SearchableSelect
                    v-model="course.requirements"
                    :items="courseItems"
                    label="جستجوی پیش نیازها..."
                    empty-message="هیچ پیش‌نیازی انتخاب نشده است."
                />
                <v-btn type="button" @click="addSeason">افزودن سرفصل جدید</v-btn>
            </v-col>
            <v-col class="v-col-12 v-col-lg-3">
                <v-card class="pa-3 mb-3 elevation-2">
                    <v-file-upload density="comfortable" variant="comfortable"></v-file-upload>
                    <v-select
                        v-model="course.must_complete_quizzes"
                        hide-details
                        :items="[
                            { title: 'خیر', value: 0 },
                            { title: 'بله', value: 1 }
                          ]"
                        label="ضروری بودن آزمون ها"
                        outlined
                        class="mb-3"
                        variant="outlined"
                        density="comfortable"
                    />
                    <v-select
                        v-model="course.status"
                        hide-details
                        :items="status"
                        label="وضعیت"
                        outlined
                        class="mb-3"
                        variant="outlined"
                        density="comfortable"
                    />
                    <v-btn block
                           size="large"
                           color="primary"
                           type="submit"
                           :loading="isLoading"
                           :disabled="isLoading"
                           @click="submitForm"
                    >
                        ذخیره دوره
                    </v-btn>
                </v-card>
            </v-col>
        </v-row>
    </AdminLayout>
</template>

<script setup>
import {reactive, ref} from 'vue';
import Editor from '@tinymce/tinymce-vue'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import SearchableSelect from "@/Components/SearchableSelect.vue";

const props = defineProps({
    categories: Object,
    teachers: Object,
    status: Object
})
const isLoading = ref(false);
const categories = ref(props.categories);
const teachers = ref(props.teachers);
const status = ref(props.status);
const selectedCourses = ref();

const courseItems = [
    {value: 1, title: 'انقلاب اسلامی ایران — دکتر منوچهر محمدی'},
    {value: 2, title: 'تحلیل انقلاب اسلامی ایران — دکتر محسن حمیدزاده'},
    {value: 3, title: 'ریشه‌های انقلاب ایران — نیکی آر. کدی'},
    // ...
]

const course = reactive({
    title: '',
    slug: '',
    category: null,
    teacher: null,
    description: '',
    requirements: [],
    must_complete_quizzes: null,
    status: 'pending',
    seasons: []
});

function addSeason() {
    course.seasons.push({
        id: Date.now(),
        title: '',
        description: '',
        is_active: true,
        lessons: []
    });
}

function updateSeason(index, updatedSeason) {
    course.seasons[index] = updatedSeason;
}

function removeSeason(index) {
    course.seasons.splice(index, 1);
}

function submitForm() {
    router.post(route('admin.courses.store'), course, {
        preserveScroll: true,
        onStart: () => {
            isLoading.value = true
        },
        onSuccess: () => {

        },
        onFinish: () => {
            isLoading.value = false
        }
    })
}
</script>
