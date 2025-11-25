<template>
    <WebLayout title="Checkout">
        <div class="zo-cart-section">
            <v-container>
                <v-row dense>
                    <v-col cols=12>
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#">خانه</a>
                                    </li>
                                    <li>
                                        <a href="#">فروشگاه کتاب</a>
                                    </li>
                                    <li>
                                        <a href="#">سبد خرید</a>
                                    </li>
                                    <li>
                                        <span>پرداخت آنلاین</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row>
                    <v-col lg="8" cols="12">
                        <div class="zo-section">
                            <div class="zo-content">
                                <h3>جزئیات پرداخت</h3>
                                <v-row dense>
                                    <v-col md="6" cols="12">
                                        <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-account-circle" label="نام">
                                        </v-text-field>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-account-circle" label="نام خانوادگی">
                                        </v-text-field>
                                    </v-col>
                                    <v-col md="8" cols="12">
                                        <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-map" label="آدرس کامل پستی">
                                        </v-text-field>
                                    </v-col>
                                    <v-col md="4" cols="12">
                                        <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-map-marker" label="کد پستی">
                                        </v-text-field>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-cellphone" label="شماره موبایل">
                                        </v-text-field>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-at" label="پست الکترونیک">
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea  hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-text" label="یادداشت‌هایی درباره سفارش شما، مثلاً نکات ویژه برای تحویل.">
                                        </v-textarea>
                                    </v-col>
                                </v-row>
                            </div>
                        </div>
                        <div class="zo-actions">
                            <v-btn variant="tonal" color="primary" class="zo-action">
                                بروزرسانی سبد خرید
                            </v-btn>
                            <v-btn flat color="primary" class="zo-action">
                                اقدام به پرداخت
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";

</script>
