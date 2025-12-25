<template>
    <div class="text-center pa-4" v-if="lesson.quiz?.id">
        <v-dialog
            v-model="dialog"
            transition="dialog-bottom-transition"
            fullscreen
        >
            <template v-slot:activator="{ props: activatorProps }">
                <v-btn
                    variant="elevated"
                    color="primary"
                    prepend-icon="mdi-book-open-variant"
                    v-bind="activatorProps"
                >
                    {{ !!lesson.quiz.completed ? 'بررسی آزمون' : 'شرکت در آزمون' }}
                </v-btn>
            </template>
            <v-card>
                <v-toolbar>
                    <v-toolbar-title>{{ lesson.title }}</v-toolbar-title>
                    <v-toolbar-items>
                        <v-btn icon="mdi-close" @click="dialog = false"></v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card flat class="zo-questions">
                    <v-card-text class="zo-content">
                        <h5>{{ lesson.quiz.title }}</h5>
                        <p>{{ lesson.quiz.description }}</p>
                        <v-divider class="my-5"></v-divider>
                        <div class="zo-question" v-for="(question, qIndex) in lesson.quiz.questions">
                            <!-- اضافه کردن readonly برای جلوگیری از تغییر پاسخ‌ها در حالت مشاهده نتیجه -->
                            <v-radio-group
                                v-model="selectedAnswers[question.id]"
                                :label="`${qIndex+1}- ${question.text}`"
                                :readonly="!!lesson.quiz.completed"
                            >
                                <v-radio v-for="(option, oIndex) in question.options" :label="option.text" :value="option.id" :key="option.id"></v-radio>
                            </v-radio-group>
                        </div>
                        <div class="text-center" v-if="!lesson.quiz.completed">
                            <v-btn
                                variant="elevated"
                                color="primary"
                                @click="submitQuiz"
                                :loading="isLoading"
                                :disabled="isLoading"
                            >
                                ثبت نهایی پاسخ‌ها
                            </v-btn>
                        </div>
                    </v-card-text>
                </v-card>
            </v-card>
        </v-dialog>
    </div>
</template>

<script setup>
// اضافه کردن watch به ایمپورت‌ها
import {ref, shallowRef, watch} from 'vue'
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const props = defineProps({
    lesson: {
        type: Object,
    },
})
const dialog = shallowRef(false)
const notifications = shallowRef(false)
const sound = shallowRef(true)
const widgets = shallowRef(false)
const selectedAnswers = ref({});

const isLoading = ref(false)

// --- شروع کد اضافه شده ---
// این بخش چک می‌کند اگر آزمون کامل شده بود، جواب‌های کاربر را در فرم پر می‌کند
watch(() => props.lesson, (newVal) => {
    if (newVal?.quiz?.completed && newVal.quiz.questions) {
        newVal.quiz.questions.forEach(question => {
            // پیدا کردن گزینه‌ای که selected برابر true دارد
            const selectedOption = question.options.find(opt => opt.selected);

            // اگر گزینه‌ای پیدا شد، آی‌دی آن را به عنوان جواب انتخاب شده ست می‌کنیم
            if (selectedOption) {
                selectedAnswers.value[question.id] = selectedOption.id;
            }
        });
    }
}, { immediate: true, deep: true });
// --- پایان کد اضافه شده ---

const submitQuiz = () => {
    router.post(route('web.courses.lesson.quiz.store', props.lesson.id),
        {
            selectedAnswers: selectedAnswers.value,
        },
        {
            onStart: () => {
                //isLoading.value = true
            },
            onSuccess: () => {
                dialog.value = false
                isLoading.value = false
            },
            onError: () => {
                isLoading.value = false
            }
        }
    )
}
</script>

<style>
/* استایل‌ها بدون تغییر باقی ماندند */
.zo-content {
    font-size: 1rem
}

.zo-content h5 {
    margin: 0 0 10px
}

.zo-questions {
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow-y: scroll
}

.zo-questions .zo-question .v-label {
    font-family: 'Estedad-Bold';
    opacity: 1
}

.zo-questions .zo-question .v-radio .v-label {
    font-family: 'Estedad-Regular'
}
</style>
