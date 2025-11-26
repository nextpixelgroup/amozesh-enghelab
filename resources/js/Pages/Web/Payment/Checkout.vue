<template>
    <WebLayout title="Checkout">
        <div class="zo-checkout-section">
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
                <v-row dense>
                    <v-col lg="8" cols="12">
                        <div class="zo-section">
                            <div class="zo-content">
                                <h3>جزئیات پرداخت</h3>
                                <div class="zo-form">
                                    <v-row dense>
                                        <v-col md="6" cols="12">
                                            <label>نام</label>
                                            <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-account-circle" label="">
                                            </v-text-field>
                                        </v-col>
                                        <v-col md="6" cols="12">
                                            <label>نام خانوادگی</label>
                                            <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-account-circle" label="">
                                            </v-text-field>
                                        </v-col>
                                        <v-col md="8" cols="12">
                                            <label>آدرس کامل پستی</label>
                                            <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-map" label="">
                                            </v-text-field>
                                        </v-col>
                                        <v-col md="4" cols="12">
                                            <label>کد پستی</label>
                                            <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-map-marker" label="">
                                            </v-text-field>
                                        </v-col>
                                        <v-col md="6" cols="12">
                                            <label>شماره موبایل</label>
                                            <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-cellphone" label="">
                                            </v-text-field>
                                        </v-col>
                                        <v-col md="6" cols="12">
                                            <label>پست الکترونیک</label>
                                            <v-text-field hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-at" label="">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <label>توضیحات</label>
                                            <v-textarea hide-details density="compact" variant="outlined" prepend-inner-icon="mdi-text" placeholder="یادداشت‌هایی درباره سفارش شما، مثلاً نکات ویژه برای تحویل.">
                                            </v-textarea>
                                        </v-col>
                                    </v-row>
                                </div>
                            </div>
                        </div>
                    </v-col>
                    <v-col lg="4" cols="12">
                        <div class="zo-section">
                            <div class="zo-content">
                                <h3>سفارش شما</h3>
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="text-right">کتاب</th>
                                            <td class="text-left">جمع جزء</td>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>کتاب سفر شهادت گفتارهایی درباره واقعه عاشورا 1x</th>
                                            <td class="text-left">
                                                <div class="zo-price">
                                                    <div class="zo-regular">
                                                        <strong>250,000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>کتاب یادمانده‌های سردشت 3x</th>
                                            <td class="text-left">
                                                <div class="zo-price">
                                                    <div class="zo-regular">
                                                        <strong>250,000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>جمع جزء</th>
                                            <td class="text-left">
                                                <div class="zo-total">
                                                    <div class="zo-price">
                                                        <div class="zo-regular">
                                                            <strong>755,350</strong>
                                                            <small>تومان</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                                <br>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>کتاب</th>
                                            <td class="text-left">جمع جزء</td>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>حمل و نقل</th>
                                            <td class="text-left">
                                                <div class="zo-price">
                                                    <div class="zo-regular">
                                                        <strong>85,000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>جمع کل</th>
                                            <td class="text-left">
                                                <div class="zo-total">
                                                    <div class="zo-price">
                                                        <div class="zo-regular">
                                                            <strong>1,172,300</strong>
                                                            <small>تومان</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="zo-bank">
                                    <span>پرداخت امن زیبال</span>
                                    <img src="/assets/img/sample/zibal.png" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="zo-actions">
                            <v-btn block flat color="primary" class="zo-action">
                                پرداخت آنلاین
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
<style scoped>
.table tr td {
    border-bottom: 1px solid rgb(235, 235, 235);
}

</style>
