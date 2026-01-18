<template>
    <v-dialog v-model="visible" persistent max-width="420">
        <v-card class="pa-4 rounded-lg elevation-6">
            <v-card-title class="font-weight-bold pb-2 d-flex justify-center">
                {{ title }}
            </v-card-title>

            <v-divider class="mb-4"></v-divider>

            <v-card-text class="text-center text-grey-darken-2 px-6">
                {{ message }}
            </v-card-text>

            <v-card-actions class="mt-4 d-flex justify-center gap-3">
                <v-btn
                    color="red-darken-1"
                    variant="flat"
                    rounded="lg"
                    class="px-6"
                    @click="cancel"
                >
                    لغو
                </v-btn>

                <v-btn
                    :color="confirmColor"
                    variant="flat"
                    rounded="lg"
                    class="px-6 text-white"
                    @click="confirm"
                >
                    تأیید
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import {ref} from 'vue'

const visible = ref(false)
const message = ref('')
const title = ref('تأیید عملیات')
const confirmColor = ref('primary')

let resolver = null

function open({msg, ttl = 'تأیید عملیات'}) {
    message.value = msg
    title.value = ttl
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

defineExpose({open})
</script>
