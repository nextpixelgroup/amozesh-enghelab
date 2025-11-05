<template>
    <AdminLayout>
            <div>
                <v-text-field
                    v-model="course.title"
                    variant="outlined"
                    density="comfortable"
                    label="عنوان دوره"
                    type="text"
                />
            </div>

            <v-text-field
                type="text"
                v-model="course.slug"
                variant="outlined"
                density="comfortable"
                label="نامک"
                suffix="https://amozesh.enghelab.test/courses/"
                dir="ltr"
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

            <div>
                <v-autocomplete
                    v-model="course.category"
                    variant="outlined"
                    density="comfortable"
                    label="دسته"
                    :items="categories"
                    multiple
                    clearable
                />
            </div>

            <div>
                <v-autocomplete
                    v-model="course.teacher"

                    variant="outlined"
                    density="comfortable"
                    label="مدرس"
                    :items="teachers"
                    clearable
                />
            </div>

            <div>
                <v-label>توضیحات</v-label>
                <Editor
                    api-key="kvdbqg230zkimldk8fapggyvjb9gmfa547eveky0zcfgg1zq"
                    v-model="course.description"
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
            </div>

            <div>
                <SearchableSelect
                    v-model="course.requirements"
                    :items="courseItems"
                    label="جستجوی پیش نیازها..."
                    empty-message="هیچ پیش‌نیازی انتخاب نشده است."
                />
            </div>

            <div>
                <v-select
                    v-model="course.must_complete_quizzes"
                    :items="[
                        { title: 'خیر', value: 'no' },
                        { title: 'بله', value: 'yes' }
                      ]"
                    label="ضروری بودن آزمون ها"
                    outlined
                    dense
                    class="mt-2"
                    variant="outlined"
                    density="comfortable"
                />
            </div>

            <div>
                <v-label>وضعیت</v-label>
                <v-select
                    v-model="course.status"
                    :items="status"
                    label="وضعیت"
                    outlined
                    dense
                    class="mt-2"
                    variant="outlined"
                    density="comfortable"
                />
            </div>

            <!-- سرفصل‌ها -->
            <draggable v-model="course.seasons" item-key="id" handle=".drag-handle" class="list-group">
                <template #item="{ element: season, index }">
                    <SeasonComponent
                        :season="season"
                        :index="index"
                        @update-season="updateSeason(index, $event)"
                        @remove-season="removeSeason(index)"
                    />
                </template>
            </draggable>

            <v-btn type="button" @click="addSeason">افزودن سرفصل جدید</v-btn>
            <v-btn type="submit" :loading="isLoading" :disabled="isLoading" @click="submitForm">ذخیره دوره</v-btn>
    </AdminLayout>
</template>

<script setup>
import {reactive, ref} from 'vue';
import Editor from '@tinymce/tinymce-vue'
import SeasonComponent from '../../../Components/SeasonComponent.vue';
import draggable from 'vuedraggable';
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
    { value: 1, title: 'انقلاب اسلامی ایران — دکتر منوچهر محمدی' },
    { value: 2, title: 'تحلیل انقلاب اسلامی ایران — دکتر محسن حمیدزاده' },
    { value: 3, title: 'ریشه‌های انقلاب ایران — نیکی آر. کدی' },
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
    router.post(route('admin.courses.store'),course, {
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
