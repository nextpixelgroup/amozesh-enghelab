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

            <div ref="container" class="lessons-container">
                <div v-for="(lesson, index) in localSeason.lessons" :key="lesson.id" class="lesson-item">
                    <div class="drag-handle">☰</div>
                    <div class="lesson-content">
                        <LessonComponent
                            :lesson="lesson"
                            :index="index"
                            @update-lesson="updateLesson(index, $event)"
                            @remove-lesson="removeLesson(index)"
                        />
                    </div>
                </div>
            </div>

            <v-btn type="button" @click="addLesson">افزودن درس</v-btn>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch, nextTick, onMounted } from 'vue';
import { useSortable } from '@vueuse/integrations/useSortable';
import LessonComponent from './LessonComponent.vue';

const props = defineProps({
    season: Object,
    index: Number
});

const emit = defineEmits(['update-season', 'remove-season']);

const open = ref(true);
const container = ref(null);
const localSeason = reactive({...props.season});

// Initialize sortable
const { option } = useSortable(container, localSeason.lessons, {
  animation: 150,
  handle: '.drag-handle',
  onUpdate: () => {
    // Emit update when order changes
    emit('update-season', localSeason);
  },
});

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

<style scoped>
.accordion-item {
    margin-bottom: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    overflow: hidden;
}

.accordion-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background-color: #f9fafb;
    cursor: pointer;
}

.accordion-body {
    padding: 1rem;
    background-color: white;
}

.lessons-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin: 1rem 0;
}

.lesson-item {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    background: white;
    transition: all 0.2s ease;
}

.lesson-item:hover {
    background-color: #f9fafb;
}

.drag-handle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    cursor: move;
    color: #9ca3af;
    user-select: none;
    padding-top: 0.5rem;
}

.drag-handle:hover {
    color: #4b5563;
}

.lesson-content {
    flex: 1;
}
</style>
