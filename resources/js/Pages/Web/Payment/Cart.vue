<template>
    <WebLayout title="Cart">
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
                                        <span>سبد خرید</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <div class="zo-section">
                            <v-table>
                                <thead>
                                    <tr>
                                        <th class="text-center">ردیف</th>
                                        <th class="text-center">تصویر</th>
                                        <th class="text-center">عنوان</th>
                                        <th class="text-center">قیمت</th>
                                        <th class="text-center">تعداد</th>
                                        <th class="text-center">جمع جزء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">
                                            <img src="/assets/img/sample/16.png" alt="">
                                        </td>
                                        <td class="text-center"><strong>کتاب یادمانده‌های سردشت</strong></td>
                                        <td class="text-center">
                                            <div class="zo-price">
                                                <div class="zo-regular">
                                                    <strong>250.000</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="zo-number">
                                                <v-number-input :max="30" :min="0" variant="outlined" reversecontrolVariant="split" label="" :hideInput="false" insetvariant="outlined" hide-details></v-number-input>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="zo-price">
                                                <div class="zo-regular">
                                                    <strong>250.000</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td class="text-center">
                                            <img src="/assets/img/sample/18.png" alt="">
                                        </td>
                                        <td class="text-center">
                                            <strong>کتاب سفر شهادت گفتارهایی درباره واقعه عاشورا</strong>
                                        </td>
                                        <td class="text-center">
                                            <div class="zo-price">
                                                <div class="zo-sale">
                                                    <del>120.000</del>
                                                    <div>
                                                        <strong>170.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="zo-number">
                                                <v-number-input :max="30" :min="0" variant="outlined" reversecontrolVariant="split" label="" :hideInput="false" insetvariant="outlined" hide-details></v-number-input>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="zo-price">
                                                <div class="zo-regular">
                                                    <strong>150.000</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td class="text-center">
                                            <img src="/assets/img/sample/17.png" alt="">
                                        </td>
                                        <td class="text-center">
                                            <strong>کتاب عروج عقل شرح حدیث عقل و جهل جلد دوم</strong>
                                        </td>
                                        <td class="text-center">
                                            <div class="zo-price">
                                                <div class="zo-sale">
                                                    <del>690.000</del>
                                                    <div>
                                                        <strong>250.000</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="zo-number">
                                                <v-number-input :max="30" :min="0" variant="outlined" reversecontrolVariant="split" label="" :hideInput="false" insetvariant="outlined" hide-details></v-number-input>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="zo-price">
                                                <div class="zo-regular">
                                                    <strong>309.000</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="zo-total">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">
                                            <div class="zo-price">
                                                <div class="zo-regular">
                                                    <strong>1.309.250</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td class="text-center">
                                            <div class="zo-price">
                                                <div class="zo-regular">
                                                    <strong>1.242.500</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
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