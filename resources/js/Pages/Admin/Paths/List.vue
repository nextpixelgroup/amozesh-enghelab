<template>
    <Head :title="adminPageTitle"/>
    <Head title="Courses"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-multicast"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">مسیرهای آموزشی</strong>
                            <span>در این بخش می توانید مسیرهای آموزشی را مدیریت کنید.</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12 text-left"
                       v-if="paths.length > 0">
                    <v-btn prepend-icon="$plus" class="zo-add" color="primary" @click="addPath">
                        افزودن مسیر
                    </v-btn>
                </v-col>
            </v-row>
        </div>

        <v-expansion-panels multiple ref="pathsContainer" v-model="activePanel">
            <v-expansion-panel
                v-for="(path, pathIndex) in paths"
                :key="pathIndex"
            >
                <v-expansion-panel-title>
                    <div class="d-flex align-center" style="width: 100%">
                        <v-btn
                            color="primary"
                            icon="mdi-drag"
                            width="25"
                            height="25"
                            size="small"
                            class="path-drag-handle"
                        ></v-btn>
                        <span class="flex-grow-1">{{ path.title }}</span>
                        <v-btn
                            icon
                            color="error"
                            variant="text"
                            size="small"
                            class="path-delete-btn"
                            @click.stop="removePath(pathIndex, path.id)"
                        >
                            <v-icon size="small">mdi-trash-can-outline</v-icon>
                        </v-btn>
                    </div>
                </v-expansion-panel-title>

                <v-expansion-panel-text>
                    <v-text-field
                        v-model="path.title"
                        label="عنوان مسیر"
                        variant="outlined"
                        density="comfortable"
                    />

                    <v-autocomplete
                        v-model="path.selectedCourse"
                        hide-details
                        hide-no-data
                        clearable
                        variant="outlined"
                        :items="path.availableCourses"
                        label="جستجو مسیر"
                        return-object
                        item-title="title"
                        item-value="value"
                        ref="searchInput"
                        autocomplete="off"
                        prepend-inner-icon="mdi-play-box-multiple"
                        @update:model-value="addCourse(path)"
                    ></v-autocomplete>
                    <div
                        :ref="el => { if (el) coursesContainer[pathIndex] = el }"
                        class="courses-container"
                    >
                        <v-card
                            v-for="(course, courseIndex) in path.courses"
                            :key="courseIndex"
                            variant="tonal"
                            class="mb-1 course-item"
                            :data-course-id="course.value"
                        >
                            <v-card-text class="py-3 d-flex align-center justify-space-between">
                                <div>
                                    <v-btn
                                        color="primary"
                                        icon="mdi-drag"
                                        width="25"
                                        height="25"
                                        size="small"
                                        class="ml-2 course-drag-handle"
                                    ></v-btn>
                                    {{ course.title }}
                                </div>
                                <v-btn
                                    icon="mdi-close"
                                    color="error"
                                    variant="text"
                                    size="small"
                                    @click="removeCourse(path, course)"
                                ></v-btn>
                            </v-card-text>
                        </v-card>
                    </div>

                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
        <v-btn
            v-if="paths.length > 0"
            color="primary"
            @click="savePaths"
            :loading="isLoading"
            :disabled="isLoading"
        >
            ذخیره مسیرها
        </v-btn>
        <v-card
            v-if="paths.length == 0"
            class="text-center pa-8 rounded-lg"
            elevation="2"
        >
            <v-icon
                size="64"
                color="grey-lighten-1"
                class="mb-4"
            >
                mdi-folder-remove-outline
            </v-icon>
            <h3 class="text-h6 font-weight-medium text-grey-darken-1 mb-2">
                مسیری وجود ندارد
            </h3>
            <p class="text-body-1 text-grey">
                برای شروع یک مسیر جدید اضافه کنید
            </p>
            <v-btn
                color="primary"
                class="mt-4"
                @click="addPath"
                prepend-icon="mdi-plus"
            >
                افزودن مسیر جدید
            </v-btn>
        </v-card>
    </AdminLayout>
    <ShowMessage
        v-model:show="isShowMessage"
        :message="message"
        :type="typeMessage"
    />
</template>

<script setup>
import {Head, router} from '@inertiajs/vue3'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {nextTick, onMounted, ref, useTemplateRef, watch} from "vue";
import {useSortable} from "@vueuse/integrations/useSortable";
import {route} from "ziggy-js";
import ShowMessage from "@/Components/ShowMessage.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
const {adminPageTitle} = usePageTitle('مسیرهای آموزشی');

const props = defineProps({
    paths: Object,
    courses: Object,
})
const isShowMessage = ref(false);
const message = ref('');
const typeMessage = ref('');
const activePanel = ref(false);
const isLoading = ref(false);
const allPaths = ref(props.paths);
const courses = ref(props.courses);
const paths = ref([]);
onMounted(() => {
    if (allPaths.value.length > 0) {
        allPaths.value.forEach(path => {
            paths.value.push({
                id: path.id,
                title: path.title,
                selectedCourse: null,
                availableCourses: courses.value.filter(c =>
                    !path.items?.some(item => item.id === c.value)
                ),
                courses: path.items?.map(item => ({
                    value: item?.id,
                    title: item?.title || 'دوره حذف شده'
                })) || []
            });
        });
    }
})

/**************************Actions**************************/
const savePaths = () => {
    // آماده کردن دیتا برای سرور
    const payload = paths.value.map(path => ({
        id: path.id,
        title: path.title,
        courses: path.courses.map(course => course.value), // فقط شناسه دوره‌ها
    }));

    router.post(route('admin.paths.store'),payload,{
        preserveState: true,
        preserveScroll: true,
        onStart: () => {
            isLoading.value = true;
        },
        onFinish: () => {
            isLoading.value = false;
        }
    })
};
// افزودن مسیر جدید
const addPath = () => {
    paths.value.push({
        id: null,
        title: 'مسیر آموزشی جدید',
        selectedCourse: null,
        availableCourses: [...courses.value],
        courses: []
    });
};

// افزودن دوره به مسیر
const addCourse = (path) => {
    if (!path.selectedCourse) return;
    const selected = path.selectedCourse;
    path.courses.push(selected);
    path.availableCourses = path.availableCourses.filter(c => c.value !== selected.value);
    path.selectedCourse = null;
};

// حذف دوره از مسیر
const removeCourse = (path, course) => {
    path.courses = path.courses.filter(c => c.value !== course.value);
    path.availableCourses.push(course);
};

const removePath = async (index,id) => {
    const confirm = await $confirm("آیا از حذف این دسته اطمینان دارید؟");
    if (confirm){
        if(id) {
            const response = await axios.delete(route('admin.paths.destroy', id), []);
            console.log();
            if(response.data.status == 'success'){

                isShowMessage.value = true
                message.value = response.data.message
                typeMessage.value = 'success'
                paths.value.splice(index, 1);
            }
            else{
                isShowMessage.value = true
                message.value = response.data.message
                typeMessage.value = 'error'
            }
        }
        else{
            paths.value.splice(index, 1);
            isShowMessage.value = true
            message.value = 'حذف موفقیت آمیز بود'
            typeMessage.value = 'success'
        }
    }
};


/**************************Sortable**************************/
const pathsContainer = useTemplateRef('pathsContainer');

useSortable(pathsContainer, paths, {
    animation: 150,
    handle: '.path-drag-handle',
})

const coursesContainer = ref([]);

// فعال‌سازی درگ داخلی فقط وقتی پنل باز شد
watch(activePanel, (newVal) => {
    nextTick(() => {
        newVal.forEach(panelIndex => {
            const container = coursesContainer.value[panelIndex];
            if (!container) return;

            // اگر قبلاً sortable نشد، فعال کن
            if (!container._sortable) {
                useSortable(container, paths.value[panelIndex].courses, {
                    animation: 150,
                    handle: '.course-drag-handle',
                });
                container._sortable = true; // علامت زده شد که فعال شد
            }
        });
    });
}, { deep: true });

</script>
<style scoped>
/* Main container styles */

/* Expansion panel styles */
.v-expansion-panel {
    margin-bottom: 12px;
    border-radius: 8px !important;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
    border: 1px solid #eee;
}


.v-expansion-panel-text {
    background-color: #fff;
    padding: 16px 24px;
}


/* Search input */
.v-autocomplete {
    margin: 16px 0;
}

.v-autocomplete :deep(.v-field) {
    border-radius: 8px;
}

/* Drag handle */
.course-drag-handle {
    cursor: move;
    opacity: 0.6;
    transition: opacity 0.2s;
}

.course-drag-handle:hover {
    opacity: 1;
}

/* Empty state */
.courses-container:empty {
    min-height: 60px;
    border: 2px dashed #e0e0e0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9e9e9e;
    margin: 16px 0;
}


.courses-container:empty::after {
    content: "دوره های انتخاب شده اینجا قرار می‌گیرد";
}

.path-delete-btn {
    opacity: 0.5;
    transition: opacity 0.2s, transform 0.2s;
}

.path-delete-btn:hover {
    opacity: 1;
    transform: scale(1.1);
}

.v-expansion-panel:hover .path-delete-btn {
    opacity: 0.7;
}

/* Make sure the delete button is properly aligned */
.v-expansion-panel-title {
    padding-right: 8px;
}

/* Add some spacing between the title and delete button */
.flex-grow-1 {
    margin: 0 8px;
}
</style>
