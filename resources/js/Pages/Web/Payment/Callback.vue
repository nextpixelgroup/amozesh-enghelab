<template>
    <WebLayout>
        <v-container class="py-10" style="max-width: 500px">
            <v-card elevation="4" class="pa-6" :color="props.status === 'success' ? 'green-lighten-5' : 'red-lighten-5'">

                <div class="text-center mb-4">
                    <h2>
                        {{ props.status === 'success' ? 'پرداخت موفق' : 'پرداخت ناموفق' }}
                    </h2>
                </div>

                <v-divider class="my-4" />

                <div class="d-flex justify-space-between mb-2" v-if="props.driver">
                    <span>درگاه امن:</span>
                    <strong>{{ props.driver }}</strong>
                </div>

                <div class="d-flex justify-space-between mb-4" v-if="props.transactionId">
                    <span>شماره تراکنش:</span>
                    <strong>{{ props.transactionId }}</strong>
                </div>

                <v-alert
                    :type="props.status === 'success' ? 'success' : 'error'"
                    variant="tonal"
                    class="mt-4"
                >
                    {{props.message}}
                </v-alert>

                <v-btn
                    block
                    color="primary"
                    variant="flat"
                    class="mb-3 mt-3"
                    @click="goToOrders"
                    :loading="isLoading"
                    :disabled="isLoading"
                >
                    مشاهده سفارشات
                </v-btn>
            </v-card>
        </v-container>
    </WebLayout>
</template><script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {ref} from "vue";

const props = defineProps({
    driver: String,
    transactionId: String,
    status: String, // "success" | "failed"
    message: String,
});
const isLoading = ref(false);
const goToOrders = () => {
    isLoading.value = true;
    router.visit(route('panel.orders.index'), { method: 'get' });
};
</script>


