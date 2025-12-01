<template>

    <Head title="Welcome"/>
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
                    <v-row class="align-center">
                        <v-col cols="12">
                            <strong>همکاری با سازمان‌ها و دانشگاه‌ها</strong>
                        </v-col>
                        <v-col lg="3" cols="12" v-for="(institution, index) in about.meta.institutions">
                            <div class="zo-university" :key="index">
                                <figure>
                                    <img :src="institution.avatar" alt="">
                                </figure>
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
<!--                            <a href="#">
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
            <div class="zo-prof-section" v-if="about.meta.teachers">
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
                            <a :href="teacher.url">
                                <figure>
                                    <img :src="teacher.avatar" alt="">
                                </figure>
                                <strong>{{teacher.title}}</strong>
                            </a>
                            <ul>
                                <li><a href="#"><img src="/assets/img/site/social-1.svg" alt="" class="img-fluid"></a>
                                </li>
                                <li><a href="#"><img src="/assets/img/site/social-2.svg" alt="" class="img-fluid"></a>
                                </li>
                            </ul>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>
    </WebLayout>
</template>
<script setup>
import {Head, Link} from '@inertiajs/vue3'
import WebLayout from "@/Layouts/WebLayout.vue";
import {route} from "ziggy-js";
import {ref} from "vue";
const props = defineProps({
    about: Object
})
const about = ref(props.about.data)
</script>
