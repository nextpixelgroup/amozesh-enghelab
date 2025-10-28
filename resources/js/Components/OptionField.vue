<template>
    <div class="option-field">
        <v-text-field v-model="option.option" placeholder="متن گزینه" />
        <label>
            <input type="radio" name="correct-{{index}}" v-model="option.is_correct" :value="true" /> درست
        </label>
    </div>
</template>

<script setup>
import { reactive, watch } from 'vue';
const props = defineProps({
    modelValue: Object,
    index: Number
});
const emit = defineEmits(['update:modelValue']);

const option = reactive({...props.modelValue});

watch(option, () => {
    emit('update:modelValue', option);
}, { deep: true });
</script>
