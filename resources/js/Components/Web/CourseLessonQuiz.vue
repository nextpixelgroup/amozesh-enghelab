<template>
    <div class="text-center pa-4">
        <v-dialog
            v-model="dialog"
            transition="dialog-bottom-transition"
            fullscreen
        >
            <template v-slot:activator="{ props: activatorProps }">
                <v-btn
                    variant="elevated"
                    color="primary"
                    prepend-icon="mdi-book-open-variant"
                    v-bind="activatorProps"
                >شرکت در آزمون
                </v-btn>
            </template>
            <v-card>
                <v-toolbar>
                    <v-toolbar-title>آزمون درس مفهوم ولایت فقیه</v-toolbar-title>
                    <v-toolbar-items>
                        <v-btn icon="mdi-close" @click="dialog = false"></v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card flat class="zo-questions">
                    <v-card-text class="zo-content">
                        <h5>فهم کلی از مفهوم ولایت فقیه</h5>
                        <p>
                            ولایت فقیه به معنای سپردن رهبری جامعه به فقیهی آگاه، عادل و توانمند است که بتواند در
                            نبود معصوم، هدایت دینی و مدیریتی جامعه را بر عهده بگیرد. این نظریه بر ضرورت پیوند میان
                            ارزش‌های دینی و اداره امور اجتماعی تأکید دارد.
                        </p>
                        <v-divider class="my-5"></v-divider>
                        <div class="zo-question">
                            <v-radio-group label="1- کدام گزینه بهترین تعریف ولایت فقیه را بیان می‌کند؟">
                                <v-radio label="اداره جامعه توسط گروهی از فقها" value="one"></v-radio>
                                <v-radio label="سپردن رهبری جامعه به فقیه عادل و آگاه در عصر غیبت"
                                         value="two"></v-radio>
                                <v-radio label="مشارکت فقیه در تصمیم‌گیری‌های قضایی" value="three"></v-radio>
                                <v-radio label="نظارت فقیه بر فعالیت‌های فرهنگی" value="four"></v-radio>
                            </v-radio-group>
                        </div>
                        <div class="zo-question">
                            <v-radio-group label="2- مبنای نظری ولایت فقیه بیشتر بر کدام موضوع تأکید دارد؟">
                                <v-radio label="تفکیک کامل دین از سیاست" value="one"></v-radio>
                                <v-radio label="پیوند میان هدایت دینی و اداره امور جامعه" value="two"></v-radio>
                                <v-radio label="اداره جامعه توسط نخبگان دانشگاهی" value="three"></v-radio>
                                <v-radio label="تمرکز قدرت در نهادهای محلی" value="four"></v-radio>
                            </v-radio-group>
                        </div>
                        <div class="zo-question">
                            <v-radio-group label="3- نظریه ولایت فقیه در کدام دوره اهمیت بیشتری یافته است؟">
                                <v-radio label="دوران صدر اسلام" value="one"></v-radio>
                                <v-radio label="دوره‌های خلافت اموی" value="two"></v-radio>
                                <v-radio label="اعصر غیبت امام معصوم" value="three"></v-radio>
                                <v-radio label="دوران سامانیان" value="four"></v-radio>
                            </v-radio-group>
                        </div>
                        <div class="zo-question">
                            <v-radio-group label="4- ولایت فقیه چه نقشی را برای فقیه در جامعه قائل است؟">
                                <v-radio label="نقش مشاور دینی بدون اختیار" value="one"></v-radio>
                                <v-radio label="نقش رهبری و هدایت جامعه" value="two"></v-radio>
                                <v-radio label="نقش قضایی محدود" value="three"></v-radio>
                                <v-radio label="نقش اداره امور مالی کشور" value="four"></v-radio>
                            </v-radio-group>
                        </div>
                        <div class="text-center">
                            <v-btn variant="elevated" color="primary">
                                ثبت نهایی پاسخ‌ها
                            </v-btn>
                        </div>
                    </v-card-text>
                </v-card>
            </v-card>
        </v-dialog>
    </div>
</template>
<script setup>
import {shallowRef} from 'vue'

const dialog = shallowRef(false)
const notifications = shallowRef(false)
const sound = shallowRef(true)
const widgets = shallowRef(false)
</script>
<style>
.zo-content {
    font-size: 1rem
}

.zo-content h5 {
    margin: 0 0 10px
}

.zo-questions {
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow-y: scroll
}

.zo-questions .zo-question .v-label {
    font-family: 'Estedad-Bold';
    opacity: 1
}

.zo-questions .zo-question .v-radio .v-label {
    font-family: 'Estedad-Regular'
}
</style>
