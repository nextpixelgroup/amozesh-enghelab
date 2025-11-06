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
                <div class="zo-header-section mb-5">
                    <v-row class="align-center">
                        <v-col class="v-col-lg-9">
                            <div class="zo-info d-lg-flex d-sm-none">
                                <div class="zo-icon elevation-4">
                                    <i class="mdi mdi-book-open-page-variant"></i>
                                </div>
                                <div class="zo-name">
                                    <strong class="d-block mb-1">افزودن سرفصل</strong>
                                    <span>در این بخش می توانید سرفصل های دوره + دروس را ایجاد کنید.</span>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <v-expansion-panels multiple class="mb-3" ref="seasonsContainer">
                    <v-expansion-panel
                        v-for="(season, sIndex) in course.seasons"
                        :key="season.id"
                    >
                        <div class="zo-actions">
                            <div class="zo-switch">
                                <v-switch v-model="season.is_active" color="success"></v-switch>
                            </div>
                        </div>
                        <div class="zo-close">
                            <v-btn
                                icon="mdi-close"
                                width="25"
                                height="25"
                                color="error"
                                @click="removeSeason(sIndex)"
                            ></v-btn>
                        </div>
                        <v-expansion-panel-title>
                            <v-btn
                                icon="mdi-drag"
                                width="25"
                                height="25"
                                size="small"
                                class="ml-2 season-drag-handle"
                            ></v-btn>
                            {{ season.title || `سرفصل ${sIndex + 1}` }}
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            <v-text-field
                                v-model="season.title"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="عنوان سرفصل"
                                type="text"
                                class="mb-3"
                                prepend-inner-icon="mdi-text-short"
                            />
                            <v-textarea
                                v-model="season.description"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                type="text"
                                label="توضیحات سرفصل"
                                class="mb-3"
                                prepend-inner-icon="mdi-text"
                                rows="3"
                            />
                            <v-row dense class="justify-end position-relative mb-3">
                                <v-col class="v-col-12">
                                    <v-expansion-panels multiple :ref="el => lessonsContainers[sIndex] = el">
                                        <v-expansion-panel
                                            v-for="(lesson, lIndex) in season.lessons"
                                            :key="lesson.id"
                                        >
                                            <div class="zo-actions">
                                                <div class="zo-switch">
                                                    <v-switch v-model="lesson.is_active" color="success"></v-switch>
                                                </div>
                                            </div>
                                            <div class="zo-close">
                                                <v-btn icon="mdi-close" width="25" height="25" color="error"
                                                       @click="removeLesson(sIndex, lIndex)"></v-btn>
                                            </div>
                                            <v-expansion-panel-title>
                                                <v-btn
                                                    icon="mdi-drag"
                                                    width="25"
                                                    height="25"
                                                    size="small"
                                                    class="ml-2 lesson-drag-handle"
                                                ></v-btn>
                                                {{ lesson.title || `درس ${lIndex + 1}` }}
                                            </v-expansion-panel-title>
                                            <v-expansion-panel-text>
                                                <v-card flat>
                                                    <v-row dense class="position-relative">
                                                        <v-col class="v-col-lg-9 v-col-12">
                                                            <v-text-field
                                                                v-model="lesson.title"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                label="عنوان درس"
                                                                type="text"
                                                                prepend-inner-icon="mdi-text-short"
                                                            />
                                                        </v-col>
                                                        <v-col class="v-col-lg-3 v-col-12">
                                                            <v-number-input
                                                                v-model="lesson.duration"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                label="زمان ویدیو"
                                                                type="text"
                                                                prepend-inner-icon="mdi-clock-time-eight-outline"
                                                                min="1"
                                                            />
                                                        </v-col>
                                                        <v-col class="v-col-12">
                                                            <v-textarea
                                                                v-model="lesson.description"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                type="text"
                                                                label="توضیحات درس"
                                                                rows="2"
                                                                prepend-inner-icon="mdi-text"
                                                            />
                                                        </v-col>
                                                        <v-col class="v-col-12">
                                                            <v-text-field
                                                                v-model="lesson.video_url"
                                                                type="text"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                label="لینک ویدیو"
                                                                suffix="https://video.amozesh.enghelab.test/courses/1/"
                                                                dir="ltr"
                                                                prepend-inner-icon="mdi-link"
                                                            >
                                                                <template v-slot:append>
                                                                    <v-btn
                                                                        icon
                                                                        variant="text"
                                                                        @click=""
                                                                        title="مشاهده"
                                                                    >
                                                                        <v-icon>mdi-open-in-new</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col class="v-col-12">
                                                            <v-file-upload
                                                                v-model="lesson.poster_id"
                                                                density="compact"
                                                                variant="compact"
                                                                title="بارگذاری تصویر ویدیو"
                                                                accept="image/*"
                                                                label="فقط فایل تصویری آپلود کنید"
                                                                :rules="[v => !v || v.type.startsWith('image/') || 'فقط فایل تصویری مجاز است']"
                                                            >
                                                            </v-file-upload>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row dense class="mb-3">
                                                        <v-col class="v-col-12">
                                                            <v-checkbox
                                                                v-model="lesson.has_exam"
                                                                hide-details
                                                                label="فعال سازی آزمون؟"
                                                            >
                                                            </v-checkbox>
                                                        </v-col>
                                                        <v-col class="v-col-12" v-if="lesson.has_exam">
                                                            <v-text-field
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                label="عنوان آزمون"
                                                                type="text"
                                                                prepend-inner-icon="mdi-text-short"
                                                            />
                                                        </v-col>
                                                        <v-col class="v-col-12" v-if="lesson.has_exam">
                                                            <v-textarea
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                type="text"
                                                                label="توضیحات آزمون"
                                                                rows="2"
                                                                prepend-inner-icon="mdi-text"
                                                            />
                                                        </v-col>
                                                    </v-row>
                                                    <v-expansion-panels multiple class="mb-3" v-if="lesson.has_exam">
                                                        <v-expansion-panel
                                                            v-for="(quiz, qIndex) in lesson.quizzes"
                                                            :key="quiz.id"
                                                        >
                                                            <v-expansion-panel-title>
                                                                <v-btn icon="mdi-drag"
                                                                       width="25"
                                                                       height="25"
                                                                       size="small"
                                                                       class="ml-2"
                                                                >
                                                                </v-btn>
                                                                {{ quiz.title || `آزمون ${qIndex + 1}` }}
                                                            </v-expansion-panel-title>
                                                            <div class="zo-actions">
                                                                <div class="zo-switch">
                                                                    <v-switch v-model="quiz.is_active"
                                                                              color="success"></v-switch>
                                                                </div>
                                                            </div>
                                                            <div class="zo-close">
                                                                <v-btn
                                                                    icon="mdi-close"
                                                                    width="25"
                                                                    height="25"
                                                                    color="error"
                                                                    @click="removeQuiz(sIndex)"
                                                                ></v-btn>
                                                            </div>
                                                            <v-expansion-panel-text>
                                                                <v-row dense>
                                                                    <v-col class="v-col-12">
                                                                        <v-text-field
                                                                            hide-details
                                                                            variant="outlined"
                                                                            density="comfortable"
                                                                            label="سوال"
                                                                            type="text"
                                                                            prepend-inner-icon="mdi-text-short"
                                                                        />
                                                                    </v-col>
                                                                    <v-col class="v-col-12 v-col-lg-3">
                                                                        <div class="zo-option">
                                                                            <v-radio value="correct"></v-radio>
                                                                            <v-text-field
                                                                                hide-details
                                                                                variant="outlined"
                                                                                density="comfortable"
                                                                                label="گزینه 1"
                                                                                type="text"
                                                                            />
                                                                        </div>
                                                                    </v-col>
                                                                    <v-col class="v-col-12 v-col-lg-3">
                                                                        <div class="zo-option">
                                                                            <v-radio value="correct"></v-radio>
                                                                            <v-text-field
                                                                                hide-details
                                                                                variant="outlined"
                                                                                density="comfortable"
                                                                                label="گزینه 2"
                                                                                type="text"
                                                                            />
                                                                        </div>
                                                                    </v-col>
                                                                    <v-col class="v-col-12 v-col-lg-3">
                                                                        <div class="zo-option">
                                                                            <v-radio value="correct"></v-radio>
                                                                            <v-text-field
                                                                                hide-details
                                                                                variant="outlined"
                                                                                density="comfortable"
                                                                                label="گزینه 3"
                                                                                type="text"
                                                                            />
                                                                        </div>
                                                                    </v-col>
                                                                    <v-col class="v-col-12 v-col-lg-3">
                                                                        <div class="zo-option">
                                                                            <v-radio value="correct"></v-radio>
                                                                            <v-text-field
                                                                                hide-details
                                                                                variant="outlined"
                                                                                density="comfortable"
                                                                                label="گزینه 4"
                                                                                type="text"
                                                                            />
                                                                        </div>
                                                                    </v-col>
                                                                </v-row>
                                                            </v-expansion-panel-text>
                                                        </v-expansion-panel>
                                                    </v-expansion-panels>
                                                    <div class="zo-add text-end">
                                                        <v-btn
                                                            v-if="lesson.has_exam"
                                                            color="primary"
                                                            @click="addQuiz(sIndex, lIndex)"
                                                        >
                                                            افزودن آزمون
                                                        </v-btn>
                                                    </div>
                                                </v-card>
                                            </v-expansion-panel-text>
                                        </v-expansion-panel>
                                    </v-expansion-panels>
                                </v-col>
                            </v-row>
                            <div class="text-left">
                                <v-btn
                                    color="primary"
                                    @click="addLesson(sIndex)"
                                >
                                    افزودن درس
                                </v-btn>
                            </div>
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
                <div class="text-left">
                    <v-btn color="primary" @click="addSeason">افزودن سرفصل</v-btn>
                </div>
                <div class="zo-header-section mb-5">
                    <v-row class="align-center">
                        <v-col class="v-col-lg-9">
                            <div class="zo-info d-lg-flex d-sm-none">
                                <div class="zo-icon elevation-4">
                                    <i class="mdi mdi-note-text-outline"></i>
                                </div>
                                <div class="zo-name">
                                    <strong class="d-block mb-1">افزودن آزمون</strong>
                                    <span>در این بخش می توانید آزمون دوره را ایجاد کنید.</span>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <v-card class="pa-3 mb-3 elevation-2">
                    <v-row dense class="mb-3">
                        <v-col class="v-col-12">
                            <v-checkbox v-model="checkbox_value"
                                        hide-details
                                        label="فعال سازی آزمون؟"
                            >
                            </v-checkbox>
                        </v-col>
                        <v-col class="v-col-12">
                            <v-text-field
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="عنوان آزمون"
                                type="text"
                                prepend-inner-icon="mdi-text-short"
                            />
                        </v-col>
                        <v-col class="v-col-12">
                            <v-textarea
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                type="text"
                                label="توضیحات آزمون"
                                rows="2"
                                prepend-inner-icon="mdi-text"
                            />
                        </v-col>
                    </v-row>
                    <v-expansion-panels multiple class="mb-3">
                        <v-expansion-panel>
                            <v-expansion-panel-title>
                                <v-btn icon="mdi-drag" width="25" height="25" size="small" class="ml-2"></v-btn>
                                آزمون 1
                            </v-expansion-panel-title>
                            <v-expansion-panel-text>
                                <v-row dense>
                                    <v-col class="v-col-12">
                                        <v-text-field
                                            hide-details
                                            variant="outlined"
                                            density="comfortable"
                                            label="سوال"
                                            type="text"
                                            prepend-inner-icon="mdi-text-short"
                                        />
                                    </v-col>
                                    <v-col class="v-col-12 v-col-lg-3">
                                        <div class="zo-option">
                                            <v-radio value="correct"></v-radio>
                                            <v-text-field
                                                hide-details
                                                variant="outlined"
                                                density="comfortable"
                                                label="گزینه 1"
                                                type="text"
                                            />
                                        </div>
                                    </v-col>
                                    <v-col class="v-col-12 v-col-lg-3">
                                        <div class="zo-option">
                                            <v-radio value="correct"></v-radio>
                                            <v-text-field
                                                hide-details
                                                variant="outlined"
                                                density="comfortable"
                                                label="گزینه 2"
                                                type="text"
                                            />
                                        </div>
                                    </v-col>
                                    <v-col class="v-col-12 v-col-lg-3">
                                        <div class="zo-option">
                                            <v-radio value="correct"></v-radio>
                                            <v-text-field
                                                hide-details
                                                variant="outlined"
                                                density="comfortable"
                                                label="گزینه 3"
                                                type="text"
                                            />
                                        </div>
                                    </v-col>
                                    <v-col class="v-col-12 v-col-lg-3">
                                        <div class="zo-option">
                                            <v-radio value="correct"></v-radio>
                                            <v-text-field
                                                hide-details
                                                variant="outlined"
                                                density="comfortable"
                                                label="گزینه 4"
                                                type="text"
                                            />
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-expansion-panel-text>
                        </v-expansion-panel>
                    </v-expansion-panels>
                    <div class="zo-add text-end">
                        <v-btn icon="mdi-plus"
                               width="25"
                               height="25"
                               color="primary"
                        >
                        </v-btn>
                    </div>
                </v-card>
            </v-col>
            <v-col class="v-col-12 v-col-lg-3">
                <v-card class="pa-3 mb-3 elevation-2  position-sticky top-0">
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
                    <v-file-upload
                        density="comfortable"
                        variant="comfortable"
                        title="بارگذاری تصویر شاخص"
                        class="mb-3"
                    >
                    </v-file-upload>
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
import {nextTick, reactive, ref, useTemplateRef, watch} from 'vue';
import Editor from '@tinymce/tinymce-vue'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import {VFileUpload} from 'vuetify/labs/VFileUpload'
import {useSortable} from "@vueuse/integrations/useSortable";

const switch_value = ref(true);
const checkbox_value = ref(true);

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
    seasons: [
        {
            id: Date.now(),
            title: '',
            description: '',
            is_active: true,
            lessons: [
                {
                    id: Date.now(),
                    title: '',
                    description: '',
                    duration: null,
                    video_url: '',
                    is_active: true,
                    has_exam: false,
                    quizzes: [
                        {
                            title: '',
                            description: '',
                            is_active: true,
                            time_limit: true,
                            questions: [
                                {
                                    text : '',
                                    options : [
                                        {
                                            text: '',
                                            is_correct: false
                                        },
                                        {
                                            text: '',
                                            is_correct: false
                                        },
                                        {
                                            text: '',
                                            is_correct: false
                                        },
                                        {
                                            text: '',
                                            is_correct: false
                                        },
                                    ]
                                }
                            ]
                        },
                    ]
                }
            ]
        }
    ]
});

/********************************Seasons********************************/
const seasonsContainer = useTemplateRef('seasonsContainer')

useSortable(seasonsContainer, course.seasons, {
    animation: 150,
    handle: '.season-drag-handle',
})

function addSeason() {
    course.seasons.push({
        id: Date.now(),
        title: '',
        description: '',
        is_active: true,
        lessons: []
    });
}

function removeSeason(index) {
    course.seasons.splice(index, 1);
}


/********************************Lessons********************************/
const lessonsContainers = ref([])
watch(
    () => course.seasons.map(s => s.lessons.length),
    () => {
        nextTick(() => {
            lessonsContainers.value.forEach((container, index) => {
                if (!container) return
                useSortable(container, course.seasons[index].lessons, {
                    animation: 150,
                    handle: '.lesson-drag-handle',
                })
            })
        })
    },
    {deep: true}
)

function addLesson(sIndex) {
    course.seasons[sIndex].lessons.push({
        id: Date.now(),
        title: '',
        description: '',
        duration: null,
        video_url: '',
        is_active: true,
        has_exam: false,
        quizzes: [],
    });
}

function removeLesson(sIndex, lIndex) {
    course.seasons[sIndex].lessons.splice(lIndex, 1);
}


/********************************quizzes********************************/
function addQuiz(sIndex, lIndex) {
    course.seasons[sIndex].lessons[lIndex].quizzes.push({
        id: Date.now(),
        question: '',
        answer: '',
        is_active: true,
    });
}

function removeQuiz(sIndex, lIndex, qIndex) {
    course.seasons[sIndex].lessons[lIndex].quizzes.splice(qIndex, 1);
}

/******************************** ********************************/

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
<style scoped>
.zo-close {
    position: absolute;
    top: -10px;
    right: -10px;
    z-index: 2;
}

.zo-close button {
    font-size: .75rem;
    cursor: pointer;
}

.zo-actions {
    position: absolute;
    top: -4.5px;
    left: 55px;
    z-index: 15
}

.zo-actions .zo-switch .v-input {
    display: flex;
    justify-content: center;
    align-items: center
}

.zo-option {
    position: relative
}

.zo-option .v-radio {
    position: absolute;
    top: 2.5px;
    left: 5px;
    background: rgb(255, 255, 255);
    z-index: 15
}
</style>
