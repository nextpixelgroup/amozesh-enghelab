<template>
    <div class="accordion-item">
        <div class="accordion-header">
            <span class="drag-handle">::</span>
            <v-text-field v-model="localLesson.title" placeholder="عنوان درس" />
            <input type="checkbox" v-model="localLesson.is_active" /> فعال
            <v-btn @click="$emit('remove-lesson')">حذف</v-btn>
            <v-btn @click="toggleOpen">{{ open ? 'بستن' : 'باز کردن' }}</v-btn>
        </div>

        <div v-show="open" class="accordion-body">
            <v-textarea v-model="localLesson.description" placeholder="توضیحات درس"></v-textarea>

            <!-- گزینه افزودن آزمون -->
            <div>
                <label>
                    <input type="checkbox" v-model="hasQuiz" /> افزودن آزمون
                </label>
            </div>

            <QuizComponent
                v-if="hasQuiz"
                :quiz="localLesson.quiz"
                @update-quiz="updateQuiz"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import QuizComponent from './QuizComponent.vue';

const props = defineProps({
    lesson: Object,
    index: Number
});

const emit = defineEmits(['update-lesson', 'remove-lesson']);

const open = ref(true);

const localLesson = reactive({...props.lesson});
const hasQuiz = ref(localLesson.quiz !== null);

watch(localLesson, () => {
    if (hasQuiz.value && !localLesson.quiz) {
        localLesson.quiz = {
            id: Date.now(),
            title: '',
            description: '',
            is_active: true,
            questions: []
        };
    } else if (!hasQuiz.value) {
        localLesson.quiz = null;
    }
    emit('update-lesson', localLesson);
}, { deep: true });

function toggleOpen() {
    open.value = !open.value;
}

function updateQuiz(updatedQuiz) {
    localLesson.quiz = updatedQuiz;
}
</script>
