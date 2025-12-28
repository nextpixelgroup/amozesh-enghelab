<template>
    <WebLayout>
        <v-container class="fill-height d-flex flex-column align-center justify-center bg-white">

            <v-card
                class="px-6 py-8 text-center"
                elevation="10"
                max-width="400"
                width="100%"
                rounded="xl"
                color="white"
            >
                <v-icon
                    size="64"
                    :color="isSuccess ? 'green-accent-4' : 'red-accent-4'"
                    class="animate-icon"
                >
                    {{ isSuccess ? 'mdi-check' : 'mdi-close' }}
                </v-icon>
                <!-- عنوان -->
                <h1 class="text-h5 font-weight-black text-uppercase mb-2 text-black">
                    {{ isSuccess ? 'پرداخت موفق' : 'پرداخت ناموفق' }}
                </h1>

                <!-- جزئیات تراکنش (لیست ساده) -->
                <div class="py-6 text-left">
                    <!-- درگاه -->
                    <div class="d-flex justify-space-between mb-2" v-if="driver">
                        <span class="text-body-2 text-grey-darken-1">درگاه پرداخت:</span>
                        <span class="text-body-2 font-weight-bold text-black">{{ driver }}</span>
                    </div>

                    <!-- کد رهگیری -->
                    <div class="d-flex justify-space-between mb-2" v-if="transactionId">
                        <span class="text-body-2 text-grey-darken-1">کد رهگیری:</span>
                        <span class="text-body-2 font-weight-bold font-monospace text-black">{{ transactionId }}</span>
                    </div>

                    <!-- تاریخ (فرضی یا از پراپ) -->
                    <div class="d-flex justify-space-between">
                        <span class="text-body-2 text-grey-darken-1">وضعیت:</span>
                        <span class="text-body-2 font-weight-bold" :class="isSuccess ? 'text-green' : 'text-red'">
                            {{ message }}
                        </span>
                    </div>
                </div>

                <!-- دکمه (دورخط دار مشابه تصویر) -->
                <v-btn
                    block
                    height="48"
                    rounded="pill"
                    variant="elevated"
                    :color="isSuccess ? 'green' : 'red'"
                    class="text-body-2 font-weight-bold mt-2"
                    @click="goToOrders"
                    :loading="isLoading"
                    append-icon="mdi-chevron-left"
                >
                    بازگشت به سفارشات
                </v-btn>

            </v-card>

        </v-container>
    </WebLayout>
</template>

<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref, computed } from "vue";

const props = defineProps({
    driver: String,
    transactionId: String,
    status: String, // "success" | "failed"
    message: String,
});

const isLoading = ref(false);

const isSuccess = computed(() => props.status === 'success');

const goToOrders = () => {
    isLoading.value = true;
    router.visit(route('panel.orders.index'), { method: 'get' });
};
</script>

<style scoped>
/* رفع مشکل فونت‌ها */
:deep(.v-card),
:deep(.text-h5),
:deep(.text-body-1),
:deep(.text-body-2),
:deep(.v-btn) {
    font-family: inherit !important;
}

.font-weight-black {
    font-weight: 800 !important;
}

.font-monospace {
    font-family: 'Courier New', Courier, monospace !important;
    letter-spacing: 0.5px;
}

/* استایل دایره آیکون (Floating Icon) */
.status-icon-wrapper {
    width: 100px;
    height: 100px;
    background-color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    /* سایه نرم دور دایره مشابه تصویر */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
    border: 1px solid #f5f5f5;
    animation: bounceIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}

/* انیمیشن ورود آیکون */
.animate-icon {
    animation: scaleUp 0.5s 0.3s backwards;
}

@keyframes bounceIn {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.1); opacity: 1; }
    100% { transform: scale(1); }
}

@keyframes scaleUp {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}

/* تنظیم سایه کارت برای اینکه روی زمینه سفید دیده شود */
.v-card {
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
    border: 1px solid #f0f0f0;
}
</style>
