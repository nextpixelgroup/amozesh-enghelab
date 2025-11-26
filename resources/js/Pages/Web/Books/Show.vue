<template>
    <WebLayout>
        <div class="zo-book-section">
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
                                        <span>{{book.title}}</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row>
                    <v-col cols="12" lg="9">
                        <v-card flat class="zo-card">
                            <div class="zo-content">
                                <v-row>
                                    <v-col lg="5" cols="12">
                                        <div class="zo-thumbnail">
                                            <img src="/assets/img/sample/29.png" alt="">
                                        </div>
                                    </v-col>
                                    <v-col lg="7" cols="12">
                                        <div class="zo-title">
                                            <h1>مدیریت فرهنگی</h1>
                                        </div>
                                        <div class="zo-sub">
                                            در اندیشه‌ی حضرت آیت‌الله‌العظمی خامنه‌ای(مدّظلّه‌العالی)
                                        </div>
                                        <div class="zo-excerpt">
                                            <p>
                                                به منظور بهره‌مندی همگان از این جاریِ زلال، مؤسسه‌ی پژوهشی فرهنگی انقلاب اسلامی مصمّم گردید تا نسبت به تدوین و تنظیم، چاپ و انتشارِ سخنان معظم‌لَه در این‌گونه «مراسم» اقدام نماید، که این تصمیم با همّتِ والای حجت‌الاسلام والمسلمین آقای محمّدجواد حاج‌علی‌اکبری، عملی گردید و اینک آن مجموعه با عنوان «مطلع عشق» به نسل جوان و خواهندگان زندگی بانشاط تقدیم می‌گردد.
                                            </p>
                                        </div>
                                        <div class="zo-stars">
                                            <span>امتیاز دهی</span>
                                            <v-icon v-for="n in 5" :key="n" :color="n <= (hover || userRate) ? 'yellow darken-3' : 'grey lighten-1'" @mouseover="!isRatingLoading ? hover = n : null" @mouseleave="!isRatingLoading ? hover = 0 : null" @click="submitRating(n)" :disabled="isRatingLoading">
                                                mdi-star
                                            </v-icon>
                                        </div>
                                    </v-col>
                                </v-row>
                                <div class="zo-info">
                                    <ul>
                                        <li>
                                            <img src="/assets/img/site/c-user.svg" alt="" class="img-fluid">
                                            <div>
                                                <span>ناشر</span>
                                                <strong>انتشارات انقلاب اسلامی</strong>
                                            </div>
                                        </li>
                                        <li>
                                            <img src="/assets/img/site/c-calendar.svg" alt="" class="img-fluid">
                                            <div>
                                                <span>سال انتشار</span>
                                                <strong>1404</strong>
                                            </div>
                                        </li>
                                        <li>
                                            <img src="/assets/img/site/c-file.svg" alt="" class="img-fluid">
                                            <div>
                                                <span>قطع</span>
                                                <strong>رقعی</strong>
                                            </div>
                                        </li>
                                        <li>
                                            <img src="/assets/img/site/c-copy.svg" alt="" class="img-fluid">
                                            <div>
                                                <span>نوبت چاپ</span>
                                                <strong>اول</strong>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="zo-nav">
                                    <ul>
                                        <li>
                                            <a href="#" class="zo-active">مشخصات کتاب</a>
                                        </li>
                                        <li>
                                            <a href="#">نظرات کاربران</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="zo-text">
                                    <div class="zo-subtitle">درباره کتاب</div>
                                    به منظور بهره‌مندی همگان از این جاریِ زلال، مؤسسه‌ی پژوهشی فرهنگی انقلاب اسلامی مصمّم گردید تا نسبت به تدوین و تنظیم، چاپ و انتشارِ سخنان معظم‌لَه در این‌گونه «مراسم» اقدام نماید، که این تصمیم با همّتِ والای حجت‌الاسلام والمسلمین آقای محمّدجواد حاج‌علی‌اکبری، عملی گردید و اینک آن مجموعه با عنوان «مطلع عشق» به نسل جوان و خواهندگان زندگی بانشاط تقدیم می‌گردد.
                                </div>
                                <!--
                                Related Books
                                -->
                                <!--
                                Comments
                                -->
                            </div>
                        </v-card>
                    </v-col>
                    <v-col cols="12" lg="3">
                        <v-card flat class="zo-card">
                            <div class="zo-content">
                                <div class="zo-price-section">
                                    <div class="zo-label">
                                        <img src="/assets/img/site/price.svg" alt="">
                                        <span>قیمت</span>
                                    </div>
                                    <div class="zo-price">
                                        <div class="zo-regular">
                                            <strong>250.000</strong>
                                            <small>تومان</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="zo-number">
                                    <v-number-input :reverse="false" color="primary" controlVariant="split" label="" :hideInput="false" :inset="false" variant="outlined"></v-number-input>
                                </div>
                                <v-btn block flat rounded color="secondary" class="zo-add">
                                    خرید آنلاین
                                </v-btn>
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup>
import { ref } from 'vue';
import WebLayout from "@/Layouts/WebLayout.vue";

const userRate = ref(0);
const hover = ref(0);
const isRatingLoading = ref(false);

function submitRating(n) {
    if (isRatingLoading.value) return;

    isRatingLoading.value = true;

    setTimeout(() => {
        userRate.value = n;
        isRatingLoading.value = false;
    }, 500);
}

const props = defineProps({
    book: Object
});
const book = props.book;

</script>
