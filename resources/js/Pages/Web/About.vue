<template>
    <Head title="Welcome" />
    <WebLayout>
        <div class="zo-about-section">
            <v-container>
                <v-row dense>
                    <v-col cols="12">
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <Link :href="route('web.index')">خانه</Link>
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
            <div class="zo-excerpt-section">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="4" cols="12">
                            <h1>{{ about.title }}</h1>
                            <div v-html="about.content"></div>
                        </v-col>
                        <v-col lg="8" cols="12">
                            <figure>
                                <img :src="about.thumbnail?.url" alt="">
                            </figure>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-university-section" v-if="about.meta.institutions">
                <v-container>
                    <v-row dense class="justify-center align-center">
                        <v-col cols="12">
                            <div class="zo-title-section">
                                <img src="/assets/img/site/right-primary.svg" alt="">
                                <strong>همکاری با سازمان‌ها و دانشگاه‌ها</strong>
                                <img src="/assets/img/site/left-primary.svg" alt="">
                            </div>
                        </v-col>
                        <v-col lg="3" cols="12" v-for="(institution, index) in about.meta.institutions">
                            <div class="zo-university" :key="index">
                                <img :src="institution.avatar" alt="">
                                <span>{{institution.title}}</span>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-institute-section" v-if="about.meta.section1Title && about.meta.section1Content">
                <v-container>
                    <v-row class="align-center">
                        <v-col lg="6" cols="12">
                            <figure>
                                <img :src="about.meta.section1Thumbnail.url" alt="">
                            </figure>
                        </v-col>
                        <v-col lg="6" cols="12">
                            <strong>{{about.meta.section1Title}}</strong>
                            <p>{{about.meta.section1Content}}</p>
                            <!--<a href="#">
                                <span>مشاهده بیشتر</span>
                                <i class="mdi mdi-chevron-left"></i>
                            </a>-->
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <v-container v-if="about.meta.section2Title && about.meta.section2Content">
                <v-row class="justify-center">
                    <v-col lg="9" cols="12">
                        <div class="zo-title-section">
                            <img src="/assets/img/site/right-primary.svg" alt="">
                            <strong>{{about.meta.section2Title}}</strong>
                            <img src="/assets/img/site/left-primary.svg" alt="">
                        </div>
                        <p>{{about.meta.section2Content}}</p>
                    </v-col>
                </v-row>
            </v-container>
            <div class="zo-profs-section" v-if="about.meta.teachers">
                <v-container>
                    <v-row class="align-center">
                        <v-col cols="12">
                            <div class="zo-title-section">
                                <img src="/assets/img/site/right-primary.svg" alt="">
                                <strong>اساتید مجموعه</strong>
                                <img src="/assets/img/site/left-primary.svg" alt="">
                            </div>
                        </v-col>
                        <v-col lg="3" sm="6" cols="12" v-for="(teacher,index) in about.meta.teachers">
                            <TeacherCard :teacher="teacher" />
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>
    </WebLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3'
import WebLayout from "@/Layouts/WebLayout.vue";
import { route } from "ziggy-js";
import { ref } from "vue";
import TeacherCard from "@/Components/Web/TeacherCard.vue";
const props = defineProps({
    about: Object
})
const about = ref(props.about.data)

</script>
<style scoped>
.zo-about-section :deep(p) {
    text-align: justify;
    line-height: 2
}

.zo-about-section figure img {
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 5px 30px rgba(0, 0, 0, .085)
}

.zo-about-section .zo-excerpt-section {
    width: 100%;
    display: inline-block;
    margin: 0 0 15px
}

.zo-about-section .zo-excerpt-section h1 {
    font-size: 1.5rem
}

.zo-university-section {
    width: 100%;
    display: inline-block;
    padding: 60px 0
}

.zo-university-section .zo-university {
    width: 100%;
    height: 80px;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 15px;
    background: var(--White);
    border-radius: 1rem;
    box-shadow: 0 5px 30px rgba(0, 0, 0, .085);
}

@media (max-width: 1280px) {

    .zo-university-section {
        padding: 30px 0
    }
}

.zo-about-section .zo-institute-section {
    width: 100%;
    display: inline-block;
    margin: 0 0 30px
}

.zo-about-section .zo-institute-section strong {
    display: block;
    margin: 0 0 10px;
    font-size: 1.25rem
}

.zo-about-section .zo-institute-section a {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin: 15px 0;
    padding: 10px 25px;
    background: var(--Secondary);
    color: var(--White);
    border-radius: .5rem
}

.zo-about-section .zo-activity-section {
    width: 100%;
    display: inline-block;
    margin: 0 0 30px
}

.zo-about-section .zo-activity-section strong {
    display: block;
    margin: 0 0 10px;
    font-size: 1.5rem
}

.zo-about-section .zo-activity-section a {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin: 15px 0;
    padding: 10px 25px;
    background: var(--Secondary);
    color: var(--White);
    border-radius: .5rem
}

@media (max-width: 1280px) {

    .zo-about-section .zo-activity-section .v-row {
        flex-direction: column-reverse
    }
}

.zo-about-section .zo-prof-section {
    text-align: center
}

.zo-about-section .zo-prof-section a {
    display: block
}

.zo-about-section .zo-prof-section a:hover {
    margin: -15px 0 0
}

</style>
