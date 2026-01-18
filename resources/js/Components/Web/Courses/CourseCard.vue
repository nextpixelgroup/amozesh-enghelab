<template>
    <a :href="course.url" class="zo-course">
        <div v-if="isAuth && course?.passed" class="course-badge completed">
            تکمیل شده
        </div>
        <div v-else-if="isAuth && course?.enrolled" class="course-badge enrolled">
            درحال یادگیری
        </div>
        <div v-else-if="isAuth && course?.currentCourse" class="course-badge currentCourse">
            درانتظار شروع
        </div>
    <figure>
        <div class="zo-thumbnail">
            <img :src="course.thumbnail" alt="" class="img-fluid" />
        </div>
        <!--
            <div class="zo-category"  v-if="course.category">
                <img src="/assets/img/site/c-cat.svg" alt="" class="img-fluid"/>
                <span>{{ course.category }}</span>
            </div>
        -->
    </figure>
    <div class="zo-details">
        <v-tooltip :text="course.title" location="top">
            <template v-slot:activator="{ props }">
                <h2 v-bind="props">{{ course.title }}</h2>
            </template>
        </v-tooltip>
        <span class="zo-prof">{{ course.teacher }}</span>
        <ul>
            <li>
                <img src="/assets/img/site/c-clock.svg" alt="" class="img-fluid" />
                <span>{{ course.duration }}</span>
            </li>
            <li>
                <img src="/assets/img/site/c-students.svg" alt="" class="img-fluid" />
                <span>{{ course.students > 0 ? course.students : 'بدون' }} دانشجو</span>
            </li>
        </ul>
        <div class="zo-price">
            <span>قیمت</span>
            <strong>{{ course.price }}</strong>
        </div>
        <div class="zo-more">اطلاعات بیشتر</div>
    </div>
    </a>
</template>
<script setup lang="ts">
import {Link, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
const page = usePage();
const props = defineProps({
    course: {
        type: Object,
        required: true
    }
})

const isAuth = ref(page.props.isAuth);
</script>
<style>
.zo-course {
    position: relative; /* ظرف اصلی برای موقعیت دهی برچسب */
    display: block; /* اطمینان از اینکه block است */
}

.course-badge {
    position: absolute;
    top: 10px; /* فاصله از بالا */
    left: 10px; /* فاصله از راست (برای راست به چپ) */
    z-index: 10; /* برای اطمینان از اینکه بالای همه چیز باشد */
    padding: 5px 10px;
    border-radius: 8px 0 0;
    font-size: 0.8rem;
    font-weight: bold;
    color: white;
}

/* استایل خاص برای حالت تکمیل شده */
.course-badge.completed {
    background-color: var(--Primary); /* سبز برای موفقیت */
    border: 1px solid var(--Primary);
}

.course-badge.enrolled {
    background-color: var(--Secondary); /* سبز برای موفقیت */
    border: 1px solid var(--Secondary);
}
.course-badge.currentCourse {
    background-color: gray; /* سبز برای موفقیت */
    border: 1px solid gray;
}
</style>
