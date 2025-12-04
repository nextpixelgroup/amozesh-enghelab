<template>
    <Head :title="`انقلاب | ${page.props.pageTitle}`" />
    <div>
        <header>
            <div class="zo-header-section">
                <v-container>
                    <v-row dense class="align-center">
                        <v-col cols="12" lg="2">
                            <Link :href="route('web.index')">
                                <img src="/assets/img/site/logo-header.svg" alt="" class="img-fluid">
                            </Link>
                        </v-col>
                        <v-col cols="12" lg="8">
                            <div class="zo-menu" v-if="menu">
                                <ul>
                                    <li v-for="(item,index) in menu" :key="index" >
                                        <Link v-if="item.target === '_self'" :href="item.url" :target="item.target">{{ item.title }}</Link>
                                        <a v-else :href="item.url" :target="item.target">{{ item.title }}</a>
                                        <ul v-if="item.children.length">
                                            <li v-for="(item,index) in menu" :key="index" >
                                                <Link v-if="item.target === '_self'" :href="item.url" :target="item.target">{{ item.title }}</Link>
                                                <a v-else :href="item.url" :target="item.target">{{ item.title }}</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </v-col>
                        <v-col cols="12" lg="2">
                            <div class="zo-actions">
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <slot/>
        </main>

        <footer>
            <div class="zo-footer-section">
                <v-container>
                    <v-row>
                        <v-col class="v-col-lg-5 v-col-12">
                            <div class="zo-about">
                                <img src="/assets/img/site/logo-footer.svg" alt="" class="img-fluid">
                                <p>
                                    متن توضیحات مرتبط با خانه انقلاب اسلامی که توضیحات مختصری در مورد فعالیت‌ها،
                                    برنامه‌ها و دوره‌های برگزار شده در این مجموعه است در این قسمت قرار می‌گیرد.
                                </p>
                            </div>
                        </v-col>
                        <v-col class="v-col-lg-5 v-col-12">
                            <div class="zo-menus">
                                <div class="zo-menu">
                                    <span>دوره‌ها و فروشگاه</span>
                                    <ul>
                                        <li><a href="#">دوره‌ها</a></li>
                                        <li><a href="#">فروشگاه</a></li>
                                        <li><a href="#">پیگیری سفارش</a></li>
                                    </ul>
                                </div>
                                <div class="zo-menu">
                                    <span>لینک‌های سریع</span>
                                    <ul>
                                        <li><a href="#">تماس با ما</a></li>
                                        <li><a href="#">درباره ما</a></li>
                                        <li><a href="#">دستاوردها</a></li>
                                    </ul>
                                </div>
                                <div class="zo-menu">
                                    <span>ارتباط با مجموعه</span>
                                    <ul>
                                        <li><a href="#">تماس با ما</a></li>
                                        <li><a href="#">درباره ما</a></li>
                                        <li><a href="#">دستاوردها</a></li>
                                    </ul>
                                </div>
                            </div>
                        </v-col>
                        <v-col class="v-col-lg-2 v-col-12">
                            <div class="zo-social">
                                <ul>
                                    <li>
                                        <a href="#">
                                            <img src="/assets/img/site/social-1.svg" alt="" class="img-fluid">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="/assets/img/site/social-2.svg" alt="" class="img-fluid">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <div class="zo-copyright-section">
                تمامی حقوق برای خانه انقلاب اسلامی محفوظ می‌باشد. ۱۴۰۴
            </div>
        </footer>
    </div>

    <FlashMessage />
</template>
<script setup>
import {ref} from 'vue'
import FlashMessage from "@/Components/FlashMessage.vue";
import {Head, Link, usePage} from "@inertiajs/vue3";
import {route} from "ziggy-js";
const isMenuOpen = ref(false)
const page = usePage()
const menu = ref(page.props.menu)
</script>

