<template>
    <v-dialog v-model="visible" persistent max-width="400">
        <v-card>
            <v-card-title class="text-h6">{{ title }}</v-card-title>
            <v-card-text>
                {{ message }}
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="grey" variant="text" @click="cancel">لغو</v-btn>
                <v-btn :color="confirmColor" variant="flat" @click="confirm">تأیید</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import {ref} from 'vue'

const visible = ref(false)
const message = ref('')
const title = ref('تأیید عملیات')
const confirmColor = ref('red')

let resolver = null

function open({msg, ttl = 'تأیید عملیات', color = 'red'}) {
    message.value = msg
    title.value = ttl
    confirmColor.value = color
    visible.value = true

    return new Promise((resolve) => {
        resolver = resolve
    })
}

function confirm() {
    visible.value = false
    resolver?.(true)
}

function cancel() {
    visible.value = false
    resolver?.(false)
}

// برای استفاده global export می‌کنیم
defineExpose({open})
</script>
