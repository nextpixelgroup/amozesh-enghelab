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
                                <img :src="teacher.avatar" alt="">
                                <strong>{{teacher.firstname}} {{teacher.lastname}}</strong>
                                <span>{{teacher.academic_title}}</span>
                            </figure>
                        </div>
                        <div class="zo-bio">
                            <p class="text-justify">{{teacher.bio}}</p>
                        </div>
                        <ul v-if="0"> <!--به گفته کارفرما از قسمت حذف شد-->
                            <li>
                                <i class="mdi mdi-school"></i>
                                <div>
                                    <span>مدرک تحصیلی</span>
                                    <strong>{{ teacher.degree }}</strong>
                                </div>
                            </li>
                            <li>
                                <i class="mdi mdi-leaf"></i>
                                <div>
                                    <span>زمینه تدریس</span>
                                    <strong>{{teacher.teaching}}</strong>
                                </div>
                            </li>
                            <li>
                                <i class="mdi mdi-bookshelf"></i>
                                <div>
                                    <span>سوابق تدریس</span>
                                    <strong>{{teacher.history}}</strong>
                                </div>
                            </li>
                            <li>
                                <i class="mdi mdi-human-male-board"></i>
                                <div>
                                    <span>مهارت‌ها و تخصص</span>
                                    <strong>{{teacher.skills}}</strong>
                                </div>
                            </li>
                        </ul>
                    </v-col>
                </v-row>
                <div class="zo-courses-section mt-6">
                    <v-row>
                        <v-col>
                            <div class="zo-dashboard-section">
                                <div class="zo-header mb-3">
                                    <v-row class="align-center">
                                        <v-col cols="12">
                                            <div class="zo-info">
                                                <div class="zo-icon elevation-4">
                                                    <i class="mdi mdi-human-male-board"></i>
                                                </div>
                                                <div class="zo-name">
                                                    <strong class="d-block mb-1">دوره های مدرس</strong>
                                                    <span>در این بخش می توانید دوره های مدرس را مشاهده کنید.</span>
                                                </div>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                    <v-row dense class="align-center">
                        <v-col v-for="(course, index) in courses" :key="index" cols="12" sm="6" md="4" lg="3">
                            <CourseCard :course="course" />
                        </v-col>
                    </v-row>
                </div>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import WebLayout from "@/Layouts/WebLayout.vue";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";
const props = defineProps({
    teacher: Object,
    courses: Object,
});
const teacher = props.teacher?.data;
const courses = props.courses?.data;

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

.zo-courses-section .zo-name {
    margin: 0;
    position: relative;
    text-align: center;
    z-index: 15
}

.zo-courses-section .zo-name strong{
    text-align: right;
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
