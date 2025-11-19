<template>
    <v-card flat class="zo-card">
        <div class="zo-content">
            <div class="zo-thumbnail">
                <img src="/assets/img/sample/15.png" alt="">
            </div>
            <div class="zo-price">
                رایگان
            </div>
            <v-btn
                block
                flat
                variant="elevated"
                class="zo-add"
                @click="enrollInCourse"
                :disabled="isEnroll"
                :loading="isEnroll"
            >
                <img src="/assets/img/site/click.svg" alt="">
                ثبت نام در این دوره
            </v-btn>
            <div class="zo-users">
                <div class="zo-name">
                    <img src="/assets/img/site/c-gap-green.svg" alt="">
                    <span>کل شرکت کنندگان</span>
                </div>
                <div class="zo-stat">76</div>
            </div>
            <div class="zo-info">
                <ul>
                    <li>
                        <div>
                            <img src="/assets/img/site/c-clock.svg" alt="">
                            <span>شروع دوره</span>
                        </div>
                        <strong>یکشنبه ۲۴ آذر ۱۴۰۴</strong>
                    </li>
                    <li>
                        <div>
                            <img src="/assets/img/site/c-calendar.svg" alt="">
                            <span>تعداد جلسات</span>
                        </div>
                        <strong>۱۸ جلسه</strong>
                    </li>
                    <li>
                        <div>
                            <img src="/assets/img/site/c-file.svg" alt="">
                            <span>دسته بندی موضوع</span>
                        </div>
                        <strong>ازدواج</strong>
                    </li>
                    <li>
                        <div>
                            <img src="/assets/img/site/c-gap.svg" alt="">
                            <span>ظرفیت دوره</span>
                        </div>
                        <strong>۲۰ نفر</strong>
                    </li>
                </ul>
            </div>
            <div class="zo-prof">
                <strong class="zo-title">درباره مدرس</strong>
                <div class="zo-name">
                    <div class="zo-avatar">
                        <img src="/assets/img/prof/3.jpg" alt="">
                    </div>
                    <div>
                        <span>محمد جواد حریری</span>
                        <div class="zo-social">
                            <img src="/assets/img/site/eitaa-grey.svg" alt="">
                            <img src="/assets/img/site/soroosh-grey.svg" alt="">
                        </div>
                    </div>
                </div>
                <ul>
                    <li>
                        <img src="/assets/img/site/c-lessons.svg" alt="">
                        <span>9 دوره</span>
                    </li>
                    <li>
                        <img src="/assets/img/site/c-gap-grey.svg" alt="">
                        <span>۵۴۸ دانشجو</span>
                    </li>
                </ul>
                <p>
                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است،
                    چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که...
                </p>
            </div>
        </div>
    </v-card>
</template>
<script setup>

import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
const props = defineProps({
    course: {
        type: Object,
        required: true
    }
})
const isEnroll = ref(false);
const enrollInCourse = () => {
    try {
        router.post(route('web.courses.enroll', { course: props.course.id }), {}, {
            preserveScroll: true,
            onStart: () => {
                isEnroll.value = true;
            },
            onSuccess: () => {
                // عملیات بعد از موفقیت‌آمیز بودن درخواست
                isEnroll.value = false;
                // می‌توانید state مربوطه را به‌روز کنید
            },
            onError: (errors) => {
                console.error('خطا در ثبت‌نام:', errors);
                isEnroll.value = false;
            }
        });
    } catch (error) {
        console.error('خطا در ارسال درخواست:', error);
        isEnroll.value = false;
    }
}
</script>
<style scoped>
.zo-content {
    padding: 20px
}

.zo-content .zo-thumbnail {
    margin: 0 0 15px
}

.zo-content .zo-thumbnail img {
    width: 100%
}

.zo-content .zo-price {
    width: 100%;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 0 15px;
    background: url('/assets/img/free.svg') no-repeat;
    background-position: center;
    background-size: contain;
    text-align: center
}

.zo-content .zo-add {
    min-height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    background: rgb(50, 180, 55);
    color: rgb(255, 255, 255);
    border-radius: 0.75rem
}

.zo-content .zo-add img {
    margin: 0 0 0 10px
}

.zo-content .zo-users {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 15px 0;
    padding: 15px;
    color: rgb(5, 105, 60);
    border: 1px dashed rgb(240, 240, 240);
    border-radius: .75rem
}

.zo-content .zo-users .zo-name {
    display: flex;
    align-items: center;
    gap: 5px
}

.zo-content .zo-info {
    width: 100%;
    display: inline-block;
    padding: 0 0 10px;
    border-bottom: 1px dashed rgb(240, 240, 240);
}

.zo-content .zo-info ul li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0
}

.zo-content .zo-info ul li div {
    display: flex;
    align-items: center;
    gap: 5px
}

.zo-content .zo-prof {
    margin: 15px 0 0
}

.zo-content .zo-prof .zo-title {
    display: block;
    margin: 0 0 5px;
    font-size: 1.125rem;
    color: var(--Secondary);
}

.zo-content .zo-prof .zo-name {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 0 0 15px
}

.zo-content .zo-prof .zo-name .zo-avatar img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 50%
}

.zo-content .zo-prof .zo-name .zo-social {
    display: flex;
    gap: 10px;
    margin: 5px 0 0
}

.zo-content .zo-prof ul {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 0 0 15px
}

.zo-content .zo-prof ul li {
    display: flex;
    align-items: center;
    gap: 5px;
    position: relative;
    color: rgb(120, 125, 135)
}

.zo-content .zo-prof p {
    font-size: .90rem;
    color: rgb(120, 125, 135)
}
</style>
