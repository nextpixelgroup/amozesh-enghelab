<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    action: String,
    method: String,
    inputs: Object,
});

const handleSuccess = () => {
    window.location.href = props.inputs.successUrl;
};

const handleCancel = () => {
    window.location.href = props.inputs.cancelUrl;
};
</script>

<template>
    <v-container class="py-10" style="max-width: 500px">
        <v-card elevation="4" class="pa-6">

            <h2 class="mb-4">
                {{ props.inputs.title }}
            </h2>

            <p class=" mb-4">
                {{ props.inputs.description }}
            </p>

            <v-divider class="my-4" />

            <div class="d-flex justify-space-between mb-6">
                <span>{{ props.inputs.amountLabel }}:</span>
                <strong>{{ props.inputs.price }} تومان</strong>
            </div>

            <v-btn
                color="green"
                block
                class="mb-4"
                @click="handleSuccess"
            >
                {{ props.inputs.payButton }}
            </v-btn>

            <v-btn
                color="red"
                block
                variant="outlined"
                @click="handleCancel"
            >
                {{ props.inputs.cancelButton }}
            </v-btn>

        </v-card>
    </v-container>
</template>
