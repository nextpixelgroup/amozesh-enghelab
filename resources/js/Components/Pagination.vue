<template>
    <v-row justify="center" align="center" class="gap-2" v-if="totalPages > 1">

        <v-pagination
            v-model="localPage"
            :length="totalPages"
            :total-visible="7"

            @update:modelValue="emit('update:modelValue', $event)"
        >

        </v-pagination>

    </v-row>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: Number,
        required: true,
        default: 1
    },
    totalPages: {
        type: Number,
        required: true,
        default: 1
    }
})

const emit = defineEmits(['update:modelValue'])

// حالت محلی برای sync شدن با v-model
const localPage = ref(props.modelValue)

// هم‌زمان‌سازی با مقدار والد (در صورت تغییر برنامه‌ای)
watch(
    () => props.modelValue,
    (val) => (localPage.value = val)
)
</script>

<style scoped>
.v-btn {
    transition: opacity 0.2s ease-in-out;
}
.v-btn[disabled] {
    opacity: 0.6;
}
</style>
