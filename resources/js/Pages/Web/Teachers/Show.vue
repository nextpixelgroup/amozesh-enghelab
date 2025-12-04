<template>

    <Head title="مدرس" />
    <WebLayout>
        <div class="zo-prof-section">
            <div class="zo-cover">
                <div class="zo-svg">
                    <img src="/assets/img/site/cover.svg" alt="" class="img-fluid">
                </div>
            </div>
            <v-container class="mt-5">
                <v-row>
                    <v-col cols="12">
                        <div class="zo-name">
                            <figure>
                                <img src="/assets/img/sample/1.webp" alt="">
                                <strong>{{teacher.firstname}} {{teacher.lastname}}</strong>
                                <span>نظریه‌پرداز توطئه و ایدئولوگ</span>
                            </figure>
                        </div>
                        <div class="zo-bio">
                            <p class="text-justify">
                                حسن رحیم‌پور ازغدی (زادهٔ ۱۳۴۲) سخنران، نظریه‌پرداز توطئه و ایدئولوگ محافظه‌کار اصولگرای ایرانی است. وی از سال ۱۳۸۲ عضو شورای عالی انقلاب فرهنگی ایران است. او از نسل جدید سخنرانان اصولگرا به‌شمار می‌رود که سعی دارد تا نظام و سیاست‌های آن را تئوریزه کند و توجیه ایدئولوژیک شیعی برای آن ارائه کند.
                            </p>
                            <p class="text-justify">
                                رحیم‌پور ازغدی همچنین به عنوان یکی از مدافعان برجسته «روحانیت حاکم» و گفتمان «شیعی حکومتی» در ایران تلقی می‌شود، قرائتی سیاسی از اسلام که قصد دارد حوزه‌های علمیه را دستکاری و کنترل کند، برخلاف ارتدکس سنتی شیعه که طرفدار استقلال از دولت است.
                            </p>
                        </div>
                        <ul>
                            <li>
                                <i class="mdi mdi-school"></i>
                                <div>
                                    <span>مدرک تحصیلی</span>
                                    <strong>کارشناسی ارشد الهیات و معارف اسلامی – گرایش فقه و اصول</strong>
                                </div>
                            </li>
                            <li>
                                <i class="mdi mdi-leaf"></i>
                                <div>
                                    <span>زمینه تدریس</span>
                                    <strong>علوم قرآنی، فقه و اصول، عقاید اسلامی، اخلاق اسلامی، تاریخ اسلام</strong>
                                </div>
                            </li>
                            <li>
                                <i class="mdi mdi-bookshelf"></i>
                                <div>
                                    <span>سوابق تدریس</span>
                                    <strong>بیش از ۱۰ سال تدریس در حوزه‌های علمیه، مدارس و مراکز آموزش دینی؛ برگزاری دوره‌های تخصصی تفسیر قرآن و آموزش احکام</strong>
                                </div>
                            </li>
                            <li>
                                <i class="mdi mdi-human-male-board"></i>
                                <div>
                                    <span>مهارت‌ها و تخصص</span>
                                    <strong>تفسیر و تحلیل متون دینی، خطابه و سخنرانی مذهبی، مهارت ارتباطی قوی، پژوهش در حوزه فقه و کلام، تولید محتوای آموزشی دین</strong>
                                </div>
                            </li>
                        </ul>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import WebLayout from "@/Layouts/WebLayout.vue";
const props = defineProps({
    teacher: Object
});
const teacher = props.teacher;

</script>
<style scoped>
.zo-prof-section {
    width: 100%;
    display: inline-block;
}

.zo-prof-section .zo-cover {
    width: 100%;
    display: inline-block;
    padding: 60px 0 90px;
    position: relative;
    background: var(--Primary) url(/public/assets/img/site/footer-pattern.png);
    color: var(--White)
}

.zo-prof-section .zo-cover .zo-svg {
    width: 100%;
    position: absolute;
    bottom: -65px;
    right: 0;
    left: 0;
    z-index: 15
}

.zo-prof-section .zo-cover .zo-svg img {
    width: 100%
}

.zo-prof-section .zo-name {
    margin: -150px 0 0;
    position: relative;
    text-align: center;
    z-index: 15
}

.zo-prof-section .zo-name figure img {
    width: 200px;
    height: 200px;
    display: block;
    margin: 0 auto 15px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 5px 15px rgba(0, 0, 0, .085);
    transition: all 0.25s ease-in-out
}

.zo-prof-section .zo-name strong {
    display: block;
    margin: 0 0 5px
}

.zo-prof-section .zo-name span {
    display: block;
    margin: 0 0 15px;
    color: var(--Sub)
}

.zo-prof-section .zo-bio {
    width: 100%;
    display: inline-block;
    margin: 0 0 15px
}

.zo-prof-section .zo-bio p {
    margin: 0 0 5px
}

.zo-prof-section ul {
    display: flex;
    flex-wrap: wrap;
    gap: 5px
}

.zo-prof-section ul li {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 0 0 10px
}

.zo-prof-section ul li i {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    background: rgba(5, 105, 60, .05);
    color: var(--Primary);
    border-radius: 50%
}

.zo-prof-section ul li div span {
    display: block;
    font-size: .95rem;
    color: var(--Sub)
}

</style>
