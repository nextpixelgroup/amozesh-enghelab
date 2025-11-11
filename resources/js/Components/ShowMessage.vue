<template>
    <v-snackbar
        v-model="snackbar"
        :color="props.type"
        location="bottom"
        :timeout="timeout"
        :class="props.type"
        content-class="snackbar-content"
    >
        <div class="d-flex align-center message-content">

            <v-icon v-if="props.type === 'success'" class="me-2" size="large">mdi-check-circle</v-icon>
            <v-icon v-else-if="props.type === 'error'" class="me-2" size="large">mdi-alert-circle</v-icon>
            <v-icon v-else class="me-2" size="large">mdi-information</v-icon>
            {{ message }}
        </div>

        <template #actions>
            <v-btn
                variant="text"
                color="white"
                icon
                @click="snackbar = false"
                class="close-btn"
                size="small"
            >
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    message: {
        type: String,
        default: ''
    },
    type: {
        type: String,
        default: 'warning', // 'success', 'error', 'info'
        validator: (value) => ['success', 'error', 'info', 'warning'].includes(value)
    },
    timeout: {
        type: Number,
        default: 8000
    }
})

const snackbar = ref(false)

// Watch for changes in props
watch(() => props.show, (newVal) => {
    snackbar.value = newVal
})

// Emit events when snackbar is closed
const emit = defineEmits(['update:show', 'closed'])

// Watch for snackbar state changes
watch(snackbar, (newVal) => {
    if (!newVal) {
        emit('update:show', false)
        emit('closed')
    }
})
</script>
