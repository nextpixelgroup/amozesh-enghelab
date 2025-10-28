<template>
    <div class="accordion-item quiz">
        <div class="accordion-header">
            <v-text-field v-model="localQuiz.title" placeholder="عنوان آزمون" />
            <input type="checkbox" v-model="localQuiz.is_active" /> فعال
            <v-btn @click="toggleOpen">{{ open ? 'بستن' : 'باز کردن' }}</v-btn>
        </div>

        <div v-show="open" class="accordion-body">
            <v-textarea v-model="localQuiz.description" placeholder="توضیحات آزمون"></v-textarea>

            <draggable v-model="localQuiz.questions" item-key="id" handle=".drag-handle">
                <template #item="{ element: question, index }">
                    <QuestionComponent
                        :question="question"
                        :index="index"
                        @update-question="updateQuestion(index, $event)"
                        @remove-question="removeQuestion(index)"
                    />
                </template>
            </draggable>

            <v-btn type="button" @click="addQuestion">افزودن سوال</v-btn>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import QuestionComponent from './QuestionComponent.vue';
import draggable from 'vuedraggable';

const props = defineProps({
    quiz: Object
});

const emit = defineEmits(['update-quiz']);

const open = ref(true);
const localQuiz = reactive({
    ...props.quiz,
    questions: props.quiz?.questions ?? []
});

watch(localQuiz, () => {
    emit('update-quiz', localQuiz);
}, { deep: true });

function toggleOpen() {
    open.value = !open.value;
}

function addQuestion() {
    localQuiz.questions.push({
        id: Date.now(),
        question: '',
        explanation: '',
        options: [
            { option: '', is_correct: false },
            { option: '', is_correct: false },
            { option: '', is_correct: false },
            { option: '', is_correct: false },
        ]
    });
}

function updateQuestion(index, updatedQuestion) {
    localQuiz.questions[index] = updatedQuestion;
}

function removeQuestion(index) {
    localQuiz.questions.splice(index, 1);
}
</script>
