<template>
    <div class="accordion-item">
        <div class="accordion-header">
            <span class="drag-handle">::</span>
            <v-text-field v-model="localSeason.title" placeholder="عنوان سرفصل" />
            <input type="checkbox" v-model="localSeason.is_active" /> فعال
            <v-btn @click="$emit('remove-season')">حذف</v-btn>
            <v-btn @click="toggleOpen">{{ open ? 'بستن' : 'باز کردن' }}</v-btn>
        </div>

        <div v-show="open" class="accordion-body">
            <v-textarea v-model="localSeason.description" placeholder="توضیحات سرفصل"></v-textarea>

            <draggable v-model="localSeason.lessons" item-key="id" handle=".drag-handle">
                <template #item="{ element: lesson, index }">
                    <LessonComponent
                        :lesson="lesson"
                        :index="index"
                        @update-lesson="updateLesson(index, $event)"
                        @remove-lesson="removeLesson(index)"
                    />
                </template>
            </draggable>

            <v-btn type="button" @click="addLesson">افزودن درس</v-btn>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
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
}, { deep: true });

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
