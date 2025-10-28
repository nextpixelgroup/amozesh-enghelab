<template>
    <AdminLayout>
        <form @submit.prevent="submitForm">
            <div>
                <v-label>عنوان دوره</v-label>
                <v-text-field v-model="course.title" type="text"/>
            </div>

            <div>
                <v-label>نامک</v-label>
                <v-text-field v-model="course.slug" type="text"/>
            </div>

            <div>
                <v-label>دسته</v-label>
                <v-select v-model="course.category">
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </v-select>
            </div>

            <div>
                <v-label>مدرس</v-label>
                <v-select v-model="course.teacher">
                    <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{
                            teacher.name
                        }}
                    </option>
                </v-select>
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
                <v-label>پیش نیازها</v-label>
                <v-textarea v-model="course.requirements"></v-textarea>
            </div>

            <div>
                <v-label>تاریخ انتشار</v-label>
                <v-text-field v-model="course.published_at" type="text" placeholder="تاریخ شمسی"/>
            </div>

            <div>
                <v-label>ضروری بودن آزمون‌ها</v-label>
                <v-select
                    v-model="course.must_complete_quizzes"
                    :items="[
                        { text: 'خیر', value: 'no' },
                        { text: 'بله', value: 'yes' }
                      ]"
                    label="وضعیت"
                    outlined
                    dense
                    class="mt-2"
                ></v-select>
            </div>

            <div>
                <v-label>وضعیت</v-label>
                <v-select
                    v-model="course.must_complete_quizzes"
                    :items="[
                        { text: 'در انتظار', value: 'pending' },
                        { text: 'فعال', value: 'active' }
                      ]"
                    label="وضعیت"
                    outlined
                    dense
                    class="mt-2"
                ></v-select>
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
            <v-btn type="submit">ذخیره دوره</v-btn>
        </form>
    </AdminLayout>
</template>

<script setup>
import {reactive} from 'vue';
import Editor from '@tinymce/tinymce-vue'

import SeasonComponent from '../../../Components/SeasonComponent.vue';
import draggable from 'vuedraggable';
import AdminLayout from "../../../Layouts/AdminLayout.vue";

const categories = reactive([{id: 1, name: 'برنامه نویسی'}, {id: 2, name: 'طراحی'}]);
const teachers = reactive([{id: 1, name: 'حسین'}, {id: 2, name: 'زهرا'}]);

const course = reactive({
    title: '',
    slug: '',
    category: null,
    teacher: null,
    description: '',
    requirements: '',
    published_at: '',
    must_complete_quizzes: false,
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
    console.log('اطلاعات دوره:', course);
    // اینجا می‌تونی با Inertia.post بفرستی
}
</script>
