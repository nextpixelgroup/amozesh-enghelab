<template>
    <div class="quiz-wrapper py-6" v-if="lesson.quiz?.id">

        <!-- Modal Dialog -->
        <v-dialog
            v-model="dialog"
            transition="dialog-bottom-transition"
            fullscreen
            class="quiz-dialog"
        >
            <!-- Activator Button (The Trigger) -->
            <template v-slot:activator="{ props: activatorProps }">
                <div class="d-flex justify-center">
                    <button
                        v-bind="activatorProps"
                        class="quiz-trigger-btn"
                        :class="{ 'completed': !!lesson.quiz.completed }"
                    >
                        <div class="btn-content">
                            <v-icon size="28" class="mb-1">
                                {{ !!lesson.quiz.completed ? 'mdi-check-decagram' : 'mdi-school-outline' }}
                            </v-icon>
                            <span class="btn-title">
                                {{ !!lesson.quiz.completed ? 'مشاهده نتیجه آزمون' : 'شرکت در آزمون آنلاین' }}
                            </span>
                            <span class="btn-subtitle" v-if="!lesson.quiz.completed">
                                برای سنجش یادگیری خود کلیک کنید
                            </span>
                        </div>
                        <div class="btn-bg-glow"></div>
                    </button>
                </div>
            </template>

            <!-- Quiz Content Inside Dialog -->
            <v-card class="quiz-main-card">
                <!-- Toolbar / Header -->
                <v-toolbar color="white" elevation="1" height="70" class="px-2">
                    <v-toolbar-title class="font-weight-bold text-primary">
                        <v-icon color="primary" class="ml-2">mdi-text-box-check-outline</v-icon>
                        {{ lesson.title }}
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon variant="tonal" color="grey-darken-1" @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar>

                <!-- Scrollable Content -->
                <div class="zo-questions-container">
                    <v-container class="max-width-800">

                        <!-- Quiz Info Header -->
                        <div class="quiz-header text-center mb-8 mt-4">
                            <h2 class="font-weight-black mb-3 text-grey-darken-3">
                                {{ lesson.quiz.title }}
                            </h2>
                            <p class="text-grey-darken-1">
                                {{ lesson.quiz.description }}
                            </p>
                            <v-chip v-if="!!lesson.quiz.completed" color="success" class="mt-3 font-weight-bold" variant="flat">
                                <v-icon start>mdi-check</v-icon>
                                آزمون تکمیل شده است
                            </v-chip>
                        </div>

                        <v-divider class="mb-8 border-opacity-25"></v-divider>

                        <!-- Questions List -->
                        <div class="questions-list">
                            <v-card
                                v-for="(question, qIndex) in lesson.quiz.questions"
                                :key="question.id"
                                class="question-card mb-6"
                                elevation="0"
                            >
                                <div class="question-number">سوال {{ qIndex + 1 }}</div>

                                <h3 class="question-text">{{ question.text }}</h3>

                                <div class="options-wrapper">
                                    <v-radio-group
                                        v-model="selectedAnswers[question.id]"
                                        :readonly="!!lesson.quiz.completed"
                                        hide-details
                                    >
                                        <div
                                            v-for="(option, oIndex) in question.options"
                                            :key="option.id"
                                            class="option-item"
                                            :class="{
                                                'is-selected': selectedAnswers[question.id] === option.id,
                                                'is-readonly': !!lesson.quiz.completed
                                            }"
                                        >
                                            <v-radio
                                                :value="option.id"
                                                color="primary"
                                                class="custom-radio"
                                            >
                                                <template v-slot:label>
                                                    <span class="option-label">{{ option.text }}</span>
                                                </template>
                                            </v-radio>
                                        </div>
                                    </v-radio-group>
                                </div>
                            </v-card>
                        </div>

                        <!-- Submit Button Area -->
                        <div class="submit-area text-center py-8" v-if="!lesson.quiz.completed">
                            <v-btn
                                size="x-large"
                                rounded="xl"
                                color="primary"
                                elevation="4"
                                height="56"
                                min-width="250"
                                @click="submitQuiz"
                                :loading="isLoading"
                                :disabled="isLoading"
                                class="submit-btn font-weight-bold"
                            >
                                ثبت نهایی و مشاهده نتیجه
                                <v-icon end class="mr-2">mdi-send-check</v-icon>
                            </v-btn>
                        </div>

                    </v-container>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script setup>
import { ref, shallowRef, watch } from 'vue'
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const props = defineProps({
    lesson: { type: Object },
})
const dialog = shallowRef(false)
const selectedAnswers = ref({});
const isLoading = ref(false)

watch(() => props.lesson, (newVal) => {
    if (newVal?.quiz?.completed && newVal.quiz.questions) {
        newVal.quiz.questions.forEach(question => {
            const selectedOption = question.options.find(opt => opt.selected);
            if (selectedOption) {
                selectedAnswers.value[question.id] = selectedOption.id;
            }
        });
    }
}, { immediate: true, deep: true });

const submitQuiz = () => {
    router.post(route('web.courses.lesson.quiz.store', props.lesson.id),
        { selectedAnswers: selectedAnswers.value },
        {
            onStart: () => { isLoading.value = true },
            onSuccess: () => {
                dialog.value = false
                isLoading.value = false
            },
            onError: () => { isLoading.value = false }
        }
    )
}
</script>

<style scoped>
/* --- Trigger Button Styles --- */
.quiz-trigger-btn {
    position: relative;
    background: var(--Secondary);
    color: white;
    padding: 16px 40px;
    border-radius: 16px;
    border: none;
    cursor: pointer;
    overflow: hidden;
    min-width: 280px;
}

.quiz-trigger-btn.completed {
    background: var(--Primary);
}

.btn-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.btn-title {
    font-size: 1.1rem;
    font-weight: 800;
    margin-bottom: 2px;
}

.btn-subtitle {
    font-size: 0.85rem;
    opacity: 0.9;
    font-weight: 300;
}

/* --- Dialog Layout --- */
.quiz-main-card {
    background-color: #f8f9fa !important;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.zo-questions-container {
    flex: 1;
    overflow-y: auto;
    background-color: #f3f4f6;
    padding-bottom: 50px;
}

.max-width-800 {
    max-width: 800px;
    margin: 0 auto;
}

/* --- Question Cards --- */
.question-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    position: relative;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 6px rgba(0,0,0,0.02) !important;
    overflow: visible;
    transition: transform 0.2s;
}

.question-card:hover {
    box-shadow: 0 10px 15px rgba(0,0,0,0.05) !important;
}

.question-number {
    position: absolute;
    top: -12px;
    right: 20px;
    background: var(--Primary);
    color: white;
    font-size: 0.8rem;
    font-weight: bold;
    padding: 4px 12px;
    border-radius: 20px;
    box-shadow: 0 4px 10px rgba(58, 134, 255, 0.3);
}

.question-text {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3436;
    margin-bottom: 20px;
    line-height: 1.6;
}

/* --- Options Styling --- */
.options-wrapper {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.option-item {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 8px 16px;
    transition: all 0.2s ease;
    cursor: pointer;
    margin-bottom: 5px;
}

.option-item:hover:not(.is-readonly) {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.option-item.is-selected {
    border-color: var(--Primary);
    background-color: #f0f7ff;
}

.option-label {
    font-size: 1rem;
    color: #495057;
    font-weight: 500;
    width: 100%;
    display: block;
}

.is-selected .option-label {
    color: var(--Primary);
    font-weight: 700;
}

/* Fix Vuetify radio alignment inside custom box */
.custom-radio {
    margin: 0 !important;
    padding: 0 !important;
}

:deep(.v-selection-control) {
    width: 100%;
}
:deep(.v-label) {
    opacity: 1 !important;
}

/* --- Submit Button --- */
.submit-btn {
    letter-spacing: 0.5px;
    transition: transform 0.1s;
}
.submit-btn:active {
    transform: scale(0.98);
}
</style>
