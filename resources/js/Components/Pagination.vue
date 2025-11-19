<template>
    <v-row justify="center" align="center" class="gap-2" v-if="length > 1">

        <v-pagination
            v-model="page"
            :length="length"
            :total-visible="6"
            @update:modelValue="updatePage"
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
    length: {
        type: Number,
        required: true,
        default: 1
    }
})

const emit = defineEmits(['update:modelValue', 'changePage'])

// حالت محلی برای sync شدن با v-model
const page = ref(props.modelValue)
function updatePage(page) {
    emit('update:modelValue', page) // برای v-model
    emit('changePage', page)        // برای callback سفارشی
}
// هم‌زمان‌سازی با مقدار والد (در صورت تغییر برنامه‌ای)
watch(
    () => props.modelValue,
    (val) => (page.value = val)
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
