<template>
    <div class="zo-header-section mb-5">
        <v-row class="align-center">
            <v-col class="v-col-12">
                <div class="zo-info d-lg-flex d-sm-none">
                    <div class="zo-icon elevation-4">
                        <i class="mdi mdi-format-list-text"></i>
                    </div>
                    <div class="zo-name">
                        <strong class="d-block mb-1">افزودن سرفصل</strong>
                        <span>در این بخش می توانید سرفصل ها و دروس دوره را ایجاد کنید.</span>
                    </div>
                </div>
            </v-col>
        </v-row>
    </div>

    <v-card class="pa-3 mb-3 elevation-2">
        <div class="zo-accordion">
            <div class="zo-content">
                <div class="zo-actions">
                    <v-switch
                        v-model="localSeason.is_active"
                        hide-details
                        color="primary"
                        class="me-3"
                    />
                    <v-btn
                        icon
                        variant="text"
                        @click="$emit('remove-season')"
                    >
                        <v-icon>mdi-trash-can</v-icon>
                    </v-btn>

                    <v-btn
                        icon
                        variant="text"
                        @click="toggleOpen"
                    >
                        <v-icon>{{ open ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                    </v-btn>
                </div>
                <div class="d-flex align-center">
                    <v-text-field
                        v-model="localSeason.title"
                        hide-details
                        variant="outlined"
                        placeholder="عنوان سرفصل"
                        prepend-inner-icon="mdi-text-short"
                        class="me-3"
                    />
                    <span class="zo-drag me-2">::</span>
                </div>
            </div>

            <div v-show="open" class="zo-body mt-3">
                <v-textarea
                    v-model="localSeason.description"
                    hide-details
                    variant="outlined"
                    placeholder="توضیحات سرفصل"
                    prepend-inner-icon="mdi-text"
                ></v-textarea>

                <draggable v-model="localSeason.lessons" item-key="id" handle=".zo-drag">
                    <template #item="{ element: lesson, index }">
                        <LessonComponent
                            :lesson="lesson"
                            :index="index"
                            @update-lesson="updateLesson(index, $event)"
                            @remove-lesson="removeLesson(index)"
                        />
                    </template>
                </draggable>

                <v-btn type="button" color="primary" class="mt-3" @click="addLesson">
                    افزودن درس
                </v-btn>
            </div>
        </div>
    </v-card>
</template>

<script setup>
import {ref, reactive, watch} from 'vue';
import LessonComponent from './LessonComponent.vue';
import draggable from 'vuedraggable';

const props = defineProps({
    season: Object,
    index: Number
});

const emit = defineEmits(['update-season', 'remove-season']);

const open = ref(true);
const localSeason = reactive({...props.season});

watch(localSeason, () => {
    emit('update-season', localSeason);
}, {deep: true});

function toggleOpen() {
    open.value = !open.value;
}

function addLesson() {
    localSeason.lessons.push({
        id: Date.now(),
        title: '',
        description: '',
        is_active: true,
        quiz: null
    });
}

function updateLesson(index, updatedLesson) {
    localSeason.lessons[index] = updatedLesson;
}

function removeLesson(index) {
    localSeason.lessons.splice(index, 1);
}
</script>
<style scoped>
.zo-actions {
    display: flex;
    align-items: center;
    position: absolute;
    top: 10.5px;
    left: 45px;
    z-index: 15
}
</style>
