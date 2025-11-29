<template>
    <WebLayout title="ContactUs">
        <div class="zo-contact-section">
            <v-container>
                <v-row dense>
                    <v-col cols="12">
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#">خانه</a>
                                    </li>
                                    <li>
                                        <span>درباره ما</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row dense>
                    <v-col lg="5" cols="12">
                        <div class="zo-content">
                            <span>فرم تماس</span>
                            <h1>از فرآیند اجرای کار سوالی دارید؟</h1>
                            <p>
                                اطلاعات خود در فرم مقابل را پر کنید و توضیحات لازم را بنوسید و برای ما ارسال کنید. کارشناسان خانه انقلاب اسلامی در سریع‌ترین زمان ممکن پیام شما را بررسی کرده و با شما تماس می‌گیرند.
                            </p>
                            <p>در صورت نیاز به تماس فوری از اطلاعات تماس زیر استفاده کنید.</p>
                            <ul class="zo-social">
                                <li>
                                    <v-btn color="primary">
                                        <img src="/assets/img/aparat.svg" width="20" height="20" />
                                    </v-btn>
                                </li>
                                <li>
                                    <v-btn color="primary">
                                        <img src="/assets/img/bale.svg" width="20" height="20" />
                                    </v-btn>
                                </li>
                                <li>
                                    <v-btn color="primary">
                                        <img src="/assets/img/eitaa.svg" width="20" height="20" />
                                    </v-btn>
                                </li>
                                <li>
                                    <v-btn color="primary">
                                        <img src="/assets/img/soroosh.svg" width="20" height="20" />
                                    </v-btn>
                                </li>
                            </ul>
                        </div>
                    </v-col>
                    <v-col lg="7" cols="12">
                        <v-row dense>
                            <v-col lg="6" cols="12">
                                <v-text-field hide-details class="mb-2" label="نام و نام خانوادگی خود را وارد کنید" variant="outlined" prepend-inner-icon="mdi-account-circle-outline">
                                </v-text-field>
                                <v-text-field prepend-inner-icon="mdi-email-outline" hide-details class="my-4" label="ایمیل خود را وارد کنید" variant="outlined">
                                </v-text-field>
                                <v-text-field prepend-inner-icon="mdi-phone-in-talk-outline" hide-details class="mb-2" label="شماره موبایل خود را وارد کنید" variant="outlined">
                                </v-text-field>
                            </v-col>
                            <v-col lg="6" cols="12" class="pr-3">
                                <v-textarea prepend-inner-icon="mdi-text" hide-details rows="7" label="پیام خود را بنویسید" variant="outlined">
                                </v-textarea>
                            </v-col>
                            <v-col cols="12">
                                <div class="col-12">
                                    <div class="text-center">
                                        <v-btn variant="elevated" color="primary" class="zo-send" height="45">ارسال پیام</v-btn>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row dense>
                    <v-col cols="12">
                        <div class="zo-map-section">
                            <div class="zo-info">
                                <v-row dense>
                                    <v-col lg="4" cols="12">
                                        <i class="mdi mdi-map-marker"></i>
                                        <strong>دفتر مرکزی</strong>
                                        <span>مشهد، خیابان سرشور ۲۹، پلاک ۱۴</span>
                                    </v-col>
                                    <v-col lg="4" cols="12">
                                        <i class="mdi mdi-phone-in-talk-outline"></i>
                                        <strong>تلفن‌های تماس</strong>
                                        <div>
                                            <a href="#">09332775003</a>
                                            <a href="#">09156240068</a>
                                        </div>
                                    </v-col>
                                    <v-col lg="4" cols="12">
                                        <i class="mdi mdi-email-outline"></i>
                                        <strong>ایمیل</strong>
                                        <span>Info@Revo.com</span>
                                    </v-col>
                                </v-row>
                            </div>
                            <div class="zo-map">
                                <img src="/assets/img/sample/map.jpg" alt="" class="img-fluid">
                            </div>
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
