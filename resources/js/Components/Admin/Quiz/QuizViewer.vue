<template>
    <v-card elevation="2" border class="h-100 mb-4">
        <!-- هدر سوال -->
        <v-toolbar color="grey-lighten-4" density="compact">
            <v-toolbar-title class="text-subtitle-1 font-weight-bold text-primary">
                <v-icon start color="primary">mdi-help-circle-outline</v-icon>
                سوال {{ currentQuestionIndex + 1 }} از {{ totalQuestions }}
            </v-toolbar-title>
            <v-spacer></v-spacer>
        </v-toolbar>

        <!-- نمایش اسلایدی سوالات -->
        <v-window v-model="currentQuestionIndex" class="pa-2">
            <v-window-item
                v-for="(question, index) in questionsList"
                :key="question.id"
                :value="index"
            >
                <v-card-text>
                    <h3 class="text-h6 mb-4 font-weight-bold" style="line-height: 1.6;">
                        {{ question.question }}
                    </h3>

                    <v-divider class="mb-4"></v-divider>

                    <v-list density="compact" class="bg-transparent">
                        <v-list-item
                            v-for="(option, idx) in question.options"
                            :key="option.id"
                            class="mb-2 rounded border"
                            :class="{
                                'bg-green-lighten-5 border-success': option.is_correct,
                                'bg-red-lighten-5 border-error': (question.userSelected === option.id && !option.is_correct),
                                'bg-grey-lighten-5': !option.is_correct && question.userSelected !== option.id
                            }"
                            :style="getBorderStyle(option, question.userSelected)"
                        >
                            <template v-slot:prepend>
                                <v-icon :color="getIconColor(option, question.userSelected)">
                                    {{ getIconName(option, question.userSelected, idx) }}
                                </v-icon>
                            </template>

                            <v-list-item-title
                                class="py-2 text-wrap d-flex align-center flex-wrap"
                                :class="{
                                    'font-weight-bold text-success-darken-2': option.is_correct,
                                    'text-error': (question.userSelected === option.id && !option.is_correct)
                                }"
                                style="white-space: normal; line-height: 1.5;"
                            >
                                {{ option.option }}

                                <!-- چیپ نمایش انتخاب کاربر -->
                                <v-chip
                                    v-if="question.userSelected === option.id"
                                    size="x-small"
                                    class="mr-2 mt-1 mt-sm-0"
                                    :color="option.is_correct ? 'success' : 'error'"
                                    variant="flat"
                                >
                                    <v-icon start size="small">mdi-account-check</v-icon>
                                    انتخاب کاربر
                                </v-chip>
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-card-text>
            </v-window-item>
        </v-window>

        <v-divider></v-divider>

        <!-- دکمه های ناوبری -->
        <v-card-actions class="justify-space-between pa-4 bg-grey-lighten-5">
            <v-btn
                variant="outlined"
                color="primary"
                @click="prevQuestion"
                :disabled="currentQuestionIndex === 0"
            >
                <v-icon right>mdi-chevron-right</v-icon>
                قبلی
            </v-btn>

            <v-btn
                variant="elevated"
                color="primary"
                @click="nextQuestion"
                :disabled="currentQuestionIndex === totalQuestions - 1"
            >
                بعدی
                <v-icon left>mdi-chevron-left</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

const props = defineProps({
    quiz: {
        type: Object,
        required: true
    },
    title: {
        type: String,
        default: 'آزمون'
    }
});

const questionsList = computed(() => props.quiz.questions || []);
const currentQuestionIndex = ref(0);
const totalQuestions = computed(() => questionsList.value.length);

const nextQuestion = () => {
    if (currentQuestionIndex.value < totalQuestions.value - 1) currentQuestionIndex.value++;
};
const prevQuestion = () => {
    if (currentQuestionIndex.value > 0) currentQuestionIndex.value--;
};

// --- Helper Functions ---
const getIconColor = (option: any, userSelectedId: number) => {
    if (option.is_correct) return 'success';
    if (userSelectedId === option.id) return 'error';
    return 'grey-lighten-1';
};

const getIconName = (option: any, userSelectedId: number, idx: number) => {
    if (option.is_correct) return 'mdi-check-circle';
    if (userSelectedId === option.id) return 'mdi-close-circle';
    return `mdi-numeric-${idx+1}-circle-outline`;
};

const getBorderStyle = (option: any, userSelectedId: number) => {
    if (option.is_correct) return 'border-color: #4CAF50 !important; border-width: 1px !important;';
    if (userSelectedId === option.id) return 'border-color: #FF5252 !important; border-width: 1px !important;';
    return '';
};
</script>

<style scoped>
.border-success {
    border: 1px solid #4CAF50 !important;
}
.border-error {
    border: 1px solid #FF5252 !important;
}
</style>
