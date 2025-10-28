<template>
    <div class="question-card">
        <div class="accordion-header">
            <span class="drag-handle">::</span>
            <input v-model="localQuestion.question" placeholder="متن سوال" />
            <button @click="$emit('remove-question')">حذف سوال</button>
            <button @click="toggleOpen">{{ open ? 'بستن' : 'باز کردن' }}</button>
        </div>

        <div v-show="open" class="accordion-body">
            <textarea v-model="localQuestion.explanation" placeholder="توضیحات سوال"></textarea>

            <div v-for="(option, idx) in localQuestion.options" :key="idx">
                <OptionField v-model="localQuestion.options[idx]" :index="idx" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import OptionField from './OptionField.vue';

const props = defineProps({
    question: Object,
    index: Number
});

const emit = defineEmits(['update-question', 'remove-question']);

const open = ref(true);
const localQuestion = reactive({...props.question});

watch(localQuestion, () => {
    emit('update-question', localQuestion);
}, { deep: true });

function toggleOpen() {
    open.value = !open.value;
}
</script>
